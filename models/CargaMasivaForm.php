<?php

namespace app\models;

use yii\base\Model;

class CargaMasivaForm extends Model
{
    public $caja_id;
    public $fondo_id;
    public $clave_programatica_id;
    public $area_generadora_id;
    public $seccion_serie_id;
    public $files;

    public function rules()
    {
        return [
            [['caja_id'], 'required'],
            [['caja_id', 'fondo_id', 'clave_programatica_id', 'area_generadora_id', 'seccion_serie_id'], 'integer'],
            [['files'], 'file', 'extensions' => 'pdf', 'maxFiles' => 20, 'maxSize' => 20 * 1024 * 1024],
        ];
    }

    public function attributeLabels()
    {
        return [
            'caja_id' => 'Caja',
            'fondo_id' => 'Fondo',
            'clave_programatica_id' => 'Clave Programática',
            'area_generadora_id' => 'Área Generadora',
            'seccion_serie_id' => 'Sección Serie',
            'files' => 'PDFs de la caja',
        ];
    }
}
