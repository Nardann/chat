<?php 
include('../config/config.php');
include('../includes/auth.php');
redirectIfLoggedIn();


include('../includes/header.php'); 
?>

<form>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button class="btn btn-primary" type="submit">Login</button>
</form>
<?php include('../includes/footer.php'); ?>
