<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../config/config.php');
include('../includes/header.php');

// Récupérer les données utilisateur et ami
$user1 = $_SESSION['username'];
$friend_id = $_GET['friend_id'];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $friend_id);
$stmt->execute();
$result = $stmt->get_result();
$friend_username = $result->fetch_assoc()['username'];
$stmt->close();

// Construire le nom du fichier de conversation de manière déterministe
$conversation_file = "../data/messages/friend/" . (strcmp($user1, $friend_username) < 0 ? "{$user1}-{$friend_username}.json" : "{$friend_username}-{$user1}.json");

// Charger les messages existants
$conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];

echo "<h2>Conversation with {$friend_username}</h2>";

foreach ($conversation as $message) {
    $sender = $message['sender'] === $user1 ? 'You' : $friend_username;
    echo "<p><strong>{$sender}:</strong> {$message['content']}</p>";
}
?>

<form action="send_message.php" method="POST">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <textarea name="message" rows="4" cols="50" required></textarea><br>
    <input type="submit" value="Send">
</form>

<?php include('../includes/footer.php'); ?>
