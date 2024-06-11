<?php
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, name, firstname, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $name, $firstname, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful";
        echo "<p>Vous serez redirigé vers la page d'accueil dans <span id='countdown'>5</span> secondes.</p>";
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
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
