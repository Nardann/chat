<?php
session_start();
include('../config/config.php');
include('../includes/header.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

$username = $_SESSION['username'];

// Récupérer l'ID de l'utilisateur connecté
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];
?>

<h2>Search Users</h2>
<form action="search.php" method="GET">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <button type="submit">Search</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $search_username = $_GET['username'];
    $sql = "SELECT id, username FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%{$search_username}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Search Results</h2>";
    while ($row = $result->fetch_assoc()) {
        if ($row['username'] != $username) {
            // Vérifier si une demande d'ami existe déjà
            $friend_id = $row['id'];
            $sql_check = "SELECT * FROM friendships WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows == 0) {
                echo "<p>" . $row['username'] . " <a href='add_friend.php?friend_id=" . $row['id'] . "'>Add Friend</a></p>";
            } else {
                echo "<p>" . $row['username'] . " (Friend request already exists)</p>";
            }

            $stmt_check->close();
        } else {
            echo "<p>" . $row['username'] . " (You)</p>";
        }
    }

    $stmt->close();
}
?>

<?php include('../includes/footer.php'); ?>
