<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servicio".
 *
 * @property int $ser_id
 * @property string $ser_anio
 * @property int|null $ser_periodo_id
 *
 * @property Alumno[] $alumnos
 * @property Periodo $serPeriodo
 */
class Servicio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ser_anio'], 'required'],
            [['ser_anio'], 'safe'],
            [['ser_periodo_id'], 'integer'],
            [['ser_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Periodo::class, 'targetAttribute' => ['ser_periodo_id' => 'per_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ser_id' => 'Ser ID',
            'ser_anio' => 'Ser Anio',
            'ser_periodo_id' => 'Ser Periodo ID',
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(Alumno::class, ['alu_servicio_id' => 'ser_id']);
    }

    /**
     * Gets query for [[SerPeriodo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerPeriodo()
    {
        return $this->hasOne(Periodo::class, ['per_id' => 'ser_periodo_id']);
    }
}
