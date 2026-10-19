<?php
// Optional, inactive connection. No credentials are bundled in source.
require_once __DIR__ . '/environment.php';
return array(
    'connectionString' => pkbEnv('MYSQL_DSN', ''),
    'username' => pkbEnv('MYSQL_USERNAME', ''),
    'password' => pkbEnv('MYSQL_PASSWORD', ''),
    'charset' => 'utf8',
);
