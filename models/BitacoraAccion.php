<?php

namespace app\models;

class BitacoraAccion extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bitacora_accion';
    }

    public function rules()
    {
        return [
            [['bit_accion', 'bit_creado_en'], 'required'],
            [['bit_usuario_id', 'bit_entidad_id'], 'integer'],
            [['bit_descripcion'], 'string'],
            [['bit_creado_en'], 'safe'],
            [['bit_usuario'], 'string', 'max' => 100],
            [['bit_accion', 'bit_ip'], 'string', 'max' => 50],
            [['bit_entidad'], 'string', 'max' => 80],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bit_id' => 'ID',
            'bit_usuario' => 'Usuario',
            'bit_accion' => 'Acción',
            'bit_entidad' => 'Entidad',
            'bit_entidad_id' => 'Registro',
            'bit_descripcion' => 'Descripción',
            'bit_ip' => 'IP',
            'bit_creado_en' => 'Fecha',
        ];
    }
}
