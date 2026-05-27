<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area_generadora".
 *
 * @property int $are_id
 * @property string $are_codigo
 * @property string|null $are_descripcion
 *
 * @property Archivo[] $archivos
 */
class AreaGeneradora extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_generadora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['are_descripcion'], 'default', 'value' => null],
            [['are_codigo'], 'required'],
            [['are_codigo'], 'string', 'max' => 50],
            [['are_descripcion'], 'string', 'max' => 255],
            [['are_codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'are_id' => 'Are ID',
            'are_codigo' => 'Are Codigo',
            'are_descripcion' => 'Are Descripcion',
        ];
    }

    /**
     * Gets query for [[Archivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(Archivo::class, ['arc_area_generadora_id' => 'are_id']);
    }

}
