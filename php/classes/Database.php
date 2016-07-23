<?php

class Database {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('mysql:host='.DB_HOST.'; dbname='.DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function getInstance() {
        return $this->db;
    }
    
}   