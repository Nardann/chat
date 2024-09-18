<?php 
include('../config/config.php');
include('../includes/auth.php');
redirectIfLoggedIn();


include('../includes/header.php'); 
?>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-12 col-md-6 bg-light p-4 rounded shadow">
        <form>
            <div class="form-group mb-3">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Se connecter</button>
        </form>
    </div>
</div>
<?php include('../includes/footer.php'); ?>
