<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $search_username = $_GET['username'];
    $username = $_SESSION['username'];

    // Récupérer l'ID de l'utilisateur connecté
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $stmt->close();

    $sql = "SELECT id, username FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%{$search_username}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['username'] != $username) {
            // Vérifier si une demande d'ami existe déjà ou si déjà amis
            $friend_id = $row['id'];
            $sql_check = "SELECT * FROM friendships WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $friendship = $result_check->fetch_assoc();
                if ($friendship['status'] == 'accepted') {
                    $response[] = ['username' => $row['username'], 'status' => 'Already friends'];
                } else {
                    $response[] = ['username' => $row['username'], 'status' => 'Friend request already exists'];
                }
            } else {
                $response[] = ['username' => $row['username'], 'status' => 'Add Friend', 'friend_id' => $row['id']];
            }

            $stmt_check->close();
        } else {
            $response[] = ['username' => $row['username'], 'status' => 'You'];
        }
    }

    $stmt->close();
    echo json_encode($response);
}
?>
