<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingreso".
 *
 * @property int $ing_id
 * @property string $ing_anio
 *
 * @property Alumno[] $alumnos
 */
class Ingreso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingreso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ing_anio'], 'required'],
            [['ing_anio'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ing_id' => 'Ing ID',
            'ing_anio' => 'Ing Anio',
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(Alumno::class, ['alu_ingreso_id' => 'ing_id']);
    }
}
