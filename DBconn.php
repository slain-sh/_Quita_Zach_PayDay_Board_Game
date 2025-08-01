<?php
function DBconnection() {
    $conn = new mysqli("localhost", "root", "toor", "payday_game"); 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>