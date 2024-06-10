<?php
session_start();
include('../config/config.php');
include('../includes/header.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
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
        echo "<p>" . $row['username'] . " <a href='add_friend.php?friend_id=" . $row['id'] . "'>Add Friend</a></p>";
    }

    $stmt->close();
}
?>

<?php include('../includes/footer.php'); ?>
