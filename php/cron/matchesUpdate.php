<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Login/php/init/init.php';

ini_set('memory_limit', '512M' );
set_time_limit(60);


$dateTime = new DateTime('today');
$today = $dateTime->format('d%2fm%2fY');
$dateTime->modify('+1 day');
$tomorrow = $dateTime->format('d%2fm%2fY');

$apiurlToday = "http://api.mozzartbet.com/MozzartWS/external.json/odds-offer?languageId=2&sportId=1&date=$today";
$apiurlTomorrow = "http://api.mozzartbet.com/MozzartWS/external.json/odds-offer?languageId=2&sportId=1&date=$tomorrow";

$f = new Fetch();
$f->setUrl($apiurlToday);
$f->fetchGames();
$f->insertGames();

$f->setUrl($apiurlTomorrow);
$f->fetchGames();
$f->insertGames();
