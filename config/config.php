<?php
$servername = "127.0.0.1";
$username = "u477148587_o";
$password = "C'est_le_0";
$dbname = "u477148587_o";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    
?>
