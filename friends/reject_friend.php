<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['friend_id'])) {
    $friendship_id = $_GET['friend_id'];
    $username = $_SESSION['username'];

    $sql = "DELETE FROM friendships WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $friendship_id);

    if ($stmt->execute()) {
        echo "Friend request rejected.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
