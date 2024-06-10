<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="inscription.php" method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom:</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom:</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="connexion.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <?php
        if (isset($_SESSION['connecte'])) {
            if ($_SESSION['connecte']) {
                header('Location: actualites.php'); // Redirection vers la page d'actualités
            }
        }
    ?>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités</title>
</head>
<body>
    <h1>Actualités</h1>

    <?php
        if (isset($_SESSION['connecte'])) {
            if ($_SESSION['connecte']) {
                // Afficher les actualités ici
                echo '<p>Bienvenue sur la page d\'actualités!</p>';
                // ... (Code pour afficher les actualités) ...
            } else {
                echo '<p>Vous devez vous connecter pour accéder à cette page.</p>';
                echo '<a href="connexion.php">Se connecter</a>';
            }
        } else {
            echo '<p>Vous devez vous connecter pour accéder à cette page.</p>';
            echo '<a href="connexion.php">Se connecter</a>';
        }
    ?>
</body>
</html>
