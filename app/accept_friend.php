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

        $sql = "UPDATE friendships SET status='accepted' WHERE user_id=? AND friend_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $friend_id, $user_id);

        if ($stmt->execute()) {
            echo "Friend request accepted.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
