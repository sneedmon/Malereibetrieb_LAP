<?php
require "config.inc.php";
function openConn () {

    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $db = "db_lap_malereibetrieb";
    
    $conn = new MySQLi(DB["host"],DB["user"],DB["pwd"],DB["name"]);
    
    if ($conn->connect_errno > 0) {
        die("Connection failed: " . mysqli_connect_error());
      }
    return $conn;
}