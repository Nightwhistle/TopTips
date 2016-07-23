<?php

require_once __DIR__ . '/../init/init.php';

$matchServices = new MatchServices();
$matches = $matchServices->getLiveMatches();  // READY matches array from `matches` table

if (!empty($matches)) {

echo <<<END
<div id="live-matches">
<h1 class="main-header">Live Matches</h1>
<table id="live-matches-table">
    <col class="time" />
    <col class="home" />
    <col class="score" />
    <col class="away" />
    <thead>
        <tr>
            <th>Time</th>
            <th>Home</th>
            <th>Score</th>
            <th>Away</th>
        </tr>
    </thead>
    <tbody>

END;

foreach ($matches as $match) {
    $odds = unserialize($match->odds);
    $time = date('H:i', $match->time / 1000);

    echo <<<END
        <tr>
            <td>$match->minute</td>
            <td>$match->home</td>
            <td>$match->score</td>
            <td>$match->away</td>
        </tr>

END;
}

echo <<<END
    </tbody>
</table>
</div>
END;
}
