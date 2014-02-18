<?php
require(__DIR__ . '/init.php');
$strPathInfo = $_SERVER['PATH_INFO'] ? : '/index';
define('ACTION_KEY', $strPathInfo);
define('HOST', 'http://www.kanxiuchang.com');
require(ACTION_PATH . "$strPathInfo.php");
