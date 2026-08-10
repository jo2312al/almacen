<?php

use yii\db\Migration;

class m260810_120000_create_bitacora_accion extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%bitacora_accion}}', [
            'bit_id' => $this->primaryKey(),
            'bit_usuario_id' => $this->integer()->null(),
            'bit_usuario' => $this->string(100)->null(),
            'bit_accion' => $this->string(50)->notNull(),
            'bit_entidad' => $this->string(80)->null(),
            'bit_entidad_id' => $this->integer()->null(),
            'bit_descripcion' => $this->text()->null(),
            'bit_ip' => $this->string(45)->null(),
            'bit_creado_en' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx_bitacora_accion_creado', '{{%bitacora_accion}}', 'bit_creado_en');
        $this->createIndex('idx_bitacora_accion_accion', '{{%bitacora_accion}}', 'bit_accion');
        $this->createIndex('idx_bitacora_accion_entidad', '{{%bitacora_accion}}', ['bit_entidad', 'bit_entidad_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%bitacora_accion}}');
    }
}
