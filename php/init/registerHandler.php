<?php

require_once 'init.php';

if (!isset($_POST)) {
    die('nothing sent!');
}

$timezone;

$user = new User();
$user->setUsername($_POST['register-username']);
$user->setEmail($_POST['register-email']);

$password = $_POST['register-password'];
$passwordRepeat = $_POST['register-password-repeat'];

if ($password === $passwordRepeat && !empty($password) && !empty($passwordRepeat) && (strlen($password) >= 6)) {
    $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
} else if (empty ($password) || empty ($passwordRepeat)) {
    $user->error[] = "You must enter password!";
} else if (strlen($password) < 6) {
    $user->error[] = "Password must contain at least 6 characters!";
} else {
    $user->error[] = "Passwords must match!";
}

$user->register();

$_SESSION['errors'] = $user->error;

header('Location: ' . $_SERVER['HTTP_REFERER']);