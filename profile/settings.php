

<div class="container mt-5">
    <h2>Paramètres de votre profil</h2>

    <!-- Formulaire contenant les informations de l'utilisateur -->
    <form action="update_profile.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Biographie</label>
            <textarea class="form-control" id="bio" name="bio" rows="3"><?= htmlspecialchars($user['bio']); ?></textarea>
        </div>

        <!-- Bouton pour modifier le profil avec popup -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmUpdateModal">
            Modifier le profil
        </button>

        <!-- Bouton pour supprimer le profil avec popup -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Supprimer le profil
        </button>
    </form>
</div>

<!-- Modal pour confirmer la modification du profil -->
<div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmUpdateModalLabel">Confirmation de la modification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir modifier votre profil ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <!-- Bouton pour soumettre le formulaire et modifier le profil -->
                <button type="submit" class="btn btn-primary" formaction="update_profile.php">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour confirmer la suppression du profil -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer définitivement votre profil ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <!-- Bouton pour supprimer le profil -->
                <a href="delete_profile.php" class="btn btn-danger">Confirmer</a>
            </div>
        </div>
    </div>
</div>
