<?php

namespace app\services;

use Yii;
use app\models\Alumno;
use app\models\Carrera;
use app\models\Servicio;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class PdfProcessorService
{
    private $apiUrl = 'http://127.0.0.1:5000/extract';

    public function processPdf($pdfFile)
    {
        try {
            // 1. Llamar a la API
            $data = $this->callPythonApi($pdfFile);

            // 2. Extraer y limpiar matrícula
            $matricula = $this->cleanText($data['fields']['alu_matricula']['value'] ?? '');
            
            if (empty($matricula)) {
                return ['status' => 'error', 'message' => 'La API no pudo extraer una matrícula válida.'];
            }

            // 3. Verificar existencia
            $alumnoExistente = Alumno::findOne(['alu_matricula' => $matricula]);
            if ($alumnoExistente) {
                return ['status' => 'ok', 'exists' => true, 'alumnoData' => $alumnoExistente->getAttributes()];
            }

            // 4. Procesar datos nuevos
            return [
                'status' => 'ok', 
                'exists' => false, 
                'processedData' => $this->mapApiDataToModel($data['fields'], $matricula)
            ];

        } catch (ConnectException $e) {
            return ['status' => 'error', 'message' => 'Error de Conexión con la API Python.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    private function callPythonApi($pdfFile)
    {
        $client = new Client(['timeout' => 120.0]);
        $response = $client->request('POST', $this->apiUrl, [
            'multipart' => [[
                'name'     => 'file',
                'contents' => fopen($pdfFile->tempName, 'r'),
                'filename' => $pdfFile->name
            ]]
        ]);
        
        $json = json_decode($response->getBody()->getContents(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON inválido de la API.");
        }
        return $json;
    }

    private function mapApiDataToModel($fields, $matricula)
    {
        return [
            'alu_matricula' => $matricula,
            'alu_nombre'    => $this->cleanText($fields['alu_nombre']['value']),
            'alu_paterno'   => $this->cleanText($fields['alu_paterno']['value']),
            'alu_materno'   => $this->cleanText($fields['alu_materno']['value']),
            'alu_ingreso'   => $this->calculateAnioIngreso($matricula),
            'alu_carrera_id'=> $this->findCarreraId($fields['alu_carrera']['value']),
            'alu_servicio_id'=> $this->findServicioId($fields['alu_servicio']['value']),
        ];
    }

    private function calculateAnioIngreso($matricula)
    {
        $dosDigitos = substr($matricula, 0, 2);
        if (!is_numeric($dosDigitos)) return null;
        
        $valor = intval($dosDigitos);
        // Tu lógica: 74-99 = 19XX, 00-73 = 20XX
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
        
        // 1. Encontrar Año
        if (!preg_match('/(19|20)\d{2}/', $texto, $matches)) {
            return null; // Si no hay año, no podemos buscar servicio
        }
        $anio = $matches[0];

        // 2. Encontrar Periodo
        $textoMin = mb_strtolower($texto);
        $periodoId = null;

        // Lógica Ene-Jul (ID 1)
        if (preg_match('/ene|feb|mar|abr|may|jun|jul/', $textoMin)) {
             $periodoId = 1;
        }
        // Lógica Jul-Dic (ID 2) - Prioridad a palabras clave de segundo semestre
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
        return trim(str_replace(',', '', $text ?? ''));
    }
}