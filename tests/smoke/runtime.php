<?php
// No production services or business mutations. Run with the deployment PHP binary.
$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii/framework/yii.php';
function check($ok, $message) {
    if (!$ok) { throw new RuntimeException($message); }
}
foreach (array('pdo_pgsql', 'curl', 'gd', 'mbstring', 'zip', 'calendar', 'dom') as $ext) {
    check(extension_loaded($ext), 'Missing extension: ' . $ext);
}
$checked = 0;
foreach (array('protected', 'themes') as $directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/' . $directory));
    foreach ($iterator as $file) {
        $path = $file->getPathname();
        if ($file->getExtension() !== 'php' || preg_match('~/(runtime|vendors/mpdf|extensions/PHPExcel)/~', $path) || basename($path) === 'PHPExcel.php') { continue; }
        try {
            token_get_all(file_get_contents($path), TOKEN_PARSE);
        } catch (ParseError $error) {
            throw new RuntimeException($path . ': ' . $error->getMessage(), 0, $error);
        }
        $checked++;
    }
}
$runtime = sys_get_temp_dir() . '/pkb-smoke-' . bin2hex(random_bytes(6));
mkdir($runtime, 0700);
try {
    $config = require $root . '/protected/config/main.php';
    $config['runtimePath'] = $runtime;
    $app = Yii::createWebApplication($config);
    check(Yii::getVersion() === '1.1.32', 'Unexpected Yii version');
    check(!$app->hasModule('gii'), 'Gii must not be enabled');
    check($app->getComponent('db', false) === null, 'Bootstrap should not connect to DB');
    foreach (array('Xls', 'Xlsx') as $type) {
        $sheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->getActiveSheet()->setCellValue('A1', 'Uji KIR')->setCellValue('B1', 42);
        $sheet->getActiveSheet()->getStyle('A1')->applyFromArray(array('font' => array('bold' => true)));
        $path = $runtime . '/report.' . strtolower($type);
        PhpOffice\PhpSpreadsheet\IOFactory::createWriter($sheet, $type)->save($path);
        $loaded = PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        check($loaded->getActiveSheet()->getCell([1, 1])->getValue() === 'Uji KIR', 'First column changed');
        check($loaded->getActiveSheet()->getCell('B1')->getValue() == 42, 'Excel value changed');
        $loaded->disconnectWorksheets();
        $sheet->disconnectWorksheets();
    }
    $pdf = new Mpdf\Mpdf(array('tempDir' => $runtime . '/mpdf'));
    $pdf->WriteHTML('<h1>Uji KIR</h1>');
    check(str_starts_with($pdf->Output('', 'S'), '%PDF-'), 'PDF rendering failed');
    echo "PASS: $checked PHP files, Yii bootstrap, XLS/XLSX roundtrip, PDF rendering\n";
} finally {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($runtime, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) { $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname()); }
    rmdir($runtime);
}
