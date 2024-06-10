<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');



if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['friend_id'])) {
    $friend_id = $_GET['friend_id'];
    $username = $_SESSION['username'];

    // Récupérer l'ID de l'utilisateur connecté
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $user_id = $user['id'];

        // Vérifier si l'utilisateur essaie de s'ajouter lui-même
        if ($user_id == $friend_id) {
            echo "You cannot add yourself as a friend.";
            $stmt->close();
            $conn->close();
            exit();
        }

        // Vérifier si une demande d'ami existe déjà
        $sql = "SELECT * FROM friendships WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "A friend request already exists between you and this user.";
            $stmt->close();
            $conn->close();
            exit();
        }

        // Insérer la demande d'ami
        $sql = "INSERT INTO friendships (user_id, friend_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $friend_id);

        if ($stmt->execute()) {
            echo "Friend request sent.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
