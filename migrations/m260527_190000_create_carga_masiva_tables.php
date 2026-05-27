<?php

use yii\db\Migration;

class m260527_190000_create_carga_masiva_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%carga_masiva}}', [
            'car_id' => $this->primaryKey(),
            'car_caja_id' => $this->integer()->notNull(),
            'car_estado' => $this->string(20)->notNull()->defaultValue('pendiente'),
            'car_total' => $this->integer()->notNull()->defaultValue(0),
            'car_exitosos' => $this->integer()->notNull()->defaultValue(0),
            'car_pendientes' => $this->integer()->notNull()->defaultValue(0),
            'car_errores' => $this->integer()->notNull()->defaultValue(0),
            'car_creado_por' => $this->integer()->null(),
            'car_creado_en' => $this->dateTime()->notNull(),
            'car_finalizado_en' => $this->dateTime()->null(),
        ]);

        $this->createIndex('idx_carga_masiva_caja', '{{%carga_masiva}}', 'car_caja_id');

        $this->createTable('{{%carga_masiva_detalle}}', [
            'det_id' => $this->primaryKey(),
            'det_carga_id' => $this->integer()->notNull(),
            'det_archivo_id' => $this->integer()->null(),
            'det_alumno_id' => $this->integer()->null(),
            'det_nombre_original' => $this->string(255)->notNull(),
            'det_matricula_detectada' => $this->string(20)->null(),
            'det_estado' => $this->string(20)->notNull(),
            'det_mensaje' => $this->text()->null(),
            'det_creado_en' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx_carga_masiva_detalle_carga', '{{%carga_masiva_detalle}}', 'det_carga_id');
        $this->createIndex('idx_carga_masiva_detalle_estado', '{{%carga_masiva_detalle}}', 'det_estado');
    }

    public function safeDown()
    {
        $this->dropTable('{{%carga_masiva_detalle}}');
        $this->dropTable('{{%carga_masiva}}');
    }
}
