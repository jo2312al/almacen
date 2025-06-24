<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clave_programatica".
 *
 * @property int $cla_id
 * @property string $cla_codigo
 * @property string|null $cla_descripcion
 *
 * @property Archivo[] $archivos
 */
class ClaveProgramatica extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clave_programatica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cla_descripcion'], 'default', 'value' => null],
            [['cla_codigo'], 'required'],
            [['cla_codigo'], 'string', 'max' => 50],
            [['cla_descripcion'], 'string', 'max' => 255],
            [['cla_codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cla_id' => 'Cla ID',
            'cla_codigo' => 'Cla Codigo',
            'cla_descripcion' => 'Cla Descripcion',
        ];
    }

    /**
     * Gets query for [[Archivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(Archivo::class, ['arc_clave_programatica_id' => 'cla_id']);
    }

}
