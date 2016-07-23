<?php

class Login {
    private $db;
    private $user;
    
    public function __construct(User $user) {
        $db = new Database();
        $this->db = $db->getInstance();
        $this->user = $user;
    }
    
    public function process() {
        
    }
}