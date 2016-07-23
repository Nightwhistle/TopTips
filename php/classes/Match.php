<?php

/* Details about single match */

class Match {
    public  $id,
            $league,
            $priority,
            $home,
            $away,
            $status,
            $time,
            $minute,
            $topTip,
            $topTipPercent,
            $score,
            $scoreHome,
            $scoreHomeHalfTime,
            $scoreAway,
            $scoreAwayHalfTime,
            $odds = array(),
            $oddsSerialized,
            $plays = array();
}