<?php

require_once __DIR__ . '/../init/init.php';
$matchServices = new MatchServices();
$matches = $matchServices->getTopTipMatches();


echo <<<END
<div id="toptips">
<h1 class="main-header">TopTips</h1>
END;

foreach ($matches as $match) {
    if ($match->topTipPercent < TOP_TIPS_LOWEST_PERCENT || $match->totalPlayed < PLAYED_REQUIRED_FOR_PERCENTAGE) continue;
    $time = date('H:i', $match->time / 1000);
    $day = date('D', $match->time / 1000);
    $topTipPercent = round($match->topTipPercent);
    $topTipPercentTaken = $topTipPercent . '%';
    $topTipPercentRemaining = 100 - $topTipPercent . '%';
    $topTip = Functions::fixTipName($match->topTip);
    $odds = unserialize($match->odds);
    
echo <<<END
    <div class="top-tips-bar" style="background: linear-gradient(90deg, rgb(72,61,139) $topTipPercentTaken, rgb(106,90,205) $topTipPercentRemaining);">
        <div class="top-tips-time">$day $time</div>
        <div class="top-tips-home">$match->home</div> - 
        <div class="top-tips-away">$match->away</div>
        <div class="top-tips-stats">
            <div class="top-tips-tip">$topTip</div>
            <div class="top-tips-odd">{$odds[$match->topTip]}</div>
            <div class="top-tips-percent">$topTipPercentTaken</div>
        </div>
    </div>
END;
        
}

echo <<<END
    
</div>

END;
