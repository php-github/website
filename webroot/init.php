<?php
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Chongqing");
function __autoload($strClassName)
{
    $strClassName = str_replace('\\', '/', $strClassName);
    require("$strClassName.php");
}
define('ROOT_PATH', realpath(__DIR__ . '/../'));
define('ACTION_PATH', ROOT_PATH . '/action');
define('LIB_PATH', ROOT_PATH . '/lib');
define('SERVICE_PATH', ROOT_PATH . '/service');
define('CONF_PATH', ROOT_PATH . '/conf');
define('TEMPLATE_PATH', ROOT_PATH . '/template');
$arrIncludePath = array();
$arrIncludePath[] = LIB_PATH;
$arrIncludePath[] = ROOT_PATH;
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $arrIncludePath));
Tofu\Log::init();
