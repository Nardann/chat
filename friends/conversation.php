<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../config/config.php');
include('../includes/header.php');
include('../includes/navbar.php'); 

$user1 = $_SESSION['username'];
$friend_id = $_GET['friend_id'];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $friend_id);
$stmt->execute();
$result = $stmt->get_result();
$friend_username = $result->fetch_assoc()['username'];
$stmt->close();
?>

<h2>Conversation avec <?php echo htmlspecialchars($_GET['friend_name']); ?></h2>

<div id="messages" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
    <?php
    foreach ($messages as $message) {
        $sender = $message['sender_id'] == $user_id ? 'Vous' : htmlspecialchars($_GET['friend_name']);
        echo "<p><strong>{$sender}:</strong> " . htmlspecialchars($message['content']) . "</p>";
    }
    ?>
</div>

<form action="send_message.php" method="POST">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <textarea name="message" required></textarea>
    <button type="submit">Envoyer</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadMessages() {
    $.ajax({
        url: 'get_messages.php',
        type: 'GET',
        data: { friend_id: '<?php echo $friend_id; ?>' },
        success: function(data) {
            $('#messages').empty();
            data.forEach(function(message) {
                var sender = message.sender === '<?php echo $user1; ?>' ? 'You' : '<?php echo $friend_username; ?>';
                $('#messages').append('<p><strong>' + sender + ':</strong> ' + message.content + '</p>');
            });
        }
    });
}

$('#messageForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'send_message.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function() {
            loadMessages();
            $('textarea[name="message"]').val('');
        }
    });
});

$(document).ready(function() {
    loadMessages();
    setInterval(loadMessages, 5000);
});

document.addEventListener("DOMContentLoaded", function() {
    var messagesDiv = document.getElementById("messages");
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
});
</script>


<?php include('../includes/footer.php'); ?>
