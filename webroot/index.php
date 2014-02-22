<?php
require(__DIR__ . '/init.php');
$strPathInfo = $_SERVER['PATH_INFO'] ? : '/index';
define('ACTION_KEY', $strPathInfo);
define('HOST', 'http://www.kanxiuchang.com');
if (is_readable(ACTION_PATH . "$strPathInfo.php")) {
    require(ACTION_PATH . "$strPathInfo.php");
} else {
    header("HTTP/1.1 404 Not Found");
}
