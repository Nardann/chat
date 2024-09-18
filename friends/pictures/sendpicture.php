<?php
include('../../config/config.php');

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
        if (!mkdir($uploadDir, 0755, true)) {
            die("Erreur lors de la création du répertoire de téléchargement.");
        }
    }

    // Créer un nouveau nom de fichier unique pour éviter les conflits
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    // Compresser l'image (pour JPEG et PNG)
    if ($fileType === 'image/jpeg' || $fileType === 'image/jpg') {
        $image = imagecreatefromjpeg($fileTmpPath);
        imagejpeg($image, $destPath, 75); // 75 = compression
        imagedestroy($image);
    } elseif ($fileType === 'image/png') {
        $image = imagecreatefrompng($fileTmpPath);
        imagepng($image, $destPath, 6); // Compression pour PNG
        imagedestroy($image);
    } else {
        echo "Format de fichier non supporté.";
        exit();
    }

    // Générer un lien chiffré pour accéder à l'image
    $encryptedLink = generateEncryptedLink($newFileName);

    // Afficher le lien chiffré à l'utilisateur
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id'])) {
        // Vérifier si friend_id est défini et non vide
        if (empty($_POST['friend_id'])) {
            echo "Error: friend_id is missing.";
            exit();
        }

        // Récupérer les données du formulaire
        $friend_id = $_POST['friend_id'];
        $message = "https://chat.nardann.xyz/friends/pictures/" . $encryptedLink;

        // Récupérer les noms d'utilisateur
        $user1 = $_SESSION['username'];
        if (!isset($conn)) {
            echo "Error: Database connection is not established.";
            exit();
        }

        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $friend_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $friend_username = $result->fetch_assoc()['username'];
        $stmt->close();

        if (!$friend_username) {
            echo "Error: Friend username not found.";
            exit();
        }

        // Construire le nom du fichier de conversation de manière déterministe
        $conversation_file = "../data/messages/friend/" . (strcmp($user1, $friend_username) < 0 ? "{$user1}-{$friend_username}.json" : "{$friend_username}-{$user1}.json");

        // Assurer que le répertoire pour les fichiers de conversation existe
        $conversationDir = dirname($conversation_file);
        if (!is_dir($conversationDir)) {
            if (!mkdir($conversationDir, 0755, true)) {
                die("Erreur lors de la création du répertoire de conversation.");
            }
        }

        // Charger les messages existants ou initialiser un tableau vide
        $conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];

        // Ajouter le nouveau message à la conversation
        $conversation[] = [
            'sender' => $user1,
            'picture' => $message,
            'timestamp' => time()
        ];

        // Enregistrer la conversation mise à jour dans le fichier JSON
        if (file_put_contents($conversation_file, json_encode($conversation)) === false) {
            echo "Error: Unable to write to conversation file.";
            exit();
        }

        // Rediriger vers la page de conversation
        header("Location: conversation.php?friend_id=$friend_id");
        exit();
    } else {
        echo "Error: Invalid request. Please ensure the form is submitted correctly.";
    }

} else {
    echo "Erreur lors de l'upload. Vérifiez le fichier ou le formulaire.";
}

// Fonction pour générer un lien chiffré
function generateEncryptedLink($fileName) {
    $secretKey = 'nardann_chat>facebook'; // Utilise une clé secrète pour le chiffrement
    $encryptedFileName = openssl_encrypt($fileName, 'AES-128-ECB', $secretKey, 0, str_repeat(' ', 16)); // Ajouter un IV pour AES
    return "access_image.php?img=" . urlencode($encryptedFileName);
}
?>
