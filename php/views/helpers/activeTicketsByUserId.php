<?php

require_once __DIR__ . '/../../init/init.php';

$userid = isset($_POST['userid']) ? $_POST['userid'] : '';

$user = new User();
if ($user->isLoggedIn()) {

    $ticket = new Ticket();
    $ticket->setUserID($userid);
    $activeTickets = $ticket->getActiveTickets();
echo "<div>";
    foreach($activeTickets as $key=>$value) {
        $ticket = new Ticket($value->ticket);
        $ticket->getMatchDetails();
        $tips = json_decode($value->ticket);
        $totalOdd = 1;
        $timePlayed = date('jS F Y', $value->time);
echo <<<END
    <table>
        <col class='hit' />
        <col class='day' width='30'/>
        <col class='time' />
        <col class='home' />
        <col class='score' />
        <col class='away' />
        <col class='tip' />
        <col class='odd' />
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Time</th>
                <th>Home</th>
                <th>Score</th>
                <th>Away</th>
                <th>Tip</th>
                <th>Odd</th>
            </tr>
        </thead>
        <tbody>
END;
        foreach ($ticket->matchDetails as $number => $details) {
            $time = (empty($details->minute)) ? date('H:i', $details->time / 1000) : $details->minute;
            $day = date('D', $details->time / 1000);
            $home = $details->home;
            $score = $details->score;
            $away = $details->away;
            $matchID = $details->id;
            $tip = $tips->{$matchID};
            $fixedTip = Functions::fixTipName($tip);
            $odds = unserialize($details->odds);
            $odd = $odds[$tip];
            $totalOdd *= $odd;
            $referee = new Referee();
            $referee->setMatchId($matchID);
            $referee->setTip($tip);
            $winStatus = $referee->checkMatchWinStatus();
            $rowColor = '';
            $scoreColor = '';
            if ($winStatus == 'WIN') {
                $rowColor = 'green-row';
                $scoreColor = 'green-bold';
            }
            if ($winStatus == 'LOSE') {
                $rowColor = 'orange-row';
                $scoreColor = 'orange-bold';
            }
            
echo <<<END
            <tr class='$rowColor'>
                <td class='$winStatus'></td>
                <td>$day</td>
                <td>$time</td>
                <td>$home</td>
                <td class='$scoreColor'>$score</td>
                <td>$away</td>
                <td>$fixedTip</td>
                <td>$odd</td>
            </tr>
END;
        }
        $totalOdd = round($totalOdd, 2);
        echo "
           <tfoot>
                <tr>
                    <td colspan='4'>$timePlayed</td>
                    <td colspan='4'>Total odd: <span>$totalOdd</span></td>
                </tr>
            </tfoot>
        </table>";
    }
echo "</div>";
}
