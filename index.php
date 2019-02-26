<?php
$_SERVER['HTTPS']='on';
// change the following paths if necessary
$yii_path = dirname(__FILE__).'/../yii/framework/';
$yii=$yii_path.'yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
