<?php

use yii\db\Migration;

class m260527_180500_add_archivo_catalog_tables extends Migration
{
    public function safeUp()
    {
        $this->createTableIfMissing('fondo', [
            'fon_id' => $this->primaryKey(),
            'fon_codigo' => $this->string(50)->notNull()->unique(),
            'fon_descripcion' => $this->string(255)->null(),
        ]);

        $this->createTableIfMissing('clave_programatica', [
            'cla_id' => $this->primaryKey(),
            'cla_codigo' => $this->string(50)->notNull()->unique(),
            'cla_descripcion' => $this->string(255)->null(),
        ]);

        $this->createTableIfMissing('area_generadora', [
            'are_id' => $this->primaryKey(),
            'are_codigo' => $this->string(50)->notNull()->unique(),
            'are_descripcion' => $this->string(255)->null(),
        ]);

        $this->createTableIfMissing('seccion_serie', [
            'sec_id' => $this->primaryKey(),
            'sec_codigo' => $this->string(10)->notNull(),
            'sec_descripcion' => $this->string(100)->null(),
        ]);

        $this->addColumnIfMissing('archivo', 'arc_ruta', $this->string(255)->null());
        $this->addColumnIfMissing('archivo', 'arc_fondo_id', $this->integer()->null());
        $this->addColumnIfMissing('archivo', 'arc_clave_programatica_id', $this->integer()->null());
        $this->addColumnIfMissing('archivo', 'arc_area_generadora_id', $this->integer()->null());
        $this->addColumnIfMissing('archivo', 'arc_seccion_serie_id', $this->integer()->null());

        if ($this->db->schema->getTableSchema('archivo')->getColumn('arc_contenido') !== null) {
            $this->alterColumn('archivo', 'arc_contenido', $this->string(250)->null());
        }

        $this->createIndexIfMissing('archivo', 'idx_archivo_fondo', ['arc_fondo_id']);
        $this->createIndexIfMissing('archivo', 'idx_archivo_clave_programatica', ['arc_clave_programatica_id']);
        $this->createIndexIfMissing('archivo', 'idx_archivo_area_generadora', ['arc_area_generadora_id']);
        $this->createIndexIfMissing('archivo', 'idx_archivo_seccion_serie', ['arc_seccion_serie_id']);
    }

    public function safeDown()
    {
        $this->dropIndexIfExists('archivo', 'idx_archivo_seccion_serie');
        $this->dropIndexIfExists('archivo', 'idx_archivo_area_generadora');
        $this->dropIndexIfExists('archivo', 'idx_archivo_clave_programatica');
        $this->dropIndexIfExists('archivo', 'idx_archivo_fondo');

        $this->dropColumnIfExists('archivo', 'arc_seccion_serie_id');
        $this->dropColumnIfExists('archivo', 'arc_area_generadora_id');
        $this->dropColumnIfExists('archivo', 'arc_clave_programatica_id');
        $this->dropColumnIfExists('archivo', 'arc_fondo_id');
        $this->dropColumnIfExists('archivo', 'arc_ruta');

        $this->dropTableIfExists('seccion_serie');
        $this->dropTableIfExists('area_generadora');
        $this->dropTableIfExists('clave_programatica');
        $this->dropTableIfExists('fondo');
    }

    private function createTableIfMissing($tableName, array $columns)
    {
        if ($this->db->schema->getTableSchema($tableName, true) === null) {
            $this->createTable($tableName, $columns);
        }
    }

    private function addColumnIfMissing($tableName, $columnName, $type)
    {
        $schema = $this->db->schema->getTableSchema($tableName, true);
        if ($schema !== null && $schema->getColumn($columnName) === null) {
            $this->addColumn($tableName, $columnName, $type);
        }
    }

    private function dropColumnIfExists($tableName, $columnName)
    {
        $schema = $this->db->schema->getTableSchema($tableName, true);
        if ($schema !== null && $schema->getColumn($columnName) !== null) {
            $this->dropColumn($tableName, $columnName);
        }
    }

    private function createIndexIfMissing($tableName, $indexName, array $columns)
    {
        $schema = $this->db->schema->getTableSchema($tableName, true);
        if ($schema !== null && !$this->indexExists($tableName, $indexName)) {
            $this->createIndex($indexName, $tableName, $columns);
        }
    }

    private function dropIndexIfExists($tableName, $indexName)
    {
        $schema = $this->db->schema->getTableSchema($tableName, true);
        if ($schema !== null && $this->indexExists($tableName, $indexName)) {
            $this->dropIndex($indexName, $tableName);
        }
    }

    private function indexExists($tableName, $indexName)
    {
        $rows = $this->db->createCommand('SHOW INDEX FROM {{%' . $tableName . '}} WHERE Key_name = :name')
            ->bindValue(':name', $indexName)
            ->queryAll();

        return !empty($rows);
    }

    private function dropTableIfExists($tableName)
    {
        if ($this->db->schema->getTableSchema($tableName, true) !== null) {
            $this->dropTable($tableName);
        }
    }
}
