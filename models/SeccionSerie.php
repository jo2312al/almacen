<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seccion_serie".
 *
 * @property int $sec_id
 * @property string $sec_codigo
 * @property string|null $sec_descripcion
 *
 * @property Archivo[] $archivos
 */
class SeccionSerie extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seccion_serie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sec_descripcion'], 'default', 'value' => null],
            [['sec_codigo'], 'required'],
            [['sec_codigo'], 'string', 'max' => 10],
            [['sec_descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sec_id' => 'Sec ID',
            'sec_codigo' => 'Sec Codigo',
            'sec_descripcion' => 'Sec Descripcion',
        ];
    }

    /**
     * Gets query for [[Archivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(Archivo::class, ['arc_seccion_serie_id' => 'sec_id']);
    }

}
