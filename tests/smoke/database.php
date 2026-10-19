<?php
// Read-only check against a configured STAGING database. Never creates schema.
if (getenv('APP_ENV') !== 'staging') {
    fwrite(STDERR, "Run this check with APP_ENV=staging and a separate staging DB.\n");
    exit(1);
}
$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii/framework/yii.php';
$config = require $root . '/protected/config/main.php';
$app = Yii::createWebApplication($config);
$db = $app->db;
$db->createCommand('SELECT 1')->queryScalar();
foreach (array('tbl_user', 'tbl_kendaraan', 'tbl_retribusi', 'tbl_hasil_uji', 'authitem', 'authassignment', 'authitemchild', 'rights', 'YiiSession') as $table) {
    if ($db->schema->getTable($table) === null) {
        throw new RuntimeException('Missing required table: ' . $table);
    }
}
echo "PASS: DB connection and core tables (not a full business-schema or UAT check)\n";
