<?php

// change the following paths if necessary
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/protected/config/environment.php';
$yii=__DIR__.'/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// Production never enables debug, even if APP_DEBUG is accidentally set.
defined('YII_DEBUG') or define('YII_DEBUG', pkbEnv('APP_ENV', 'production') !== 'production' && pkbBool('APP_DEBUG'));
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
