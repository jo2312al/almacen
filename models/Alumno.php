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
 * @property Archivo[] $archivos
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
            // Regla para evitar matrículas duplicadas
            ['alu_matricula', 'unique', 'message' => 'Esta matrícula ya ha sido registrada en el sistema.'],
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
        // Traducción de etiquetas para una mejor experiencia de usuario
        return [
            'alu_id' => 'ID Alumno',
            'alu_matricula' => 'Matrícula',
            'alu_nombre' => 'Nombre(s)',
            'alu_paterno' => 'Apellido Paterno',
            'alu_materno' => 'Apellido Materno',
            'alu_generacion_id' => 'Generación',
            'alu_ingreso' => 'Año de Ingreso',
            'alu_servicio_id' => 'Servicio',
            'alu_carrera_id' => 'Carrera',
        ];
    }

    // --- RELACIONES (sin cambios) ---
    public function getAluCarrera(){ return $this->hasOne(Carrera::class, ['car_id' => 'alu_carrera_id']); }
    public function getAluGeneracion(){ return $this->hasOne(Generacion::class, ['gen_id' => 'alu_generacion_id']); }
    public function getAluServicio(){ return $this->hasOne(Servicio::class, ['ser_id' => 'alu_servicio_id']); }
    public function getArchivos(){ return $this->hasMany(Archivo::class, ['arc_alumno_id' => 'alu_id']); }

    /**
     * ===================================================================
     * FUNCIÓN FALTANTE AÑADIDA
     * Devuelve el nombre completo del alumno en el orden correcto.
     * ===================================================================
     */
    public function getNombreCompleto()
    {
        return trim($this->alu_nombre . ' ' . $this->alu_paterno . ' ' . $this->alu_materno);
    }
}
