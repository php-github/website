<?php
require(__DIR__ . '/init.php');
$strPathInfo = $_SERVER['PATH_INFO'] ? : '/index';
define('ACTION_KEY', $strPathInfo);
require(ACTION_PATH . "$strPathInfo.php");
