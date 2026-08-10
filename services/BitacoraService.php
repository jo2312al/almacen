<?php

namespace app\services;

use app\models\BitacoraAccion;
use Yii;

class BitacoraService
{
    public static function registrar($accion, $entidad = null, $entidadId = null, $descripcion = null)
    {
        $identity = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;

        $model = new BitacoraAccion([
            'bit_usuario_id' => Yii::$app->user->isGuest ? null : Yii::$app->user->id,
            'bit_usuario' => $identity && isset($identity->username) ? $identity->username : 'sistema',
            'bit_accion' => $accion,
            'bit_entidad' => $entidad,
            'bit_entidad_id' => $entidadId,
            'bit_descripcion' => $descripcion,
            'bit_ip' => Yii::$app->request->userIP,
            'bit_creado_en' => date('Y-m-d H:i:s'),
        ]);

        return $model->save(false);
    }
}
