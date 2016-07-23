<?php

/* Contains all services considering matches
 * Public functions:
 *      insertMatch() - inserts match in database
 *      getMatchByID() - returns match from database by ID
 *      getReadyMatches() - returns matches that are ready to play
 *      getLiveMatches() - returns matches that are currently live
 */

class MatchServices {
    
    private $db,
            $match;
    public  $matchid,
            $tip,
            $win;

    public function __construct(Match $match = null) {
        $db = new Database();
        $this->db = $db->getInstance();
        $this->match = $match;
    }
    
    public function setWin($win) {
        $this->win = $win;
    }
    
    public function insertMatch() {

        $statement = $this->db->prepare("INSERT INTO matches (id,
                                                     league,
                                                     priority,
                                                     home,
                                                     away,
                                                     status,
                                                     time,
                                                     minute,
                                                     score,
                                                     odds)
                                             VALUES (:id,
                                                     :league,
                                                     :priority,
                                                     :home,
                                                     :away,
                                                     :status,
                                                     :time,
                                                     :minute,
                                                     :score,
                                                     :oddsSerialized)
                             ON DUPLICATE KEY UPDATE id = :id2,
                                                     league = :league2,
                                                     priority = :priority2,
                                                     home = :home2,
                                                     away = :away2,
                                                     status = :status2,
                                                     time = :time2,
                                                     minute = :minute2,
                                                     score = :score2,
                                                     odds = :oddsSerialized2;");
        $statement->bindParam(':id', $this->match->id);
        $statement->bindParam(':league', $this->match->league);
        $statement->bindParam(':priority', $this->match->priority);
        $statement->bindParam(':home', $this->match->home);
        $statement->bindParam(':away', $this->match->away);
        $statement->bindParam(':status', $this->match->status);
        $statement->bindParam(':time', $this->match->time);
        $statement->bindParam(':minute', $this->match->minute);
        $statement->bindParam(':score', $this->match->score);
        $statement->bindParam(':oddsSerialized', $this->match->oddsSerialized);
        
        $statement->bindParam(':id2', $this->match->id);
        $statement->bindParam(':league2', $this->match->league);
        $statement->bindParam(':priority2', $this->match->priority);
        $statement->bindParam(':home2', $this->match->home);
        $statement->bindParam(':away2', $this->match->away);
        $statement->bindParam(':status2', $this->match->status);
        $statement->bindParam(':time2', $this->match->time);
        $statement->bindParam(':minute2', $this->match->minute);
        $statement->bindParam(':score2', $this->match->score);
        $statement->bindParam(':oddsSerialized2', $this->match->oddsSerialized);
        
        $statement->execute();
        
        $statement2 = $this->db->prepare("INSERT IGNORE INTO playedtips (matchid) VALUES (:matchid2)");
        $statement2->bindParam(':matchid2', $this->match->id);
        $statement2->execute();

    }
    
    public function updateLiveGame() {
        $statement = $this->db->prepare("UPDATE matches SET home = :home,
                                                            away = :away,
                                                            minute = :minute,
                                                            score = :score,
                                                            status = :status
                                                      WHERE id = :id");
        $statement->bindParam(':id', $this->match->id);
        $statement->bindParam(':home', $this->match->home);
        $statement->bindParam(':away', $this->match->away);
        $statement->bindParam(':minute', $this->match->minute);
        $statement->bindParam(':score', $this->match->score);
        $statement->bindParam(':status', $this->match->status);
        $statement->execute();
    }
    
    public function removeTips() {
        $statement = $this->db->prepare("DELETE FROM playedtips WHERE matchid = :matchid");
        $statement->bindParam(':matchid', $this->match->id);
        $statement->execute();
    }
    
    public function getMatchByID($id = null) {
        $matchId = null;
        if (!is_null($id)) {
            $matchId = $id;
        } else {
            $matchId = $this->match->id;
        }
        $statement = $this->db->prepare("SELECT * FROM matches WHERE id = :id LIMIT 1");
        $statement->bindParam(':id', $matchId);
        $statement->execute();
        return $this->match = $statement->fetch(PDO::FETCH_OBJ);
    }
    
    public function getReadyMatches() {
        $allowedTime = time() + 60*15; // 15mins before game begins
        $statement = $this->db->prepare("SELECT matches.*, CASE GREATEST(fr1, frx, fr2, fh1, fhx, fh2, sh1, shx, sh2, go02, go23, go3p, fh2p, sh2p, gg, gg3p)
                                                            WHEN fr1 THEN 'fr1'
                                                            WHEN frx THEN 'frx'
                                                            WHEN fr2 THEN 'fr2'
                                                            WHEN fh1 THEN 'fh1'
                                                            WHEN fhx THEN 'fhx'
                                                            WHEN fh2 THEN 'fh2'
                                                            WHEN sh1 THEN 'sh1'
                                                            WHEN shx THEN 'shx'
                                                            WHEN sh2 THEN 'sh2'
                                                            WHEN go02 THEN 'go02'
                                                            WHEN go23 THEN 'go23'
                                                            WHEN go3p THEN 'go3p'
                                                            WHEN fh2p THEN 'fh2p'
                                                            WHEN sh2p THEN 'sh2p'
                                                            WHEN gg THEN 'gg'
                                                            WHEN gg3p THEN 'gg3p'
                                                            ELSE 0
                                                         END AS topTip,
                                                         fr1+frx+fr2+fh1+fhx+fh2+sh1+shx+sh2+go02+go23+go3p+fh2p+sh2p+gg+gg3p as totalPlayed,
                                                         GREATEST(fr1, frx, fr2, fh1, fhx, fh2, sh1, shx, sh2, go02, go23, go3p, fh2p, sh2p, gg, gg3p) / (fr1+frx+fr2+fh1+fhx+fh2+sh1+shx+sh2+go02+go23+go3p+fh2p+sh2p+gg+gg3p) * 100 as topTipPercent
                                               FROM matches JOIN playedtips
                                               ON matches.id = playedtips.matchid
                                               WHERE matches.time > :allowedTime
                                               ORDER BY priority, league, time");
        $statement->bindParam(':allowedTime', $allowedTime);
        $statement->execute();
        
        return $this->match = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getTopTipMatches() {
        $allowedTime = time() + TABLE_TIME_BEFORE_MATCH; // 15mins before game begins
        $statement = $this->db->prepare("SELECT matches.time, matches.home, matches.away, matches.odds, CASE GREATEST(fr1, frx, fr2, fh1, fhx, fh2, sh1, shx, sh2, go02, go23, go3p, fh2p, sh2p, gg, gg3p)
                                                            WHEN fr1 THEN 'fr1'
                                                            WHEN frx THEN 'frx'
                                                            WHEN fr2 THEN 'fr2'
                                                            WHEN fh1 THEN 'fh1'
                                                            WHEN fhx THEN 'fhx'
                                                            WHEN fh2 THEN 'fh2'
                                                            WHEN sh1 THEN 'sh1'
                                                            WHEN shx THEN 'shx'
                                                            WHEN sh2 THEN 'sh2'
                                                            WHEN go02 THEN 'go02'
                                                            WHEN go23 THEN 'go23'
                                                            WHEN go3p THEN 'go3p'
                                                            WHEN fh2p THEN 'fh2p'
                                                            WHEN sh2p THEN 'sh2p'
                                                            WHEN gg THEN 'gg'
                                                            WHEN gg3p THEN 'gg3p'
                                                            ELSE 0
                                                         END AS topTip,
                                                         fr1+frx+fr2+fh1+fhx+fh2+sh1+shx+sh2+go02+go23+go3p+fh2p+sh2p+gg+gg3p as totalPlayed,
                                                         GREATEST(fr1, frx, fr2, fh1, fhx, fh2, sh1, shx, sh2, go02, go23, go3p, fh2p, sh2p, gg, gg3p) / (fr1+frx+fr2+fh1+fhx+fh2+sh1+shx+sh2+go02+go23+go3p+fh2p+sh2p+gg+gg3p) * 100 as topTipPercent
                                               FROM matches JOIN playedtips
                                               ON matches.id = playedtips.matchid
                                               WHERE matches.time > :allowedTime
                                               ORDER BY topTipPercent DESC
                                               LIMIT :limit");
        $statement->bindParam(':allowedTime', $allowedTime);
        $statement->bindValue(':limit', TOP_TIPS_TABLE_LIMIT);
        $statement->execute();
        
        return $this->match = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getLiveMatches() {
        $statement = $this->db->prepare("SELECT * FROM matches WHERE status = :status ORDER BY time DESC");
        $statement->bindValue(':status', 'LIVE');
        $statement->execute();
        
        return $this->match = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function updatePlayedMatches() {
        $this->updatePlayedMatchesTable();
    }
    
    public function updateTicketsTable() {
        $statement = $this->db->prepare("SELECT ticketid, count(*) as played, sum(win) as win 
                                            FROM playedmatches
                                            WHERE ticketid IN (SELECT id FROM tickets WHERE processed = 0)
                                            GROUP BY ticketid
                                            HAVING sum(processed) = count(*)");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_OBJ);
        
        $statement2 = $this->db->prepare("UPDATE tickets SET processed = 1, win = :win WHERE id = :id");
        foreach ($results as $result) {
            $statement2->bindParam(':id', $result->ticketid);
            if ($result->played == $result->win) {
                $statement2->bindValue(':win', 1);
            } else {
                $statement2->bindValue(':win', 0);
            }
            $statement2->execute();
        }
    }
    
    public function updateUserMoney() {
        $userMoneyArray = array();
        $statement = $this->db->prepare("SELECT tickets.userid, ticketid, ROUND(EXP(SUM(LOG(playedmatches.odd))),2) as product, tickets.win
                                         FROM playedmatches JOIN tickets
                                         ON tickets.id = playedmatches.ticketid
                                         WHERE tickets.processed = 1
                                         GROUP BY ticketid");
        $statement->execute();
        while ($result = $statement->fetch(PDO::FETCH_OBJ)) {
            if ($result->win == 1) {
                if (!isset($userMoneyArray[$result->userid])) $userMoneyArray[$result->userid] = 10;
                $userMoneyArray[$result->userid] += $result->product * 10;
            } else {
                if (!isset($userMoneyArray[$result->userid])) $userMoneyArray[$result->userid] = 10;
                $userMoneyArray[$result->userid] -= 10;
            }
        }
        
        $statement2 = $this->db->prepare("UPDATE users SET money = :money WHERE id = :userid");
        
        foreach($userMoneyArray as $k => $v) {
            
            $statement2->bindParam(':money', $v);
            $statement2->bindParam(':userid', $k);
            $statement2->execute();
        }
        
    }
    
    
    
    private function updatePlayedMatchesTable() {
        $statement = $this->db->prepare("UPDATE playedmatches SET processed = 1,
                                                                  win = :status
                                                            WHERE matchid = :matchid AND
                                                                  tip = :tip");
        $statement->bindParam(':status', $this->win);
        $statement->bindParam(':matchid', $this->matchid);
        $statement->bindParam(':tip', $this->tip);
        
        $statement->execute();
    }
    
}