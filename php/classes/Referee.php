<?php

class Referee {
    private $db,
            $matchId,
            $match,
            $userid,
            $tip,
            $odd = 'roflmfao',
            $status,
            $homeScore,
            $awayScore,
            $homeScoreHalfTime,
            $awayScoreHalfTime;
    
    public function __construct() {
        $db = new Database();
        $this->db = $db->getInstance();
    }
    
    public function setMatchId($matchId) {
        $this->matchId = $matchId;
    }
    
    public function setTip($tip) {
        $this->tip = $tip;
    }
    
    public function checkMatchWinStatus() {
        $this->getMatchDetails();
        return $this->checkWinStatus();
    }
    
    private function getMatchDetails() {
        $matchServices = new MatchServices();
        $this->match = $matchServices->getMatchByID($this->matchId);
        $this->populateMatchVariables();
    }
    
    private function populateMatchVariables() {
        $this->status = $this->match->status;
        $score = $this->match->score;
        $scoreArray = explode(' ', $score);
        $scoreFT = explode(':', $scoreArray[0]);
        $scoreHT = explode(':', $scoreArray[1]);
        $this->homeScore = $scoreFT[0];
        $this->awayScore = $scoreFT[1];
        $this->homeScoreHalfTime = substr($scoreHT[0],1);
        $this->awayScoreHalfTime = substr($scoreHT[1],0,-1);
        
        if ($this->match->minute == 'PP') $this->status = 'PP';
    }
    
    private function checkWinStatus($result = '', $tip = '') {
        
        if ($this->status !== 'FINISHED' && $this->status !== 'PP') return false;
        
        if ($this->status == 'PP') return "WIN";
        
        $a = $b = $c = $d = '';
        if ($result === '' && $tip === '') {
            $a = $this->homeScore;
            $b = $this->awayScore;
            $c = $this->homeScoreHalfTime;
            $d = $this->awayScoreHalfTime;
            $tip = $this->tip;
        } else {
            $scoreArray = explode(' ', $result);
            $scoreFT = explode(':', $scoreArray[0]);
            $scoreHT = explode(':', $scoreArray[1]);
            $a = $scoreFT[0];
            $b = $scoreFT[1];
            $c = substr($scoreHT[0],1);
            $d = substr($scoreHT[1],0,-1);
        }
        if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c) || !is_numeric($d)) return false;
        
        $fr1 = ($a > $b) ? "WIN" : "LOSE";
        $frx = ($a == $b) ? "WIN" : "LOSE";
        $fr2 = ($a < $b) ? "WIN" : "LOSE";
        
        $fh1 = ($c > $d) ? "WIN" : "LOSE";
        $fhx = ($c == $d) ? "WIN" : "LOSE";
        $fh2 = ($c < $d) ? "WIN" : "LOSE";
        
        $sh1 = ($a-$c > $b-$d) ? "WIN" : "LOSE";
        $shx = ($a-$c == $b-$d) ? "WIN" : "LOSE";
        $sh2 = ($a-$c < $b-$d) ? "WIN" : "LOSE";
        
        $go02 = ($a+$b <= 2) ? "WIN" : "LOSE";
        $go23 = ($a+$b == 2 || $a+$b == 3) ? "WIN" : "LOSE";
        $go3p = ($a+$b >= 3) ? "WIN" : "LOSE";
        
        $fh2p = ($c+$d >= 2) ? "WIN" : "LOSE";
        $sh2p = (($a-$c) + ($b-$d) >= 2) ? "WIN" : "LOSE";
        
        $gg = ($a != 0 && $b != 0) ? "WIN" : "LOSE";
        $gg3p = ($a != 0 && $b != 0 && $a+$b >= 3) ? "WIN" : "LOSE";
        
        return $$tip;
    }
}