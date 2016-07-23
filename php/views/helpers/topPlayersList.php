<?php

require_once __DIR__ . '/../../init/init.php';

$userServices = new UserServices();
$topPlayers = $userServices->getTopPlayersLimit(100);
$place = 1;
echo <<<END
<a href="#" id="top-players-list-close">X</a>
<h1 class="main-header">Top Players List</h1>
<div id="top-players-list-container">

<div class="top-players-list-row-header">
        <div class="cell header-matches">Matches</div>
        <div class="cell header-tickets">Tickets</div>
        <div class="cell header-money">$</div>
    </div>

END;
        
foreach ($topPlayers as $topPlayer) {
    if ($topPlayer->tplayed == 0) continue;

    $ticketsPercent = round($topPlayer->twin/$topPlayer->tplayed*100);
    $matchesPercent = round($topPlayer->mwin/$topPlayer->mplayed*100);
echo <<<END

    <div class="top-players-list-row" data-player-id="$topPlayer->id">
        <div class="cell place">$place</div>
        <div class="cell username">$topPlayer->username</div>
        <div class="cell stats">$topPlayer->mplayed</div>
        <div class="cell stats">$topPlayer->mwin</div>
        <div class="cell percent">$matchesPercent%</div>
        <div class="cell stats">$topPlayer->tplayed</div>
        <div class="cell stats">$topPlayer->twin</div>
        <div class="cell percent">$ticketsPercent%</div>
        <div class="cell money">$topPlayer->money €</div>
    </div>
           
   

END;
    $place++;
}

echo <<<END
</div>
END;
