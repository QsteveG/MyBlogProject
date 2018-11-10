<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DBManager{
    
    private $username = "root";
    private $pass = "";
    private $host = "localhost";
    private $DBName = "corsophp";
    private $mysqli = null;
        
        
    function DBConn(){
        $localMysqli = new mysqli($this->host, $this->username, $this->pass, $this->DBName);
        
        $this->mysqli = $localMysqli;
        
//        if (!$this->DBObj) {
        if (mysqli_connect_error()) {
            echo "Error: Unable to connect to MySQL.".PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno().PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error().PHP_EOL;
            return null;
        }else{
            return $localMysqli;
        }
        
    }
    
    function DBDisconn(){
        if ($this->mysqli != null){
            $this->mysqli->close();
//            echo "DB connection closed";
        }else{
            echo "DB Object NULL";
        }
    }
}

?>

