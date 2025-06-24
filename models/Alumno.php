<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alumno".
 *
 * @property int $alu_id
 * @property string $alu_matricula
 * @property string $alu_nombre
 * @property string $alu_paterno
 * @property string $alu_materno
 * @property int|null $alu_generacion_id
 * @property string|null $alu_ingreso
 * @property int|null $alu_servicio_id
 * @property int|null $alu_carrera_id
 *
 * @property Carrera $aluCarrera
 * @property Generacion $aluGeneracion
 * @property Servicio $aluServicio
 */
class Alumno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alu_matricula', 'alu_nombre', 'alu_paterno', 'alu_materno'], 'required'],
            [['alu_generacion_id', 'alu_servicio_id', 'alu_carrera_id'], 'integer'],
            [['alu_ingreso'], 'safe'],
            [['alu_matricula'], 'string', 'max' => 8],
            [['alu_nombre', 'alu_paterno', 'alu_materno'], 'string', 'max' => 50],
            [['alu_carrera_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::class, 'targetAttribute' => ['alu_carrera_id' => 'car_id']],
            [['alu_generacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Generacion::class, 'targetAttribute' => ['alu_generacion_id' => 'gen_id']],
            [['alu_servicio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::class, 'targetAttribute' => ['alu_servicio_id' => 'ser_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'alu_id' => 'Alu ID',
            'alu_matricula' => 'Alu Matricula',
            'alu_nombre' => 'Alu Nombre',
            'alu_paterno' => 'Alu Paterno',
            'alu_materno' => 'Alu Materno',
            'alu_generacion_id' => 'Alu Generacion ID',
            'alu_ingreso' => 'Alu Ingreso',
            'alu_servicio_id' => 'Alu Servicio ID',
            'alu_carrera_id' => 'Alu Carrera ID',
        ];
    }

    /**
     * Gets query for [[AluCarrera]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAluCarrera()
    {
        return $this->hasOne(Carrera::class, ['car_id' => 'alu_carrera_id']);
    }

    /**
     * Gets query for [[AluGeneracion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAluGeneracion()
    {
        return $this->hasOne(Generacion::class, ['gen_id' => 'alu_generacion_id']);
    }

    /**
     * Gets query for [[AluServicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAluServicio()
    {
        return $this->hasOne(Servicio::class, ['ser_id' => 'alu_servicio_id']);
    }
    public function getArchivos()
{
    return $this->hasMany(Archivo::class, ['arc_alumno_id' => 'alu_id']);
}

}
