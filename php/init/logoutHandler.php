<?php

session_start();
$_SESSION = array();
session_destroy();
setcookie('TopTipsRememberMe', '', time()-1, '/');

header('Location: ' . $_SERVER['HTTP_REFERER']);
