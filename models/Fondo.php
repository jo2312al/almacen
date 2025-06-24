<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fondo".
 *
 * @property int $fon_id
 * @property string $fon_codigo
 * @property string|null $fon_descripcion
 *
 * @property Archivo[] $archivos
 */
class Fondo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fondo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fon_descripcion'], 'default', 'value' => null],
            [['fon_codigo'], 'required'],
            [['fon_codigo'], 'string', 'max' => 50],
            [['fon_descripcion'], 'string', 'max' => 255],
            [['fon_codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fon_id' => 'Fon ID',
            'fon_codigo' => 'Fon Codigo',
            'fon_descripcion' => 'Fon Descripcion',
        ];
    }

    /**
     * Gets query for [[Archivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(Archivo::class, ['arc_fondo_id' => 'fon_id']);
    }

}
