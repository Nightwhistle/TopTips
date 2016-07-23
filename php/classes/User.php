<?php

/* User data:
 * Public functions:
 *      login() - Log user in
 *      register() - Gerister user
 *      isLoggedIn() - True or false
 */

class User {
    private $db,
            $id,
            $username,
            $password,
            $email,
            $session,
            $timezone,
            $money;

    public $rank,
           $userDatabase,
           $rememberMe,
           $winTicketsCount,
           $playedTicketsCount,
           $winMatchesCount,
           $playedMatchesCount,
           $error = array();

    public function __construct() {
        $db = new Database();
        $this->db = $db->getInstance();
    }

    public function login() {
        if ($user = $this->fetchUserLoginDetails()) {
            if (!$this->testPassword($user->password)) {
                $this->error[] = "Wrong username or password!";
                return false;
            }
            $this->processLogin();
        }
    }

    public function register() {
        if (self::isAlreadyRegistered()) {
            return false;
        }
        self::processRegister();
    }
    
    public function isLoggedIn() {
        if ($this->checkRememberMeCookie()) {
            return true;
        }
        if (isset($_SESSION['username'])) {
            return true;
        }
        return false;
    }
    
    public function getUserData() {
        if (!$this->isLoggedIn()) return false;
        $statement = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindParam(':username', $_SESSION['username']);
        $statement->execute();
        $this->userDatabase = $statement->fetchAll(PDO::FETCH_OBJ);
        $this->username = $this->userDatabase[0]->username;
        $this->email = $this->userDatabase[0]->email;
        $this->id = $this->userDatabase[0]->id;
        $this->rank = $this->userDatabase[0]->rank;
        $this->timezone = $this->userDatabase[0]->timezone;
        $this->money = $this->userDatabase[0]->money;
        $this->getPlayedTicketsWinStatus();
        $this->getPlayedMatchesWinStatus();
        return true;
    }
    
    private function isAlreadyRegistered() {
        $statement = $this->db->prepare("SELECT username, email FROM users WHERE username = :username OR email = :email");
        $statement->bindParam(':username', $this->username);
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if ($this->username === $result->username) {
                $this->error[] = "Username already exists!";
            }
            if ($this->email === $result->email) {
                $this->error[] = "Email already exists!";
            }
            return true;
        }
        return false;
    }
    
    private function processRegister() {
        if (!empty($this->error)) return false;
        $timezone = $this->detectUserTimeZone();
        
        try {
            $statement = $this->db->prepare("INSERT INTO users (username, password, email, timezone)
                                                        values (:username, :password, :email, :timezone)");
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(':password', $this->password);
            $statement->bindParam(':email', $this->email);
            $statement->bindParam(':timezone', $this->timezone);
            $statement->execute();
            echo "fine!";
        } catch(PDOException $e) {
            echo "$e";
        }
    }
    
    private function processLogin() {
        if ($this->rememberMe) {
            $this->setRememberMeCookie();
        }
        $_SESSION['username'] = $this->username;
        $_SESSION['id'] = $this->userDatabase->id;
        $_SESSION['timezone'] = $this->userDatabase->timezone;
        unset($_SESSION['errors']);
    }
    
    private function fetchUserLoginDetails() {
        $statement = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $statement->bindParam(':username', $this->username);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return $this->userDatabase = $statement->fetch(PDO::FETCH_OBJ);
        }
        $this->error[] = "Wrong username or password!";
        return false;
    }
    
    private function fetchUserLoginDetailsById() {
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $statement->bindParam(':id', $this->id);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return $this->userDatabase = $statement->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }
    
    private function testPassword($password) {
        return (password_verify($this->password, $password));
    }
    
    private function setRememberMeCookie() {
        $token = $this->username . time();
        $token = hash('md5', $token);

        $statement = $this->db->prepare("UPDATE users SET session = :token WHERE username = :username");
        $statement->bindParam(':token', $token);
        $statement->bindParam(':username', $this->username);
        $statement->execute();
        // Cookie info: user ID - token hash
        $hashedToken = $this->userDatabase->id . '-' . password_hash($token, PASSWORD_DEFAULT);
        setCookie("TopTipsRememberMe", $hashedToken, COOKIE_EXPIRE, '/');
    }
    
    private function checkRememberMeCookie() {
        if (isset($_COOKIE['TopTipsRememberMe'])) {
            $cookie = explode('-', $_COOKIE['TopTipsRememberMe']);
            $this->id = $cookie[0];
            $hashedToken = $cookie[1];

            $this->fetchUserLoginDetailsById();
            if (password_verify($this->userDatabase->session, $hashedToken)) {
                $_SESSION['username'] = $this->userDatabase->username;
                $_SESSION['id'] = $this->id;
                $_SESSION['timezone'] = $this->userDatabase->timezone;
                return true;
            }
        }
        return false;
    }
    
    private function getPlayedTicketsWinStatus() {
        $statement = $this->db->prepare("SELECT sum(win) as win, sum(processed) as played
                                         FROM tickets
                                         WHERE userid = :userid");
        $statement->bindParam(':userid', $this->id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        $this->winTicketsCount = $result->win;
        $this->playedTicketsCount = $result->played;
    }
    
    private function getPlayedMatchesWinStatus() {
        $statement = $this->db->prepare("SELECT sum(win) as win, sum(processed) as played
                                         FROM playedmatches
                                         WHERE userid = :userid");
        $statement->bindParam(':userid', $this->id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        $this->winMatchesCount = $result->win;
        $this->playedMatchesCount = $result->played;
    }
    
    private function detectUserTimeZone() {
        
        $userIp = $_SERVER['REMOTE_ADDR'];

        $fetch = new Fetch();
        $result = $fetch->getUserTimeZone($userIp);
        return $this->timezone = $result['timezone'];
    }
    
    
    // Getters/Setters
    
    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getMoney() {
        return $this->money;
    }
    
    public function setUsername($name) {
        if (empty($name)) {
            $this->error[] = "You must enter username!";
            return false;
        }
        if (strlen($name) > 20) {
            $this->error[] = "Username cant be longer than 20 characters!";
        }
        $this->username = $name;
    }

    public function setPassword($password) {
        if (empty($password)) {
            $this->error[] = "You must enter password!";
            return false;
        }
        $this->password = $password;
    }

    public function setEmail($email) {
        if (empty($email)) {
            $this->error[] = "You must enter email!";
            return false;
        }
        $this->email = $email;
    }
    
    
    
    


}