<?php
session_start();

$servername = "localhost";
$username = "blueducksuser"; 
$password = "HL95#3#ZxTZ"; 
$dbname = "blueducksdb"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>