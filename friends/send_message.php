<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../config/config.php');

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id']) && isset($_POST['message'])) {
    // Récupérer les données du formulaire
    $friend_id = $_POST['friend_id'];
    $message = $_POST['message'];

    // Enregistrer le message dans la base de données
    $sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $_SESSION['user_id'], $friend_id, $message);
    $stmt->execute();
    $stmt->close();

    // Rediriger vers la page de conversation
    header("Location: conversation.php?friend_id=$friend_id");
    exit();
} else {
    echo "Error: Invalid request.";
}
?>
