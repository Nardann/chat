<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../config/config.php');

$user1 = $_SESSION['username'];
$friend_id = $_GET['friend_id'];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $friend_id);
$stmt->execute();
$result = $stmt->get_result();
$friend_username = $result->fetch_assoc()['username'];
$stmt->close();

$conversation_file = "../data/messages/friend/" . (strcmp($user1, $friend_username) < 0 ? "{$user1}-{$friend_username}.json" : "{$friend_username}-{$user1}.json");

$conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];

header('Content-Type: application/json');
echo json_encode($conversation);
?>
