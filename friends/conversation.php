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

<h2 style="position:fixed; background-color:white; border-radius:10px; padding:20px;">Conversation avec <?php echo $friend_username; ?></h2>
<div id="messages"  style="margin-top:100px;"></div>

<form class="messageForm" id="messageForm">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <textarea name="message" rows="4" cols="50" required></textarea><br>
    <input type="submit" class="btn btn-primary" value="Envoyer">
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
      Images
    </button>
</form>
<!-- Bouton pour ouvrir le modal -->


<!-- Modal Bootstrap -->
<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- En-tête du modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="uploadImageModalLabel">Importer une image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Corps du modal : formulaire pour l'upload -->
      <div class="modal-body">
        <form id="imageUploadForm" action="./pictures/sendpicture.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="fileUpload" class="form-label">Choisir une image</label>
            <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
            <input class="form-control" type="file" id="fileUpload" name="fileUpload" accept="image/*">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <input type="submit" class="btn btn-primary" id="confirmBtn"></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
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
                
                // Convertir le timestamp en date lisible
                var date = new Date(message.timestamp * 1000);
                var formattedDate = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear().toString().slice(-2); // Ex: 24/09/24
                var formattedTime = date.getHours() + ':' + ('0' + date.getMinutes()).slice(-2); // Ex: 13:45

                var timestampHtml = '<span class="timestamp" style:"color:#BBBBBB;font-size:10px;"><i>' + formattedTime + ' ' + formattedDate + ' </i></span>';

                // Affichage du message ou de l'image
                if (message.content) {
                    $('#messages').append('<p>' + timestampHtml + '<strong>' + sender + ':</strong> ' + message.content +'</p>');
                } 
                else if (message.picture) {
                    $('#messages').append('<p>' + timestampHtml + '<strong>' + sender + ':</strong> <img src="' + message.picture + '" alt="Image" style="max-width:15%;">' + '</p>');
                }
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
setTimeout(function() {
                window.scrollTo(0, document.body.scrollHeight);
            }, 5000);        });

</script>

<?php include('../includes/footer.php'); ?>
