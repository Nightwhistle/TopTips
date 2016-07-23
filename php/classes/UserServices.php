<?php

class UserServices {
    
    private $db;
    
    public function __construct() {
        $db = new Database();
        $this->db = $db->getInstance();
    }
    
    public function getTopPlayers() {
        $statement = $this->db->prepare("SELECT u.id, u.username, u.money,
                                                a.twin, a.tplayed, 
                                                b.mwin, b.mplayed
                                        FROM users u 
                                        LEFT JOIN (SELECT t.userid, sum(win) as twin, sum(processed) as tplayed
                                              FROM tickets t
                                              GROUP BY t.userid) a
                                          ON u.id = a.userid
                                        LEFT JOIN (SELECT p.userid, sum(win) as mwin, sum(processed) as mplayed
                                              FROM playedmatches p
                                              GROUP BY p.userid) b
                                          ON u.id = b.userid
                                          WHERE mplayed >= 0
                                        ORDER BY money DESC
                                        LIMIT 5");
                                        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getTopPlayersLimit($limit) {
        $statement = $this->db->prepare("SELECT u.id, u.username, u.money,
                                                a.twin, a.tplayed, 
                                                b.mwin, b.mplayed
                                        FROM users u 
                                        LEFT JOIN (SELECT t.userid, sum(win) as twin, sum(processed) as tplayed
                                              FROM tickets t
                                              GROUP BY t.userid) a
                                          ON u.id = a.userid
                                        LEFT JOIN (SELECT p.userid, sum(win) as mwin, sum(processed) as mplayed
                                              FROM playedmatches p
                                              GROUP BY p.userid) b
                                          ON u.id = b.userid
                                          WHERE mplayed >= 0
                                        ORDER BY money DESC
                                        LIMIT $limit");
                                        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function updateUsersRanks() {
        
        $rank = 0;
        
        $statement = $this->db->prepare("SELECT id FROM users ORDER BY money DESC");
        $statement->execute();
        
        $statement2 = $this->db->prepare("UPDATE users SET rank = :rank WHERE id = :id");
        
        while ($user = $statement->fetch(PDO::FETCH_OBJ)) {
            $rank++;
            $statement2->bindParam(':rank', $rank);
            $statement2->bindParam(':id', $user->id);
            $statement2->execute();
        }
    }
}