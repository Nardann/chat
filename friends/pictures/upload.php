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
    echo "Votre image a été uploadée avec succès ! Voici le lien chiffré : " . $encryptedLink;
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
