<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $usu_id
 * @property string $usu_nombre
 * @property string $usu_paterno
 * @property string $usu_materno
 * @property string $usu_usuario
 * @property string $usu_contrasena
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usu_nombre', 'usu_paterno', 'usu_materno', 'usu_usuario', 'usu_contrasena'], 'required'],
            [['usu_nombre', 'usu_paterno', 'usu_materno'], 'string', 'max' => 50],
            [['usu_usuario'], 'string', 'max' => 20],
            [['usu_contrasena'], 'string', 'max' => 255],
            [['usu_usuario'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usu_id' => 'Usu ID',
            'usu_nombre' => 'Usu Nombre',
            'usu_paterno' => 'Usu Paterno',
            'usu_materno' => 'Usu Materno',
            'usu_usuario' => 'Usu Usuario',
            'usu_contrasena' => 'Usu Contrasena',
        ];
    }
}
