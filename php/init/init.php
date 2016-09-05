<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/TopTips/config/config.php';

spl_autoload_register(function($class) {
    include $_SERVER['DOCUMENT_ROOT'] . '/TopTips/php/classes/' . $class . '.php';
});

$timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : 'GMT';
date_default_timezone_set($timezone);
