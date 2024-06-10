<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');
include('../includes/header.php');

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT users.username FROM friendships 
        JOIN users ON friendships.friend_id = users.id 
        WHERE friendships.user_id = ? AND friendships.status = 'accepted'
        UNION
        SELECT users.username FROM friendships 
        JOIN users ON friendships.user_id = users.id 
        WHERE friendships.friend_id = ? AND friendships.status = 'accepted'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Friends</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['username'] . "</p>";
}

$stmt->close();
$conn->close();
?>

<?php include('../includes/footer.php'); ?>
