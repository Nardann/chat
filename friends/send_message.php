<?php
session_start();
include('../config/config.php');
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
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

    // Construire les noms de fichiers pour la conversation dans les deux sens
    $conversation_file1 = "../data/messages/friend/{$user1}-{$friend_username}.json";
    $conversation_file2 = "../data/messages/friend/{$friend_username}-{$user1}.json";

    // Choix du fichier de conversation existant ou création d'un nouveau fichier
    if (file_exists($conversation_file1)) {
        $conversation_file = $conversation_file1;
    } elseif (file_exists($conversation_file2)) {
        $conversation_file = $conversation_file2;
    } else {
        $conversation_file = $conversation_file1; // Utilisation par défaut
    }

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
