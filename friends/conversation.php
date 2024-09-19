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

<h2>Conversation with <?php echo $friend_username; ?></h2>
<div id="messages"></div>

<form class="messageForm" id="messageForm">
    <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
    <textarea name="message" rows="4" cols="50" required></textarea><br>
    <input type="submit" value="Send">
</form>

    <button id="openPopupBtn">Importer une image</button>

    <!-- Popup -->
    <div id="uploadPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <h2>Choisir une image à importer</h2>
            <form action="./pictures/sendpicture.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
                <input type="file" name="fileUpload" id="fileUpload" accept="image/*">
                <input type="submit" id="confirmBtn">Confirmer</button>
            </form>
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

                // Vérifier si le contenu du message est défini
                if (message.content) {
                    $('#messages').append('<p><strong>' + sender + ':</strong> ' + message.content + '</p>');
                } 
                // Si le contenu n'est pas défini mais que l'image l'est, afficher l'image
                else if (message.picture) {
                    $('#messages').append('<p><strong>' + sender + ':</strong> <img src="' + message.picture + '" alt="Image" style="max-width:10%;"></p>');
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

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner les éléments
    var openPopupBtn = document.getElementById('openPopupBtn');
    var popup = document.getElementById('uploadPopup');
    var closeBtn = document.querySelector('.close-btn');
    var confirmBtn = document.getElementById('confirmBtn');
    var uploadForm = document.getElementById('uploadForm');

    // Ouvrir la popup lorsque l'utilisateur clique sur le bouton
    openPopupBtn.addEventListener('click', function() {
        popup.style.display = 'block';
    });

    // Fermer la popup lorsqu'on clique sur la croix
    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Fermer la popup lorsqu'on clique en dehors du contenu
    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });

    // Action lors de la confirmation de l'import
    confirmBtn.addEventListener('click', function() {
        var fileInput = document.getElementById('fileUpload');

        if (fileInput.files.length === 0) {
            alert("Veuillez choisir une image avant de confirmer.");
            return;
        }

        // Vous pouvez ajouter ici une requête AJAX pour envoyer le fichier
        var formData = new FormData(uploadForm);

        // Envoi de l'image via AJAX
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert("Image uploadée avec succès !");
            popup.style.display = 'none';
            // Vous pouvez également mettre à jour l'interface utilisateur ici.
        })
        .catch(error => {
            console.error('Erreur lors de l\'upload:', error);
            alert("Une erreur s'est produite lors de l'upload.");
        });
    });
});

</script>

<?php include('../includes/footer.php'); ?>
