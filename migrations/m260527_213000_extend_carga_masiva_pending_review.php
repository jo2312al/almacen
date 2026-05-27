<?php

use yii\db\Migration;

class m260527_213000_extend_carga_masiva_pending_review extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%carga_masiva}}', 'car_fondo_id', $this->integer()->null());
        $this->addColumn('{{%carga_masiva}}', 'car_clave_programatica_id', $this->integer()->null());
        $this->addColumn('{{%carga_masiva}}', 'car_area_generadora_id', $this->integer()->null());
        $this->addColumn('{{%carga_masiva}}', 'car_seccion_serie_id', $this->integer()->null());

        $this->addColumn('{{%carga_masiva_detalle}}', 'det_ruta_temporal', $this->string(255)->null());
        $this->addColumn('{{%carga_masiva_detalle}}', 'det_datos_extraidos', $this->text()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%carga_masiva_detalle}}', 'det_datos_extraidos');
        $this->dropColumn('{{%carga_masiva_detalle}}', 'det_ruta_temporal');

        $this->dropColumn('{{%carga_masiva}}', 'car_seccion_serie_id');
        $this->dropColumn('{{%carga_masiva}}', 'car_area_generadora_id');
        $this->dropColumn('{{%carga_masiva}}', 'car_clave_programatica_id');
        $this->dropColumn('{{%carga_masiva}}', 'car_fondo_id');
    }
}
