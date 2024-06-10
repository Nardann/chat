<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
</head>
<body>
    <h1>Error 404 - Page Non trouvée</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <p>Vous serez redirigé vers la page d'accueil dans <span id="countdown">10</span> secondes.</p>
    <script>
        // Fonction pour mettre à jour le compte à rebours
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
        updateCountdown();
    </script>
</body>
</html>
