<?php

require_once __DIR__ . '/../init/init.php';

$userServices = new UserServices();
$topPlayers = $userServices->getTopPlayers();

echo <<<END
<div id="top-players">
<h1 class="main-header">Top Players</h1>
<div id="top-players-container">
END;
        
foreach ($topPlayers as $topPlayer) {
    if ($topPlayer->tplayed == 0) continue;
    
    $ticketsPercent = round($topPlayer->twin/$topPlayer->tplayed*100);
    $matchesPercent = round($topPlayer->mwin/$topPlayer->mplayed*100);
      
echo <<<END

    <div class="top-players-player">
        <h2>$topPlayer->username</h2>
        <p>Tickets: <span class="top-players-tickets-played">$topPlayer->tplayed</span>
                    <span class="top-players-tickets-win">$topPlayer->twin</span>
                    <span class="top-players-tickets-percent">$ticketsPercent%</span>
        </p>
        <p>Matches: <span class="top-players-tickets-played">$topPlayer->mplayed</span>
                    <span class="top-players-tickets-win">$topPlayer->mwin</span>
                    <span class="top-players-tickets-percent">$matchesPercent%</span>
        </p>
        <p><span class="top-players-money">$topPlayer->money €</span></p>
    </div>

END;
    
}

echo <<<END
</div>
<a href="#" id="top-players-link" class="cpanel-link">Top 100</a>
</div>

END;
