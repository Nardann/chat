<?php
session_start();
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        echo "Login successful";
        echo "<p>Vous serez redirigé vers la page d'accueil dans <span id='countdown'>5</span> secondes.</p>";
        echo "<script>
                function updateCountdown() {
                    var countdownElement = document.getElementById('countdown');
                    var countdownValue = parseInt(countdownElement.innerText);
                    countdownValue--;

                    // Si le compte à rebours atteint 0, rediriger vers la page d'accueil
                    if (countdownValue <= 0) {
                        window.location.href = "../index.php";
                    } else {
                        countdownElement.innerText = countdownValue;
                        setTimeout(updateCountdown, 1000); // Actualiser toutes les secondes
                    }
                }

                // Lancer la mise à jour initiale du compte à rebours
                updateCountdown();</script>";
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
