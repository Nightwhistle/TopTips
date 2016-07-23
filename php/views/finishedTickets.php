<?php

require_once __DIR__ . '/../init/init.php';

$user = new User();
if ($user->isLoggedIn()) {

    $ticket = new Ticket();
    $ticket->setUserID($_SESSION['id']);
    $activeTickets = $ticket->getFinishedTickets();
echo <<<END
<div id="finished-tickets">
    <h1 class="main-header">Finished Tickets</h1>

END;
    foreach($activeTickets as $key=>$value) {
        $ticket = new Ticket($value->ticket);
        $ticket->getFinishedTicketMatchDetails($value->id);
        $tips = json_decode($value->ticket);
        $totalOdd = 1;
        $timePlayed = date("jS F Y", $value->time);
        $ticketWin = 'Win';
        $ticketid = $value->id;
        
        
echo <<<END
    <table id='ticket-$ticketid'>
        <col class='hit' />
        <col class='home' />
        <col class='score' />
        <col class='away' />
        <col class='tip' />
        <col class='odd' />
        <thead>
            <tr>
                <th></th>
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
            $home = $details->home;
            $score = $details->score;
            $away = $details->away;
            $matchID = $details->id;
            $tip = $details->tip;
            $fixedTip = Functions::fixTipName($tip);
            $odd = number_format($details->odd, 2);
            $totalOdd *= $odd;
            $winStatus = ($details->win == 1) ? 'WIN' : 'LOSE';
            if ($winStatus == 'LOSE') $ticketWin = 'LOSE';
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
            <tr class="$rowColor">
                <td class="$winStatus"></td>
                <td>$home</td>
                <td class="$scoreColor">$score</td>
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
                    <td colspan='2'>$timePlayed</td>
                    <td colspan='4'>Total odd: <span>$totalOdd</span></td>
                </tr>
            </tfoot>
        </table>";
        echo "<a href='#' class='ticket-share' data-ticketid='$ticketid'><i class='fa fa-facebook-official'></i> share</a>";
    }

echo <<<END
<a href='' id='finished-tickets-more-button'>More...</a>
</div>
END;

}
