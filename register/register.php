<?php 
include('../includes/auth.php');
redirectIfLoggedIn();

include('../config/config.php');
include('../includes/header.php'); 
?>
<form action="register_process.php" method="POST">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="name">Nom:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="firstname">Prénom:</label>
    <input type="text" id="firstname" name="firstname" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Register</button>
</form>
<?php include('../includes/footer.php'); ?>
