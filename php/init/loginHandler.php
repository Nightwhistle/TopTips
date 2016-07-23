<?php

require_once 'init.php';


if (!isset($_POST)) {
    die('Error: nothing sent!');
}

$user = new User();
$user->setUsername($_POST['login-username']);
$user->setPassword($_POST['login-password']);
if (isset($_POST['login-remember'])) {
    $user->rememberMe = true;
}
$user->login();
$_SESSION['errors'] = $user->error;

header('Location: ' . $_SERVER['HTTP_REFERER']);