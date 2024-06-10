<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Non trouvée</title>
</head>
<body>
    <h1>Error 404 - Page Non trouvée</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <p>Vous serez redirigé vers la page d'accueil dans 10 secondes.</p>
    <script>
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 10000); // Redirection après 10 secondes (10000 millisecondes)
    </script>
</body>
</html>
