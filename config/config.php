<?php
$servername = "127.0.0.1";
$username = "u477148587_hugo";
$password = "C-est-le-0";
$dbname = "u477148587_hugo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    
?>
