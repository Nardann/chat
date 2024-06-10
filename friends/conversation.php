<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../config/config.php');
include('../includes/header.php');

// Vérifier si l'ID de l'ami est spécifié dans l'URL
if (!isset($_GET['friend_id'])) {
    echo "ID de l'ami non spécifié.";
    include('../includes/footer.php');
    exit();
}

$friend_id = $_GET['friend_id'];

// Récupérer le nom de l'ami à partir de l'ID
$sql_friend = "SELECT username FROM users WHERE id = ?";
$stmt_friend = $conn->prepare($sql_friend);
$stmt_friend->bind_param("i", $friend_id);
$stmt_friend->execute();
$result_friend = $stmt_friend->get_result();
$friend = $result_friend->fetch_assoc();
$friend_username = $friend['username'];

echo "<h2>Conversation avec $friend_username</h2>";

// Récupérer les messages de la conversation entre l'utilisateur et l'ami
$sql_messages = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp";
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
$conn->close();

// Formulaire pour envoyer un message
echo "<form action='send_message.php' method='post'>";
echo "<input type='hidden' name='friend_id' value='$friend_id'>";
echo "<textarea name='message' placeholder='Type your message here'></textarea>";
echo "<button type='submit'>Send</button>";
echo "</form>";

include('../includes/footer.php');
?>
