<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');
include('../includes/header.php');
include('../includes/navbar.php'); 

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT users.id, users.username FROM friendships 
        JOIN users ON friendships.friend_id = users.id 
        WHERE friendships.user_id = ? AND friendships.status = 'accepted'
        UNION
        SELECT users.id, users.username FROM friendships 
        JOIN users ON friendships.user_id = users.id 
        WHERE friendships.friend_id = ? AND friendships.status = 'accepted'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Friends</h2>";
while ($row = $result->fetch_assoc()) {
    $friend_id = $row['id'];
    $friend_username = $row['username'];
    echo "<form action='conversation.php' method='get'>";
    echo "<input type='hidden' name='friend_id' value='$friend_id'>";
    echo "<p>$friend_username <button type='submit'><i class="bi bi-chat-left-dots"></i></button></p>";
    echo "</form>";
}

$stmt->close();
$conn->close();
?>

<?php include('../includes/footer.php'); ?>
