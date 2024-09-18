<?php
if (isset($_GET['img'])) {
    $encryptedFileName = $_GET['img'];
    $secretKey = 'my_secret_key'; // Utilise la même clé secrète

    // Déchiffrer le nom du fichier
    $decryptedFileName = openssl_decrypt(urldecode($encryptedFileName), 'AES-128-ECB', $secretKey);

    // Vérifier si le fichier existe et afficher l'image
    $filePath = 'data/pictures/' . $decryptedFileName;
    if (file_exists($filePath)) {
        header('Content-Type: image/jpeg');
        readfile($filePath);
    } else {
        echo "Image non trouvée.";
    }
} else {
    echo "Lien invalide.";
}
?>
