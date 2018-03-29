<?php

$TMPL_DIR = dirname(__FILE__) . '/theme/';
$VERSION_INFO_FILE 	= dirname(__FILE__) . '/version.txt';
$BILLING_SERVER = 'billing.powerpartners.ru';
$CALC_SERVER = 'http://powerpartners.ru/api/calc-delivery.php';
$UPGRADE_INTERVAL = 60*60*24;

// Подключаем локальный конфиг
if (file_exists(dirname(__FILE__) . '/config-local.php'))
    include(dirname(__FILE__) . '/config-local.php');

require_once 'util.php';
require_once 'vlib/vlibTemplate.php';

?>