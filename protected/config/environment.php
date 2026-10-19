<?php
// Portainer injects environment variables; .env is documentation, not auto-loaded.
function pkbEnv($name, $default = null)
{
    $value = getenv($name);
    return $value === false ? $default : $value;
}
function pkbBool($name, $default = false)
{
    return filter_var(pkbEnv($name, $default), FILTER_VALIDATE_BOOLEAN);
}
function pkbDatabase($prefix = 'DB')
{
    return array(
        'class' => 'CDbConnection',
        'connectionString' => 'pgsql:host=' . pkbEnv($prefix . '_HOST', 'postgres')
            . ';port=' . pkbEnv($prefix . '_PORT', '5432')
            . ';dbname=' . pkbEnv($prefix . '_DATABASE', 'db_pkb'),
        'username' => pkbEnv($prefix . '_USERNAME', 'pkb'),
        'password' => pkbEnv($prefix . '_PASSWORD', ''),
        'charset' => 'utf8',
        'emulatePrepare' => true,
    );
}
