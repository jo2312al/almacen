<?php

namespace app\models;

use Yii;

class CargaMasiva extends \yii\db\ActiveRecord
{
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PROCESANDO = 'procesando';
    const ESTADO_FINALIZADA = 'finalizada';

    public static function tableName()
    {
        return 'carga_masiva';
    }

    public function rules()
    {
        return [
            [['car_caja_id', 'car_creado_en'], 'required'],
            [['car_caja_id', 'car_fondo_id', 'car_clave_programatica_id', 'car_area_generadora_id', 'car_seccion_serie_id', 'car_total', 'car_exitosos', 'car_pendientes', 'car_errores', 'car_creado_por'], 'integer'],
            [['car_creado_en', 'car_finalizado_en'], 'safe'],
            [['car_estado'], 'string', 'max' => 20],
            [['car_estado'], 'default', 'value' => self::ESTADO_PENDIENTE],
            [['car_total', 'car_exitosos', 'car_pendientes', 'car_errores'], 'default', 'value' => 0],
            [['car_caja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Caja::class, 'targetAttribute' => ['car_caja_id' => 'caj_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'car_id' => 'ID',
            'car_caja_id' => 'Caja',
            'car_fondo_id' => 'Fondo',
            'car_clave_programatica_id' => 'Clave Programática',
            'car_area_generadora_id' => 'Área Generadora',
            'car_seccion_serie_id' => 'Sección Serie',
            'car_estado' => 'Estado',
            'car_total' => 'Total',
            'car_exitosos' => 'Guardados',
            'car_pendientes' => 'Pendientes',
            'car_errores' => 'Errores',
            'car_creado_en' => 'Creado',
            'car_finalizado_en' => 'Finalizado',
        ];
    }

    public function getCaja()
    {
        return $this->hasOne(Caja::class, ['caj_id' => 'car_caja_id']);
    }

    public function getDetalles()
    {
        return $this->hasMany(CargaMasivaDetalle::class, ['det_carga_id' => 'car_id']);
    }
}
