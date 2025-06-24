<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generacion".
 *
 * @property int $gen_id
 * @property string $gen_nombre
 *
 * @property Alumno[] $alumnos
 */
class Generacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gen_nombre'], 'required'],
            [['gen_nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gen_id' => 'Gen ID',
            'gen_nombre' => 'Gen Nombre',
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(Alumno::class, ['alu_generacion_id' => 'gen_id']);
    }
}
