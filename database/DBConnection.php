<?php

/*
 * database connection according to the singleton Design Pattern
 * - open the connection only once, use it several times
 */


class DBConnection
{
    private static $connection = null;

    public static function getConnection(){
        // Does connection exist?
        if ( DBConnection::$connection){
            // return open connection
            return DBConnection::$connection;
        }else{
            // create new connection & store the connection (for next time)
            DBConnection::$connection = DBConnection::openConnection();
            // return connection
            return DBConnection::$connection;
        }
    }

    private static function openConnection(){
        // open the connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "5204_oop_magic";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn){
            return $conn;
        }else{
            die("DB Verbindung fehlgeschlagen");
        }
    }
}