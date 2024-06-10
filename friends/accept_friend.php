<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['friend_id'])) {
    $friendship_id = $_GET['friend_id'];
    $username = $_SESSION['username'];

    $sql = "UPDATE friendships SET status='accepted' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $friendship_id);

    if ($stmt->execute()) {
        echo "Friend request accepted.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
