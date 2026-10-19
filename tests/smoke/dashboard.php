<?php
// Render real controller/views with isolated SQLite fixtures. No external DB writes.
$root = dirname(__DIR__, 2);
$_SERVER['SCRIPT_FILENAME'] = $root . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii/framework/yii.php';
$runtime = sys_get_temp_dir() . '/pkb-dashboard-' . bin2hex(random_bytes(6));
mkdir($runtime, 0700);
$config = require $root . '/protected/config/main.php';
$config['runtimePath'] = $runtime;
$config['components']['db'] = array('class' => 'CDbConnection', 'connectionString' => 'sqlite::memory:');
$config['components']['request'] = array('hostInfo' => 'http://localhost', 'baseUrl' => '', 'scriptUrl' => '/index.php');
unset($config['components']['dbcoba']);
$app = Yii::createWebApplication($config);
// Fail on warnings/deprecations too, including the original number_format(null).
set_error_handler(static function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
require $root . '/protected/controllers/SiteController.php';
class DashboardSmokeController extends SiteController
{
    public $renderedData;
    public function render($view, $data = null, $return = false)
    {
        $this->renderedData = $data;
        return parent::render($view, $data, $return);
    }
}
function dashboardCheck($ok, $message) {
    if (!$ok) throw new RuntimeException($message);
}
try {
    $db = $app->db;
    $db->setActive(true);
    // The dashboard only needs PostgreSQL TO_DATE beyond common SQL aggregates.
    $db->pdoInstance->sqliteCreateFunction('TO_DATE', static function ($value, $format) {
        return DateTimeImmutable::createFromFormat('d-M-y', $value)->format('Y-m-d');
    }, 2);
    foreach (array(
        'CREATE TABLE v_lap_pad (tgl_pad TEXT, total NUMERIC, jum_kend INTEGER, tahun INTEGER, bulan INTEGER)',
        'CREATE TABLE tbl_kendaraan (id_kendaraan INTEGER PRIMARY KEY, umum BOOLEAN)',
        'CREATE TABLE tbl_type (id_kendaraan INTEGER)',
        'CREATE TABLE tbl_daftar (id_daftar INTEGER PRIMARY KEY, id_kendaraan INTEGER, tgl_uji TEXT, id_jns INTEGER, lulus BOOLEAN, datang BOOLEAN)',
    ) as $sql) $db->createCommand($sql)->execute();
    $controller = new DashboardSmokeController('site');
    $app->controller = $controller;
    $controller->init();
    foreach (array('empty', 'null', 'populated') as $scenario) {
        if ($scenario !== 'empty') {
            $db->createCommand()->insert('v_lap_pad', array(
                'tgl_pad' => date('Y-m-d'), 'tahun' => (int) date('Y'), 'bulan' => (int) date('n'),
                'total' => $scenario === 'null' ? null : 1250.5,
                'jum_kend' => $scenario === 'null' ? null : 7,
            ));
        }
        ob_start();
        try {
            $controller->run('home');
            $html = ob_get_contents();
        } finally {
            ob_end_clean();
        }
        $data = $controller->renderedData;
        $vehicles = $scenario === 'populated' ? 7 : 0;
        $money = $scenario === 'populated' ? '1,251' : '0';
        foreach (array('totalRetribusi', 'totalRetribusiBulan', 'totalRetribusiTahun') as $key) {
            dashboardCheck($data[$key] === $money, "$scenario: wrong $key");
        }
        foreach (array('totalKendaraan', 'totalKendaraanPerBulan', 'totalKendaraanPerTahun') as $key) {
            dashboardCheck($data[$key] === $vehicles, "$scenario: wrong $key");
        }
        dashboardCheck(str_contains($html, '<!DOCTYPE html>') && str_contains($html, 'Kendaraan/bulan'), "$scenario: layout not rendered");
        preg_match_all('/data:\s*\[([^\]]*)\]/s', $html, $series);
        dashboardCheck(count($series[1]) === 3, "$scenario: expected three chart years");
        foreach ($series[1] as $i => $values) {
            $points = array_map('trim', explode(',', rtrim(trim($values), ',')));
            dashboardCheck(count($points) === 12, "$scenario: expected twelve chart months");
            foreach ($points as $month => $point) {
                $expected = $i === 2 && $month + 1 === (int) date('n') ? $vehicles : 0;
                dashboardCheck($point === (string) $expected, "$scenario: invalid chart point");
            }
        }
        $db->createCommand('DELETE FROM v_lap_pad')->execute();
    }
    echo "PASS: dashboard controller, layout and chart with empty, NULL and populated aggregates\n";
} finally {
    restore_error_handler();
    $db->setActive(false);
    foreach (glob($runtime . '/*') as $file) if (is_file($file)) unlink($file);
    rmdir($runtime);
}
