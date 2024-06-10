<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../includes/header.php');
include('../config/config.php');

// Vérifier si un ami est sélectionné pour afficher la conversation
$friend_id = null;
if (isset($_GET['friend_id'])) {
    $friend_id = $_GET['friend_id'];

    // Afficher la conversation avec l'ami sélectionné
    $sql_friend = "SELECT username FROM users WHERE id = ?";
    $stmt_friend = $conn->prepare($sql_friend);
    $stmt_friend->bind_param("i", $friend_id);
    $stmt_friend->execute();
    $result_friend = $stmt_friend->get_result();
    $friend = $result_friend->fetch_assoc();

    echo "<h2>Conversation avec " . $friend['username'] . "</h2>";

    // Récupérer les messages de la conversation entre l'utilisateur et l'ami
    $sql_messages = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)";
    $stmt_messages = $conn->prepare($sql_messages);
    $stmt_messages->bind_param("iiii", $_SESSION['user_id'], $friend_id, $friend_id, $_SESSION['user_id']);
    $stmt_messages->execute();
    $result_messages = $stmt_messages->get_result();

    echo "<ul>";
    while ($row = $result_messages->fetch_assoc()) {
        echo "<li>" . $row['content'] . "</li>";
    }
    echo "</ul>";

    $stmt_friend->close();
    $stmt_messages->close();
}

// Afficher la liste des amis de l'utilisateur
echo "<h2>Liste des amis</h2>";
echo "<ul>";
$sql = "SELECT friend_id FROM friendships WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $friend_id = $row['friend_id'];
    $sql_friend = "SELECT username FROM users WHERE id = ?";
    $stmt_friend = $conn->prepare($sql_friend);
    $stmt_friend->bind_param("i", $friend_id);
    $stmt_friend->execute();
    $result_friend = $stmt_friend->get_result();
    $friend = $result_friend->fetch_assoc();
    echo "<li><a href='friends.php?friend_id=$friend_id'>" . $friend['username'] . "</a></li>";
}
echo "</ul>";

$stmt->close();
$stmt_friend->close();
$conn->close();

include('../includes/footer.php');
?>
