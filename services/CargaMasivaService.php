<?php

namespace app\services;

use app\models\Alumno;
use app\models\Archivo;
use app\models\AreaGeneradora;
use app\models\CargaMasiva;
use app\models\CargaMasivaDetalle;
use app\models\CargaMasivaForm;
use app\models\ClaveProgramatica;
use app\models\Fondo;
use app\models\SeccionSerie;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class CargaMasivaService
{
    public function process(CargaMasivaForm $form, array $files)
    {
        $batch = new CargaMasiva([
            'car_caja_id' => $form->caja_id,
            'car_estado' => CargaMasiva::ESTADO_PROCESANDO,
            'car_total' => count($files),
            'car_creado_por' => Yii::$app->user->isGuest ? null : Yii::$app->user->id,
            'car_creado_en' => date('Y-m-d H:i:s'),
        ]);
        $batch->save(false);

        $summary = [
            CargaMasivaDetalle::ESTADO_GUARDADO => 0,
            CargaMasivaDetalle::ESTADO_PENDIENTE => 0,
            CargaMasivaDetalle::ESTADO_ERROR => 0,
        ];

        foreach ($files as $file) {
            $state = $this->processFile($batch, $form, $file);
            $summary[$state]++;
        }

        $batch->car_estado = CargaMasiva::ESTADO_FINALIZADA;
        $batch->car_exitosos = $summary[CargaMasivaDetalle::ESTADO_GUARDADO];
        $batch->car_pendientes = $summary[CargaMasivaDetalle::ESTADO_PENDIENTE];
        $batch->car_errores = $summary[CargaMasivaDetalle::ESTADO_ERROR];
        $batch->car_finalizado_en = date('Y-m-d H:i:s');
        $batch->save(false);

        return $batch;
    }

    private function processFile(CargaMasiva $batch, CargaMasivaForm $form, UploadedFile $file)
    {
        $detail = new CargaMasivaDetalle([
            'det_carga_id' => $batch->car_id,
            'det_nombre_original' => $file->name,
            'det_estado' => CargaMasivaDetalle::ESTADO_ERROR,
            'det_creado_en' => date('Y-m-d H:i:s'),
        ]);

        $processor = new PdfProcessorService();
        $result = $processor->processPdf($file);

        if (($result['status'] ?? null) !== 'ok') {
            $detail->det_mensaje = $result['message'] ?? 'No se pudo analizar el PDF.';
            $detail->save(false);
            return CargaMasivaDetalle::ESTADO_ERROR;
        }

        $alumnoData = $result['exists'] ? $result['alumnoData'] : ($result['processedData'] ?? []);
        $matricula = $alumnoData['alu_matricula'] ?? null;
        $detail->det_matricula_detectada = $matricula;

        if (!$result['exists']) {
            $detail->det_estado = CargaMasivaDetalle::ESTADO_PENDIENTE;
            $detail->det_mensaje = 'Alumno no registrado. Revisar y capturar antes de guardar el archivo.';
            $detail->save(false);
            return CargaMasivaDetalle::ESTADO_PENDIENTE;
        }

        $alumno = Alumno::findOne(['alu_id' => $alumnoData['alu_id']]);
        if (!$alumno) {
            $detail->det_mensaje = 'La API encontró matrícula, pero el alumno ya no existe en la base.';
            $detail->save(false);
            return CargaMasivaDetalle::ESTADO_ERROR;
        }

        $archivo = $this->buildArchivo($form, $file, $alumno);
        $archivo->file = $file;

        if (!$archivo->validate()) {
            $detail->det_mensaje = json_encode($archivo->getErrors(), JSON_UNESCAPED_UNICODE);
            $detail->save(false);
            return CargaMasivaDetalle::ESTADO_ERROR;
        }

        $storage = new ArchivoStorageService();
        $stored = $storage->saveValidatedArchivo($archivo, $file);

        if (!$stored['success']) {
            $detail->det_mensaje = $stored['message'];
            $detail->save(false);
            return CargaMasivaDetalle::ESTADO_ERROR;
        }

        $detail->det_archivo_id = $stored['id'];
        $detail->det_alumno_id = $alumno->alu_id;
        $detail->det_estado = CargaMasivaDetalle::ESTADO_GUARDADO;
        $detail->det_mensaje = 'Archivo guardado correctamente.';
        $detail->save(false);

        return CargaMasivaDetalle::ESTADO_GUARDADO;
    }

    private function buildArchivo(CargaMasivaForm $form, UploadedFile $file, Alumno $alumno)
    {
        $archivo = new Archivo();
        $archivo->scenario = 'create';
        $archivo->arc_caja_id = $form->caja_id;
        $archivo->arc_alumno_id = $alumno->alu_id;
        $archivo->arc_fondo_id = $form->fondo_id ?: null;
        $archivo->arc_clave_programatica_id = $form->clave_programatica_id ?: null;
        $archivo->arc_area_generadora_id = $form->area_generadora_id ?: null;
        $archivo->arc_seccion_serie_id = $form->seccion_serie_id ?: null;
        $archivo->arc_codigo = $this->buildCodigo($form, $alumno);
        $archivo->arc_nombre_documento = pathinfo($file->name, PATHINFO_FILENAME) ?: $archivo->arc_codigo;

        return $archivo;
    }

    private function buildCodigo(CargaMasivaForm $form, Alumno $alumno)
    {
        $parts = [
            $this->catalogCode(Fondo::class, $form->fondo_id, 'fon_codigo'),
            $this->catalogCode(ClaveProgramatica::class, $form->clave_programatica_id, 'cla_codigo'),
            $this->catalogCode(AreaGeneradora::class, $form->area_generadora_id, 'are_codigo'),
            $this->catalogCode(SeccionSerie::class, $form->seccion_serie_id, 'sec_codigo'),
            $alumno->alu_matricula,
            date('Y'),
        ];

        return implode('/', $parts);
    }

    private function catalogCode($class, $id, $attribute)
    {
        if (!$id) {
            return '00';
        }

        $model = $class::findOne($id);
        return $model ? ArrayHelper::getValue($model, $attribute, '00') : '00';
    }
}
