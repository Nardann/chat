<?php 
include('../includes/auth.php');
redirectIfLoggedIn();

include('../config/config.php');
include('../includes/header.php'); 
?>

<form action="login_process.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>
<?php include('../includes/footer.php'); ?>
