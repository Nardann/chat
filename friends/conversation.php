<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');
include('../includes/header.php');
include('../includes/navbar.php'); 

$username = $_SESSION['username'];
$friend_id = $_GET['friend_id'];

// Récupérer l'ID de l'utilisateur connecté
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];
$stmt->close();

// Déterminer le nom du fichier JSON pour la conversation
$conversation_file = '../data/messages/friend/' . min($user_id, $friend_id) . '-' . max($user_id, $friend_id) . '.json';

// Vérifier si le fichier de conversation existe, sinon le créer
if (!file_exists($conversation_file)) {
    file_put_contents($conversation_file, json_encode([]));
}

// Charger les messages de la conversation
$messages = json_decode(file_get_contents($conversation_file), true);
?>

<h2>Conversation avec <?php echo htmlspecialchars($_GET['friend_name']); ?></h2>

<div id="messages" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
    <?php
    foreach ($messages as $message) {
        $sender = $message['sender_id'] == $user_id ? 'Vous' : htmlspecialchars($_GET['friend_name']);
        echo "<p><strong>{$sender}:</strong> " . htmlspecialchars($message['content']) . "</p>";
    }
    ?>
</div>

<form action="send_message.php" method="POST">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <textarea name="message" required></textarea>
    <button type="submit">Envoyer</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var messagesDiv = document.getElementById("messages");
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
});
</script>

<?php include('../includes/footer.php'); ?>
