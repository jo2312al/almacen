<?php

namespace app\models;

class CargaMasivaDetalle extends \yii\db\ActiveRecord
{
    const ESTADO_GUARDADO = 'guardado';
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_ERROR = 'error';

    public static function tableName()
    {
        return 'carga_masiva_detalle';
    }

    public function rules()
    {
        return [
            [['det_carga_id', 'det_nombre_original', 'det_estado', 'det_creado_en'], 'required'],
            [['det_carga_id', 'det_archivo_id', 'det_alumno_id'], 'integer'],
            [['det_mensaje', 'det_datos_extraidos'], 'string'],
            [['det_creado_en'], 'safe'],
            [['det_nombre_original', 'det_ruta_temporal'], 'string', 'max' => 255],
            [['det_matricula_detectada', 'det_estado'], 'string', 'max' => 20],
            [['det_carga_id'], 'exist', 'skipOnError' => true, 'targetClass' => CargaMasiva::class, 'targetAttribute' => ['det_carga_id' => 'car_id']],
            [['det_archivo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archivo::class, 'targetAttribute' => ['det_archivo_id' => 'arc_id']],
            [['det_alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::class, 'targetAttribute' => ['det_alumno_id' => 'alu_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'det_id' => 'ID',
            'det_nombre_original' => 'Archivo',
            'det_ruta_temporal' => 'Ruta temporal',
            'det_matricula_detectada' => 'Matrícula',
            'det_datos_extraidos' => 'Datos extraídos',
            'det_estado' => 'Estado',
            'det_mensaje' => 'Mensaje',
            'det_creado_en' => 'Procesado',
        ];
    }

    public function getCarga()
    {
        return $this->hasOne(CargaMasiva::class, ['car_id' => 'det_carga_id']);
    }

    public function getArchivo()
    {
        return $this->hasOne(Archivo::class, ['arc_id' => 'det_archivo_id']);
    }

    public function getAlumno()
    {
        return $this->hasOne(Alumno::class, ['alu_id' => 'det_alumno_id']);
    }
}
