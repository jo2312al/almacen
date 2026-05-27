<?php

$config = [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=localhost;port=3306;dbname=servicio',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8',

    // Opciones para cache de esquema en produccion.
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

$localConfig = __DIR__ . '/db.local.php';
if (is_file($localConfig)) {
    $config = array_merge($config, require $localConfig);
}

return $config;
