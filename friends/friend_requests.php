<?php
session_start();
include('../config/config.php');
include('../includes/header.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT friendships.id, users.username FROM friendships 
        JOIN users ON friendships.user_id = users.id 
        WHERE friendships.friend_id = ? AND friendships.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Friend Requests</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['username'] . 
         " <a href='accept_friend.php?friend_id=" . $row['id'] . "'>Accept</a>" . 
         " <a href='reject_friend.php?friend_id=" . $row['id'] . "'>Reject</a></p>";
}

$stmt->close();
$conn->close();
?>

<?php include('../includes/footer.php'); ?>
