<?php
session_start();
include('includes/header.php');
include('../includes/navbar.php'); 

?>

<?php if (isset($_SESSION['username'])): 
include('includes/navbar.php'); 
?>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="login/logout.php">Logout</a>
    <br>
    <a href="friends/friends.php">View Friends</a>
    <br>
    <a href="friends/search.php">Search Users</a>
    <br>
    <a href="friends/friend_requests.php">Friend Requests</a>
<?php else: ?>
    <h1>Welcome to our website</h1>
    <a href="register/register.php">Register</a> | 
    <a href="login/login.php">Login</a>
<?php endif; ?>

<?php include('includes/footer.php'); ?>
