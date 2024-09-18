<?php
// Vérifier si un fichier a été uploadé
if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === 0) {
    
    // Obtenir les informations du fichier
    $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
    $fileName = $_FILES['fileUpload']['name'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileType = $_FILES['fileUpload']['type'];
    
    // Définir le chemin de stockage
    $uploadDir = '../../data/pictures/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Créer le dossier si nécessaire
    }

    // Créer un nouveau nom de fichier unique pour éviter les conflits
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    // Compresser l'image (pour JPEG)
    if ($fileType === 'image/jpeg' || $fileType === 'image/jpg') {
        $image = imagecreatefromjpeg($fileTmpPath);
        imagejpeg($image, $destPath, 75); // 75 = compression
    } elseif ($fileType === 'image/png') {
        $image = imagecreatefrompng($fileTmpPath);
        imagepng($image, $destPath, 6); // Compression pour PNG
    } else {
        echo "Format de fichier non supporté.";
        exit();
    }
    imagedestroy($image);

    // Générer un lien chiffré pour accéder à l'image
    $encryptedLink = generateEncryptedLink($newFileName);

    // Afficher le lien chiffré à l'utilisateur
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id'])) {
        include('../config/config.php');
    
        // Récupérer les données du formulaire
        $friend_id = $_POST['friend_id'];
        $message = "https://"+$encryptedLink;
    
        // Récupérer les noms d'utilisateur
        $user1 = $_SESSION['username'];
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $friend_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $friend_username = $result->fetch_assoc()['username'];
        $stmt->close();
    
        // Construire le nom du fichier de conversation de manière déterministe
        $conversation_file = "../data/messages/friend/" . (strcmp($user1, $friend_username) < 0 ? "{$user1}-{$friend_username}.json" : "{$friend_username}-{$user1}.json");
    
        // Charger les messages existants ou initialiser un tableau vide
        $conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];
    
        // Ajouter le nouveau message à la conversation
        $conversation[] = [
            'sender' => $user1,
            'content' => $message,
            'timestamp' => time()
        ];
    
        // Enregistrer la conversation mise à jour dans le fichier JSON
        file_put_contents($conversation_file, json_encode($conversation));
    
        // Rediriger vers la page de conversation
        header("Location: conversation.php?friend_id=$friend_id");
        exit();
    } else {
        echo "Error: Invalid request.";
    }

} else {
    echo "Erreur lors de l'upload.";
}

// Fonction pour générer un lien chiffré
function generateEncryptedLink($fileName) {
    $secretKey = 'nardann_chat>facebook'; // Utilise une clé secrète pour le chiffrement
    $encryptedFileName = openssl_encrypt($fileName, 'AES-128-ECB', $secretKey);
    return "access_image.php?img=" . urlencode($encryptedFileName);
}
?>
