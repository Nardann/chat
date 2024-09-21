<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();

include('../config/config.php');
include('../includes/header.php');
include('../includes/navbar.php'); 

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
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" required>
</form>

<h2>Search Results</h2>
<div id="search-results"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#username').on('input', function() {
        var search_username = $(this).val();
        if (search_username.length >= 3) { // Condition pour vérifier si la longueur est au moins 3 caractères
            $.ajax({
                url: 'search_process.php',
                type: 'GET',
                data: { username: search_username },
                success: function(data) {
                    var results = JSON.parse(data);
                    $('#search-results').empty();
                    results.forEach(function(user) {
                        if (user.status === 'Add Friend') {
                            $('#search-results').append('<p>' + user.username + ' <a href="add_friend.php?friend_id=' + user.friend_id + '">Add Friend</a></p>');
                        } else {
                            $('#search-results').append('<p>' + user.username + ' (' + user.status + ')</p>');
                        }
                    });
                }
            });
        } else {
            $('#search-results').empty();
        }
    });
});
</script>

<?php include('../includes/footer.php'); ?>
