<?php
/**
 * This file defines the things it needs for the DB connection and is used in the database.php file.
 */

class Config {
    private $servername;
    private $username;
    private $password;
    private $dbname;

    protected function connect() {
        $this->servername = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "5204_oop_magic";

        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($conn){
            return $conn;
        }else{
            die("DB Verbindung fehlgeschlagen");
        }

        //return new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

}