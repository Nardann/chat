<?php
session_start();

function redirectIfLoggedIn() {
    if (isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit();
    }
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit();
    }
}
?>
