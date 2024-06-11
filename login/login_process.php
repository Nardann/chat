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
        echo "<p>Vous serez redirigé vers la page d'accueil dans <span id='countdown'>1</span> secondes.</p>";
        echo "<script>
                var countdownValue = 5; // Initialiser le compte à rebours
                var countdownElement = document.getElementById('countdown');
                var countdownInterval = setInterval(function() {
                    countdownValue--; // Décrémenter le compte à rebours
                    countdownElement.innerText = countdownValue; // Mettre à jour l'affichage
                    if (countdownValue <= 0) {
                        clearInterval(countdownInterval); // Arrêter le compte à rebours lorsque la redirection est effectuée
                        window.location.href = '../index.php'; // Redirection vers la page d'accueil
                    }
                }, 1000); // Actualiser toutes les secondes
              </script>";
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
