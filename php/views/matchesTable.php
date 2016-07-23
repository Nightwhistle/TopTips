<?php
require_once __DIR__ . '/../init/init.php';
$matchServices = new MatchServices();
$matches = $matchServices->getReadyMatches();  // READY matches array from `matches` table

echo <<<END
<div id="matches">
<h1 class="main-header">Available matches <input type='text' id='matches-table-search' placeholder='search'></h1>

END;
$league = '';
foreach ($matches as $match) {

$odds = unserialize($match->odds);
$time = date('H:i', $match->time / 1000);
$day = date('D', $match->time / 1000);
$topTip = '';
$topTipPercent = '';
$rowColor = '';
if ($match->totalPlayed >= PLAYED_REQUIRED_FOR_PERCENTAGE) {
    $topTip = Functions::fixTipName($match->topTip);
    $topTipPercent = round($match->topTipPercent).'%';
    
    switch ($match->topTipPercent) {
        case ($match->topTipPercent >= 90): $rowColor = "green-row"; break;
        case ($match->topTipPercent >= 70 && $match->topTipPercent < 90): $rowColor = "yellow-row"; break;
        case ($match->topTipPercent >= 50 && $match->topTipPercent < 70): $rowColor = "orange-row"; break;
    }
}

if ($league !== $match->league) {
        $league = $match->league;
include __DIR__ . '/helpers/matchesTableHeader.php';
}

echo <<<END
        <tr data-matchid="$match->id" class="$rowColor">
            <td>$day</td>
            <td>$time</td>
            <td>$match->home</td>
            <td>$match->away</td>
            <td>$topTip</td>
            <td>$topTipPercent</td>
            <td data-odd="fr1">{$odds['fr1']}</td>
            <td data-odd="frx">{$odds['frx']}</td>
            <td data-odd="fr2">{$odds['fr2']}</td>
            <td data-odd="fh1">{$odds['fh1']}</td>
            <td data-odd="fhx">{$odds['fhx']}</td>
            <td data-odd="fh2">{$odds['fh2']}</td>
            <td data-odd="sh1">{$odds['sh1']}</td>
            <td data-odd="shx">{$odds['shx']}</td>
            <td data-odd="sh2">{$odds['sh2']}</td>
            <td data-odd="go02">{$odds['go02']}</td>
            <td data-odd="go23">{$odds['go23']}</td>
            <td data-odd="go3p">{$odds['go3p']}</td>
            <td data-odd="fh2p">{$odds['fh2p']}</td>
            <td data-odd="sh2p">{$odds['sh2p']}</td>
            <td data-odd="gg">{$odds['gg']}</td>
            <td data-odd="gg3p">{$odds['gg3p']}</td>
        </tr>

END;
}

echo <<<END
    </tbody>
</table>
</div>
END;
