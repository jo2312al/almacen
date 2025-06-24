<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property int $car_id
 * @property string $car_nombre
 *
 * @property Alumno[] $alumnos
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_nombre'], 'required'],
            [['car_nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'car_id' => 'Car ID',
            'car_nombre' => 'Car Nombre',
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(Alumno::class, ['alu_carrera_id' => 'car_id']);
    }
}
