<?php

require_once 'init.php';

if (!isset($_POST)) {
    die('nothing sent!');
}

$ticket = $_POST['data'];
$userID = $_SESSION['id'];
var_dump($_POST['data']);

$ticket = new Ticket($ticket);
$ticket->setUserID($userID);
$ticket->getMatchDetails();
$ticket->ticketInsert();
$ticket->insertSingleMatches();