<?php
session_start();
include('../config/config.php');
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

// Vérifier si le fichier de la conversation existe
$conversation_file = "../data/messages/friend/{$_SESSION['username']}-{$friend_username}.json";
if (file_exists($conversation_file)) {
    // Le fichier de conversation existe, ouvrir et afficher les messages
    $conversation = json_decode(file_get_contents($conversation_file), true);
    echo "<ul>";
    foreach ($conversation as $message) {
        echo "<li>{$message['content']}</li>";
    }
    echo "</ul>";
} else {
    // Le fichier de conversation n'existe pas, créer un nouveau fichier
    file_put_contents($conversation_file, json_encode([]));
}

// Formulaire pour envoyer un message
?>

<form action="./send_message.php" method="post">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <input type="text" name="message" placeholder="Type your message here">
    <button type="submit">Send</button>
</form>

<?php
// Fermeture des requêtes et de la connexion
$stmt_friend->close();
$conn->close();

include('../includes/footer.php');
?>
