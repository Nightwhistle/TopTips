<?php

require_once __DIR__ . '/../init/init.php';

$user = new User();

if ($user->getUserData()) {
    $money = $user->getMoney();
    
    $ticketPercent = ($user->playedTicketsCount != 0) ? round($user->winTicketsCount / $user->playedTicketsCount * 100) : 0;
    $matchPercent = ($user->playedMatchesCount != 0) ? round($user->winMatchesCount / $user->playedMatchesCount * 100) : 0;
echo <<<END
<div id="profile">
    <h1 class="main-header">{$user->getUsername()} <span>Rank: {$user->rank}</span></h1>
    <div class='profile-score'>
        <div><h2>Tickets</h2>$ticketPercent%<h3>$user->winTicketsCount / $user->playedTicketsCount</h3></div>
        <div><h2>Matches</h2>$matchPercent%<h3>$user->winMatchesCount / $user->playedMatchesCount</h3></div>
        <div><h2>Money</h2>$money €<h3></h3></div>
    </div>
</div>
END;
}