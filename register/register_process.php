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
        echo "<p>Vous serez redirigé vers la page d'accueil dans <span id='countdown'>10</span> secondes.</p>";
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
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
