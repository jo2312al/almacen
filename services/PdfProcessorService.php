<?php

namespace app\services;

use Yii;
use app\models\Alumno;
use app\models\Carrera;
use app\models\Servicio;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class PdfProcessorService
{
    public function processPdf($pdfFile, $tipoDocumento = null)
    {
        try {
            if (!$pdfFile) {
                return ['status' => 'error', 'message' => 'No se recibió ningún archivo PDF.'];
            }

            if ($pdfFile->error !== UPLOAD_ERR_OK || !$pdfFile->tempName) {
                return ['status' => 'error', 'message' => $this->uploadErrorMessage($pdfFile->error)];
            }

            $data = $this->callOcrApi($pdfFile, $tipoDocumento ?: Yii::$app->params['ocrTipoDocumento']);
            $fields = $data['fields'] ?? [];
            $matricula = $this->cleanText($this->fieldValue($fields, 'alu_matricula'));

            if (empty($matricula)) {
                return ['status' => 'error', 'message' => 'La API OCR no pudo extraer una matrícula válida.'];
            }

            $alumnoExistente = Alumno::findOne(['alu_matricula' => $matricula]);
            if ($alumnoExistente) {
                return ['status' => 'ok', 'exists' => true, 'alumnoData' => $alumnoExistente->getAttributes(), 'ocr' => $data];
            }

            return [
                'status' => 'ok',
                'exists' => false,
                'processedData' => $this->mapApiDataToModel($fields, $matricula),
                'ocr' => $data,
            ];
        } catch (ConnectException $e) {
            return ['status' => 'error', 'message' => 'Error de conexión con la API OCR.'];
        } catch (RequestException $e) {
            return ['status' => 'error', 'message' => $this->requestErrorMessage($e)];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => 'Error OCR: ' . $e->getMessage()];
        }
    }

    private function callOcrApi($pdfFile, $tipoDocumento)
    {
        $apiUrl = Yii::$app->params['ocrApiUrl'] ?? Yii::$app->params['pdfApiUrl'];
        $apiKey = Yii::$app->params['ocrApiKey'] ?? '';
        $headers = [];
        if ($apiKey !== '') {
            $headers['X-API-Key'] = $apiKey;
        }

        $client = new Client(['timeout' => 180.0, 'http_errors' => true]);
        $handle = fopen($pdfFile->tempName, 'r');
        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el PDF temporal para enviarlo a la API OCR.');
        }

        try {
            $response = $client->request('POST', $apiUrl, [
                'headers' => $headers,
                'multipart' => [
                    [
                        'name' => 'id_tipo_documento',
                        'contents' => $tipoDocumento,
                    ],
                    [
                        'name' => 'file',
                        'contents' => $handle,
                        'filename' => $pdfFile->name,
                        'headers' => ['Content-Type' => 'application/pdf'],
                    ],
                ],
            ]);
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }

        $json = json_decode($response->getBody()->getContents(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON inválido de la API OCR.');
        }

        return $json;
    }

    private function mapApiDataToModel($fields, $matricula)
    {
        return [
            'alu_matricula' => $matricula,
            'alu_nombre' => $this->cleanText($this->fieldValue($fields, 'alu_nombre')),
            'alu_paterno' => $this->cleanText($this->fieldValue($fields, 'alu_paterno')),
            'alu_materno' => $this->cleanText($this->fieldValue($fields, 'alu_materno')),
            'alu_ingreso' => $this->calculateAnioIngreso($matricula),
            'alu_carrera_id' => $this->findCarreraId($this->fieldValue($fields, 'alu_carrera')),
            'alu_servicio_id' => $this->findServicioId($this->fieldValue($fields, 'alu_servicio')),
        ];
    }

    private function fieldValue(array $fields, $name)
    {
        $field = $fields[$name] ?? null;
        if (is_array($field)) {
            return $field['value'] ?? '';
        }

        return $field ?? '';
    }

    private function calculateAnioIngreso($matricula)
    {
        $dosDigitos = substr($matricula, 0, 2);
        if (!is_numeric($dosDigitos)) return null;

        $valor = intval($dosDigitos);
        return ($valor >= 74 && $valor <= 99) ? '19' . $dosDigitos : '20' . $dosDigitos;
    }

    private function findCarreraId($texto)
    {
        $texto = $this->cleanText($texto);
        if (!$texto) return null;

        $model = Carrera::find()->where(['like', 'car_nombre', $texto])->one();
        return $model ? $model->car_id : null;
    }

    private function findServicioId($texto)
    {
        if (!$texto) return null;

        if (!preg_match('/(19|20)\d{2}/', $texto, $matches)) {
            return null;
        }
        $anio = $matches[0];
        $textoMin = mb_strtolower($texto);
        $periodoId = null;

        if (preg_match('/ene|feb|mar|abr|may|jun|jul/', $textoMin)) {
             $periodoId = 1;
        }
        if (preg_match('/ago|sep|oct|nov|dic/', $textoMin)) {
             $periodoId = 2;
        }

        if ($anio && $periodoId) {
            $model = Servicio::find()
                ->where(['ser_anio' => $anio, 'ser_periodo_id' => $periodoId])
                ->one();
            return $model ? $model->ser_id : null;
        }
        return null;
    }

    private function cleanText($text)
    {
        $text = trim(str_replace(',', '', $text ?? ''));
        return mb_strtoupper($text, 'UTF-8') === 'NO ENCONTRADO' ? '' : $text;
    }

    private function requestErrorMessage(RequestException $e)
    {
        $response = $e->getResponse();
        if (!$response) {
            return 'Error conectando con la API OCR: ' . $e->getMessage();
        }

        $body = (string)$response->getBody();
        $json = json_decode($body, true);
        $message = $json['error'] ?? $json['message'] ?? $body;

        return 'Error API OCR HTTP ' . $response->getStatusCode() . ': ' . $message;
    }

    private function uploadErrorMessage($errorCode)
    {
        if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
            return 'El PDF supera el tamaño máximo permitido por el servidor.';
        }

        return 'No se pudo recibir el PDF en el servidor.';
    }
}
