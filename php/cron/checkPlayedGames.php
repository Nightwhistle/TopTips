<?php

/*  Checks for win / lose on playedMatches and tickets table processing games.
 * 
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/Login/php/init/init.php';

$ticket = new Ticket();
$referee = new Referee();
$matchService = new MatchServices();
$userServices = new UserServices();

$playedMatches = $ticket->getPlayedMatches();
$playedTickets = $ticket->getAllActiveTickets();


foreach ($playedMatches as $playedMatch) {
    $matchId = $playedMatch->matchid;
    $tip = $playedMatch->tip;
    $referee->setMatchId($matchId);
    $referee->setTip($tip);
    
    if ($win = $referee->checkMatchWinStatus()) {
        if ($win === 'WIN') $win = 1;
        if ($win === 'LOSE') $win = 0;
        $matchService->win = $win;
        $matchService->tip = $tip;
        $matchService->matchid = $matchId;
        $matchService->updatePlayedMatches();

    }
    
}

$matchService->updateTicketsTable();
$matchService->updateUserMoney();
$userServices->updateUsersRanks();


