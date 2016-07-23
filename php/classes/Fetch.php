<?php

/* Fetching matches from API
 * Public functions:
 *      setUrl() - Setting URL for fetching
 *      fetchGames() - Saving current object URL into fetch property
 *      insertGames() - Inserting games in database
 */

class Fetch {
    private $db,
            $url,
            $fetch;
    
    public function __construct() {
        $db = new Database();
        $this->db = $db->getInstance();
    }
    
    public function setUrl($url) {
        $this->url = $url;
    }
    
    public function fetchGames() {
        $url = $this->url;
        $json = file_get_contents($url);
        $this->fetch = json_decode($json);
    }
    
    public function getUserTimeZone($ip) {
        $serializedArray = file_get_contents("http://ip-api.com/php/".$ip);
        return unserialize($serializedArray);
    }   
    public function insertGames() {
        $match = new Match();
        foreach($this->fetch as $m) {  // loop through all matches
            $match->id      = $m->matchNumber;
            $match->home    = $m->home;
            $match->away    = $m->visitor;
            $match->time    = $m->time;
            $match->minute  = (isset($m->minute)) ? $m->minute : '';
            $match->status  = $m->matchStatus;
            $match->league  = isset($m->competition->name) ? $m->competition->name : '' ;
            $match->priority = isset($m->competition->priority) ? $m->competition->priority : 999999;
            $match->score   = $m->result;
            $score = explode(' ', $m->result);
            $scoreFullTime = explode(':', $score[0]);
            $scoreHalfTime = explode(':', $score[1]);
            $match->scoreHome = $scoreFullTime[0];
            $match->scoreAway = $scoreFullTime[1];
            $match->scoreHomeHalfTime = substr($scoreHalfTime[0],1);
            $match->scoreAwayHalfTime = substr($scoreHalfTime[1],0,-1);
            
            $odds = $m->odds;
            $match->odds['fr1'] = (isset($odds[0]->subgames[0]->value)) ? $odds[0]->subgames[0]->value : '';
            $match->odds['frx'] = (isset($odds[0]->subgames[1]->value)) ? $odds[0]->subgames[1]->value : '';
            $match->odds['fr2'] = (isset($odds[0]->subgames[2]->value)) ? $odds[0]->subgames[2]->value : '';
            
            $match->odds['fh1'] = (isset($odds[2]->subgames[0]->value)) ? $odds[2]->subgames[0]->value : '';
            $match->odds['fhx'] = (isset($odds[2]->subgames[1]->value)) ? $odds[2]->subgames[1]->value : '';
            $match->odds['fh2'] = (isset($odds[2]->subgames[2]->value)) ? $odds[2]->subgames[2]->value : '';       
            
            $match->odds['sh1'] = (isset($odds[3]->subgames[0]->value)) ? $odds[3]->subgames[0]->value : '';
            $match->odds['shx'] = (isset($odds[3]->subgames[1]->value)) ? $odds[3]->subgames[1]->value : '';
            $match->odds['sh2'] = (isset($odds[3]->subgames[2]->value)) ? $odds[3]->subgames[2]->value : '';
            
            $match->odds['go02'] = (isset($odds[5]->subgames[1]->value)) ? $odds[5]->subgames[1]->value : '';
            $match->odds['go23'] = (isset($odds[5]->subgames[2]->value)) ? $odds[5]->subgames[2]->value : '';
            $match->odds['go3p'] = (isset($odds[5]->subgames[3]->value)) ? $odds[5]->subgames[3]->value : '';
            $match->odds['fh2p'] = (isset($odds[6]->subgames[1]->value)) ? $odds[6]->subgames[1]->value : '';
            $match->odds['sh2p'] = (isset($odds[7]->subgames[1]->value)) ? $odds[7]->subgames[1]->value : '';
            $match->odds['gg'] = (isset($odds[8]->subgames[0]->value)) ? $odds[8]->subgames[0]->value : '';
            $match->odds['gg3p'] = (isset($odds[8]->subgames[2]->value)) ? $odds[8]->subgames[2]->value : '';
            
            $match->oddsSerialized = serialize($match->odds);
            
            
            $matchServices = new MatchServices($match);
            $matchServices->insertMatch();

        }
    }
    
    public function updateLiveGames() {
        
        $statement = $this->db->prepare("UPDATE playedmatches SET score = :score WHERE matchid = :matchid AND processed = 0");
        
        foreach ($this->fetch as $m) {
            var_dump($m);
            $match = new Match();
            $match->id = $m->matchNumber;
            $match->time = $m->time;
            $minute = $m->liveStatus;
            $match->minute = $minute;
            $match->home = $m->home;
            $match->away = $m->visitor;
            
            $resultHT = $m->resultPT;
            $resultFT = $m->resultFT;
            $result = $resultFT .' '. $resultHT;
            $match->score = $result;
            
            $removeTips = false;

            if ($m->postponed) {
                $removeTips = true;
                $match->status = 'POSTPONED';
                $statement2 = $this->db->prepare("UPDATE playedmatches SET odd = :odd WHERE matchid = :matchid AND processed = 0");
                $statement2->bindParam(':odd', 1);
                $statement2->bindParam(':matchid', $m->matchNumber);
            } elseif ($m->live) {
                $match->status = 'LIVE';
                $removeTips = true;
            } else {
                $match->status = 'READY';
            }
            
            if ($minute === 'FT') {
                $removeTips = true;
                $match->status = 'FINISHED';
                $statement->bindParam(':score', $result);
                $statement->bindParam(':matchid', $m->matchNumber);
                $statement->execute();
            }
            
            var_dump($removeTips);
            
            $matchServices = new MatchServices($match);
            $matchServices->updateLiveGame();
            if ($removeTips) $matchServices->removeTips();
        }
    }
}
