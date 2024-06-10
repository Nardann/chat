<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['friend_id'])) {
    $friend_id = $_GET['friend_id'];
    $username = $_SESSION['username'];

    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $user_id = $user['id'];

        $sql = "INSERT INTO friendships (user_id, friend_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $friend_id);

        if ($stmt->execute()) {
            echo "Friend request sent.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
