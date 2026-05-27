<?php

namespace app\services;

use Yii;
use app\models\Caja;
use app\models\Anaquel;
use app\models\Nivelalmacenamiento;
use yii\helpers\Url;

/**
 * Servicio encargado de la lógica de negocio de las Cajas.
 * Maneja la generación de códigos únicos y códigos QR.
 */
class CajaService
{
    /**
     * Crea una nueva caja generando automáticamente su código.
     * @param array $postData Datos del formulario (Yii::$app->request->post())
     * @return array Resultado ['success', 'message', 'model', 'errors']
     */
    public function createCaja($postData)
    {
        $model = new Caja();
        
        // Cargar datos
        if (!$model->load($postData)) {
            return ['success' => false, 'message' => 'No se recibieron datos válidos.', 'model' => $model];
        }

        // Validar dependencias (Anaquel y Nivel)
        $anaquelId = $model->caj_anaquel_id;
        $nivelId = $model->caj_nivel_id;

        if (!$anaquelId || !$nivelId) {
            $model->addError('caj_anaquel_id', 'Debe seleccionar un Anaquel y un Nivel.');
            return ['success' => false, 'message' => 'Faltan datos de ubicación.', 'model' => $model];
        }

        // Generar Código Automático
        $codigo = $this->calculateNextCode($anaquelId, $nivelId);
        
        if (!$codigo) {
            return ['success' => false, 'message' => 'Error al generar el código: Anaquel o Nivel no encontrados.', 'model' => $model];
        }

        $model->caj_codigo = $codigo;

        // Guardar
        if ($model->save()) {
            return [
                'success' => true, 
                'message' => "Caja creada con código: $codigo", 
                'model' => $model
            ];
        } else {
            return [
                'success' => false, 
                'message' => 'Error al guardar en base de datos.', 
                'model' => $model,
                'errors' => $model->getErrors()
            ];
        }
    }

    /**
     * Genera la imagen RAW del código QR.
     * @param int $caj_id ID de la caja
     * @return array|null Datos del QR ['content', 'filename'] o null si falla.
     * @throws \Exception
     */
    public function generateQrImage($caj_id)
    {
        try {
            // Asumimos que tienes configurado el componente 'qr' en web.php
            $qr = Yii::$app->get('qr');

            // Construye la URL absoluta hacia la vista de la caja
            $qrText = Url::to(['caja/view', 'caj_id' => $caj_id], true);
            $fileName = 'qr_caja_' . $caj_id . '.png';

            // Genera la imagen del QR en memoria
            $qrImageContent = $qr
                ->setText($qrText)
                ->setSize(300)
                ->setErrorCorrectionLevel('H')
                ->writeString();

            return [
                'content' => $qrImageContent,
                'filename' => $fileName
            ];

        } catch (\Exception $e) {
            Yii::error('Error generando QR: ' . $e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Lógica interna para calcular el código consecutivo (ej. AC01A0001)
     */
    private function calculateNextCode($anaquelId, $nivelId)
    {
        $anaquel = Anaquel::findOne($anaquelId);
        $nivel = Nivelalmacenamiento::findOne($nivelId);

        if (!$anaquel || !$nivel) {
            return null;
        }

        // 1. Obtener la primera letra del nombre del nivel
        $primerLetraNivel = strtoupper(substr($nivel->niv_nombre, 0, 1));

        // 2. Contar cajas existentes en ese nivel y anaquel
        $count = Caja::find()
            ->where(['caj_anaquel_id' => $anaquelId, 'caj_nivel_id' => $nivelId])
            ->count();

        // 3. Generar el contador (relleno con ceros a la izquierda)
        $contador = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        // 4. Construir código final
        // Formato: AC + ID_Anaquel(2 digitos) + LetraNivel + Contador(4 digitos)
        return "AC" . str_pad($anaquel->ana_id, 2, '0', STR_PAD_LEFT) . 
               $primerLetraNivel . 
               $contador;
    }
}