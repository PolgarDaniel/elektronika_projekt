<?php
class Database {
    // Atributy pre prihlasenie do databazy
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "elektronika_projekt"; 

    public $conn;

    // Funkcia connect vytvara pripojenie k databaze
    public function connect() {
        // Vytvorenie noveho spojenia cez mysqli
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        // Overenie, ci sa spojenie podarilo
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error); // Ak nie, ukonci skript s chybovou hlaskou
        }

        return $this->conn; // Vrat aktivne spojenie
    }
}

