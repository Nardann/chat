<?php
session_start();
include('includes/header.php');
?>

<?php if (isset($_SESSION['username'])): ?>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="login/logout.php">Logout</a>
<?php else: ?>
    <h1>Welcome to our website</h1>
    <a href="register/register.php">Register</a> | 
    <a href="login/login.php">Login</a>
<?php endif; ?>

<?php include('includes/footer.php'); ?>
