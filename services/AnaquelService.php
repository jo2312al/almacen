<?php

namespace app\services;

use app\models\Anaquel;

/**
 * Servicio encargado de la lógica de negocio de los Anaqueles.
 */
class AnaquelService
{
    /**
     * Genera un nuevo anaquel calculando automáticamente su nombre consecutivo.
     * @return array Resultado de la operación (success, message, data/errors)
     */
    public function createNextAnaquel()
    {
        $model = new Anaquel();
        
        // 1. Calcular el siguiente nombre (AA0001, AA0002...)
        $model->ana_nombre = $this->generateNextName();

        // 2. Intentar guardar
        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'El anaquel se ha creado exitosamente.',
                'data' => [
                    'ana_id' => $model->ana_id,
                    'ana_nombre' => $model->ana_nombre,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ocurrió un error al intentar guardar el anaquel.',
                'errors' => $model->getErrors(),
            ];
        }
    }

    /**
     * Lógica privada para calcular el siguiente folio.
     */
    private function generateNextName()
    {
        // Buscar el último nombre de anaquel
        $lastAnaquel = Anaquel::find()
            ->select('ana_nombre')
            ->orderBy(['ana_nombre' => SORT_DESC])
            ->limit(1)
            ->one();
        
        if ($lastAnaquel) {
            // Extraer el número del último nombre (ej: AA0005 -> 5)
            preg_match('/AA(\d+)/', $lastAnaquel->ana_nombre, $matches);
            
            if (isset($matches[1])) {
                // Sumar 1 y rellenar con ceros (ej: 6 -> 0006)
                $nextNumber = str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT);
                return 'AA' . $nextNumber;
            }
        }
        
        // Si no existe ninguno o el formato no coincide, iniciar en 0001
        return 'AA0001';
    }
}