<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Login/php/init/init.php';

$url = "https://api.mozzartbet.com/MozzartWS/external.json/results-current?languageId=2&sportId=1";

$f = new Fetch();
$f->setUrl($url);
$f->fetchGames();

$f->updateLiveGames();
