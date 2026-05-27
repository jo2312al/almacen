<?php

namespace app\services;

use Yii;
use app\models\Archivo;
use app\models\Alumno;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Servicio encargado de la gestión física y lógica de los archivos.
 */
class ArchivoStorageService
{
    /**
     * Guarda el archivo físico y el registro en base de datos.
     * @param Archivo $model El modelo con los datos cargados.
     * @param UploadedFile $fileInstance La instancia del archivo subido.
     * @return array Resultado ['success', 'message', 'errors', 'id']
     */
    public function saveArchivo(Archivo $model, $fileInstance)
    {
        // 1. Validar que la instancia del archivo existe
        if (!$fileInstance) {
            return ['success' => false, 'message' => 'No se ha subido ningún archivo (El archivo es nulo).'];
        }

        // --- CORRECCIÓN CRÍTICA ---
        // Asignamos el archivo al modelo ANTES de validar.
        // Esto permite que las reglas del modelo (ej. [['file'], 'required']) pasen correctamente.
        $model->file = $fileInstance;
        // --------------------------

        // 2. Validar datos del modelo (incluyendo el archivo)
        if (!$model->validate()) {
            return [
                'success' => false, 
                'message' => 'Error de validación en los datos.', 
                'errors' => $model->getErrors()
            ];
        }

        // 3. Verificar Alumno
        $alumno = Alumno::findOne($model->arc_alumno_id);
        if (!$alumno) {
            return ['success' => false, 'message' => 'El alumno seleccionado no existe.'];
        }

        // 4. Preparar Directorios y Nombres
        // Reemplazar barras '/' por guiones para que no rompa la ruta
        $safeFilename = str_replace('/', '-', $model->arc_codigo);
        
        // Directorio base: web/archivos/MATRICULA
        $baseDir = Yii::getAlias('@webroot/archivos/') . $alumno->alu_matricula;
        
        // Crear directorio recursivamente si no existe
        if (!is_dir($baseDir)) {
            if (!FileHelper::createDirectory($baseDir, 0775, true)) {
                return ['success' => false, 'message' => 'No se pudo crear el directorio: ' . $baseDir];
            }
        }

        // Definir rutas
        [$absolutePath, $relativePath] = $this->buildAvailablePath($baseDir, 'archivos/' . $alumno->alu_matricula, $safeFilename);

        // 5. Asignar ruta relativa para guardar en BD
        $model->arc_ruta = $relativePath;

        return $this->storeValidatedArchivo($model, $fileInstance, $absolutePath);
    }

    public function saveValidatedArchivo(Archivo $model, UploadedFile $fileInstance)
    {
        $alumno = Alumno::findOne($model->arc_alumno_id);
        if (!$alumno) {
            return ['success' => false, 'message' => 'El alumno seleccionado no existe.'];
        }

        $safeFilename = str_replace('/', '-', $model->arc_codigo);
        $baseDir = Yii::getAlias('@webroot/archivos/') . $alumno->alu_matricula;

        if (!is_dir($baseDir) && !FileHelper::createDirectory($baseDir, 0775, true)) {
            return ['success' => false, 'message' => 'No se pudo crear el directorio: ' . $baseDir];
        }

        [$absolutePath, $relativePath] = $this->buildAvailablePath($baseDir, 'archivos/' . $alumno->alu_matricula, $safeFilename);
        $model->arc_ruta = $relativePath;

        return $this->storeValidatedArchivo($model, $fileInstance, $absolutePath);
    }

    private function storeValidatedArchivo(Archivo $model, UploadedFile $fileInstance, $absolutePath)
    {
        if (!$fileInstance->saveAs($absolutePath)) {
            return ['success' => false, 'message' => 'Error crítico: No se pudo mover el archivo al directorio final. Verifique permisos de escritura.'];
        }

        if ($model->save(false)) {
            return [
                'success' => true,
                'message' => '¡Archivo registrado y guardado exitosamente!',
                'id' => $model->arc_id
            ];
        }

        @unlink($absolutePath);
        return [
            'success' => false,
            'message' => 'Error al guardar el registro en la base de datos.',
            'errors' => $model->getErrors()
        ];
    }

    private function buildAvailablePath($baseDir, $relativeDir, $safeFilename)
    {
        $counter = 1;
        $candidate = $safeFilename;

        do {
            $absolutePath = $baseDir . '/' . $candidate . '.pdf';
            $relativePath = $relativeDir . '/' . $candidate . '.pdf';
            $counter++;
            $candidate = $safeFilename . '-' . $counter;
        } while (file_exists($absolutePath));

        return [$absolutePath, $relativePath];
    }

    /**
     * Elimina el archivo físico y el registro.
     */
    public function deleteArchivo($id)
    {
        $model = Archivo::findOne($id);
        if (!$model) {
            return false;
        }

        $filePath = Yii::getAlias('@webroot/') . $model->arc_ruta;
        
        // Intentar borrar archivo físico
        if ($model->arc_ruta && file_exists($filePath)) {
            @unlink($filePath);
        }

        // Borrar de BD
        return $model->delete();
    }

    /**
     * Obtiene los datos para la descarga.
     */
    public function getDownloadPath($id)
    {
        $model = Archivo::findOne($id);
        if (!$model) return null;

        $relativePath = $model->arc_ruta ?: null;

        if (!$relativePath && $model->hasAttribute('arc_contenido')) {
            $relativePath = $model->arc_contenido;
        }

        if (!$relativePath) {
            return null;
        }

        $filePath = Yii::getAlias('@webroot/') . ltrim($relativePath, '/\\');
        
        if (is_file($filePath)) {
            return [
                'path' => $filePath,
                'filename' => basename($filePath)
            ];
        }
        return null;
    }
}
