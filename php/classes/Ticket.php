<?php

/*
 * Ticket data:
 * Public functions: 
 *      setUserID() - Sets ID to the one required
 *      getMatchDetails() - Fetches full details for all matches in ticket
 */

class Ticket {
    private $db,
            $userID,
            $ticketID,
            $activeTickets,
            $ticketJson,
            $time,
            $matchList;
    public  $matchDetails,
            $ticket,
            $playedDetails;
    
    public function __construct($json = "") {
        $db = new Database();
        $this->db = $db->getInstance();
        $this->ticketJson = $json;
        $this->ticket = json_decode($json);
    }
    
    public function setUserID($id) {
        $this->userID = $id;
    }
    
    public function ticketInsert() {
        $this->time = time();
        $statement = $this->db->prepare("INSERT INTO tickets (userid, ticket, time)
                                          VALUES (:userid, :ticket, :time)");

        $statement->bindParam(':userid', $this->userID);
        $statement->bindParam(':ticket', $this->ticketJson);
        $statement->bindParam(':time', $this->time);
        $statement->execute();
        $this->ticketID = $this->db->lastInsertId();
    }
    
    public function insertSingleMatches() {
        
        $matchServices = new MatchServices();
        $ticket = json_decode($this->ticketJson);
        $statement = $this->db->prepare("INSERT INTO playedmatches (userid, ticketid, matchid, home, away, tip, odd)
                                                            VALUES (:userid, :ticketid, :matchid, :home, :away, :tip, :odd)");
        
        foreach ($this->matchDetails as $key => $match) {
            $matchDetail = $matchServices->getMatchByID($match->id);
            $statement->bindParam(':userid', $this->userID);
            $statement->bindParam(':ticketid', $this->ticketID);
            $statement->bindParam(':matchid', $match->id);
            $statement->bindParam(':home', $matchDetail->home);
            $statement->bindParam(':away', $matchDetail->away);
            $statement->bindParam(':tip', $tip = $ticket->{$match->id});
            $odds = unserialize($match->odds);
            $statement->bindParam(':odd', $odds[$tip]);
      
            $statement->execute();
            
            $statement2 = $this->db->prepare("UPDATE playedtips SET {$tip} = {$tip} +1 WHERE matchid = {$match->id}");
            $statement2->execute();
        }
    }
    
    public function getActiveTickets() {
        $statement = $this->db->prepare("SELECT * FROM tickets WHERE userid = :userid AND processed = 0");
        $statement->bindParam(':userid', $this->userID);
        $statement->execute();
        return $this->activeTickets = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getAllActiveTickets() {
        $statement = $this->db->prepare("SELECT * FROM tickets WHERE processed = 0 ORDER BY time DESC");
        $statement->execute();
        return $this->activeTickets = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getFinishedTickets() {
        $statement = $this->db->prepare("SELECT * FROM tickets WHERE userid = :userid AND processed = 1 ORDER BY time DESC LIMIT 2");
        $statement->bindParam(':userid', $this->userID);
        $statement->execute();
        return $this->activeTickets = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getFinishedTicketsWithLimit($offset, $limit) {
        $statement = $this->db->prepare("SELECT * FROM tickets WHERE userid = :userid AND processed = 1 ORDER BY time DESC LIMIT :offset , :limit");
        $statement->bindParam(':userid', $this->userID);
        $statement->bindParam(':offset', $offset);
        $statement->bindParam(':limit', $limit);
        $statement->execute();
        return $this->activeTickets = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getPlayedMatches() {
        $statement = $this->db->prepare("SELECT matchid, tip FROM playedmatches WHERE processed = 0");
        $statement->execute();
        return $this->playedDetails = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getMatchDetails() {
        $this->getMatchList();                  // Populates $this->matchList
        $matchList = implode(',', $this->matchList);
        $statement =$this->db->prepare("SELECT * FROM matches WHERE id IN ($matchList) ORDER BY time ASC");
        $statement->execute();
        return $this->matchDetails = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getFinishedTicketMatchDetails($ticketid) {
        $this->getMatchList();                  // Populates $this->matchList
        $matchList = implode(',', $this->matchList);
        $statement = $this->db->prepare("SELECT * FROM playedmatches WHERE matchid IN ($matchList) AND processed = 1 AND ticketid = $ticketid");
        $statement->execute();
        return $this->matchDetails = $statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    private function getMatchList() {
        $this->matchList = array();
        foreach ($this->ticket as $key => $value) {
            $this->matchList[] = $key;
        }
        return $this->matchList;
    }
    
}