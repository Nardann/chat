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

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id']) && isset($_POST['message'])) {
    // Récupérer les données du formulaire
    $friend_id = $_POST['friend_id'];
    $message = $_POST['message'];

    // Récupérer les noms d'utilisateur
    $user1 = $_SESSION['username'];
    $sql = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $friend_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $friend_username = $result->fetch_assoc()['username'];

    // Construire le nom du fichier de conversation
    $conversation_file = "../data/messages/friend/{$user1}-{$friend_username}.json";

    // Charger les messages existants ou initialiser un tableau vide
    $conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];

    // Ajouter le nouveau message à la conversation
    $conversation[] = [
        'sender_id' => $_SESSION['user_id'],
        'receiver_id' => $friend_id,
        'content' => $message
    ];

    // Enregistrer la conversation mise à jour dans le fichier JSON
    file_put_contents($conversation_file, json_encode($conversation));

    // Rediriger vers la page de conversation
    header("Location: conversation.php?friend_id=$friend_id");
    exit();
} else {
    echo "Error: Invalid request.";
}
?>
