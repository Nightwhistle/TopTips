<?php
require_once __DIR__ . '/../init/init.php';


$json = $_POST['data'];
$data = json_decode($json);
$arr = (array)$data;
$oddsSum = 1;
if (count($arr) > 0) {

echo <<<END
<table>
    <col class='time' />
    <col class='home' />
    <col class='away' />
    <col class='tip' />
    <col class='odd' />
    <thead>
        <th>Time</th>
        <th>Home</th>
        <th>Away</th>
        <th>Tip</th>
        <th>Odd</th>
    </thead>
    <tbody>
END;

foreach($data as $k => $v) {
    $match = new Match();
    $match->id = $k;
    $matchServices = new MatchServices($match);
    $game = $matchServices->getMatchByID();
    $time = date('H:i', $game->time / 1000);
    $odds = unserialize($game->odds);
    $oddsSum *= $odds[$v];
    $oddsSumRound = round($oddsSum, 2);
    $tip = Functions::fixTipName($v);
echo <<<END
    <tr>
        <td>$time</td>
        <td>$game->home</td>
        <td>$game->away</td>
        <td>$tip</td>
        <td>$odds[$v]</td>
    </tr>
END;
}

echo <<<END
    </tbody>
<tfoot>
    <tr>
        <td colspan="5">Odds sum: <span>$oddsSumRound</span></td>
    </tr>
</tfoot>
</table>
END;

} else {
    echo "";
}