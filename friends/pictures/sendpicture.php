<?php
include('../../config/config.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
// Fonction pour écrire dans le fichier de log
function writeLog($message) {
    $logFile = '../../logs/upload_log.txt'; // Définir le chemin du fichier log
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[" . $timestamp . "] " . $message . PHP_EOL;
    
    // S'assurer que le dossier des logs existe
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Vérifier si un fichier a été uploadé
if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === 0) {
    writeLog("Fichier uploadé : " . $_FILES['fileUpload']['name']);

    // Obtenir les informations du fichier
    $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
    $fileName = $_FILES['fileUpload']['name'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileType = $_FILES['fileUpload']['type'];

    // Définir le chemin de stockage
    $uploadDir = '../../data/pictures/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            writeLog("Erreur lors de la création du répertoire de téléchargement.");
            die("Erreur lors de la création du répertoire de téléchargement.");
        }
        writeLog("Répertoire de téléchargement créé.");
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
        writeLog("Fichier JPEG compressé et sauvegardé sous " . $newFileName);
    } elseif ($fileType === 'image/png') {
        $image = imagecreatefrompng($fileTmpPath);
        imagepng($image, $destPath, 6); // Compression pour PNG
        imagedestroy($image);
        writeLog("Fichier PNG compressé et sauvegardé sous " . $newFileName);
    } else {
        writeLog("Format de fichier non supporté : " . $fileType);
        echo "Format de fichier non supporté.";
        exit();
    }

    // Générer un lien chiffré pour accéder à l'image
    $encryptedLink = generateEncryptedLink($newFileName);
    writeLog("Lien chiffré généré : " . $encryptedLink);

    // Afficher le lien chiffré à l'utilisateur
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id'])) {
        // Vérifier si friend_id est défini et non vide
        if (empty($_POST['friend_id'])) {
            writeLog("Error: friend_id is missing.");
            echo "Error: friend_id is missing.";
            exit();
        }

        // Récupérer les données du formulaire
        $friend_id = $_POST['friend_id'];
        $message = "https://chat.nardann.xyz/friends/pictures/" . $encryptedLink;

        // Récupérer les noms d'utilisateur
        $user1 = $_SESSION['username'];
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $friend_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $friend_username = $result->fetch_assoc()['username'];
        $stmt->close();

        if (!$friend_username) {
            writeLog("Error: Friend username not found for friend_id: $friend_id");
            echo "Error: Friend username not found.";
            exit();
        }

        // Construire le nom du fichier de conversation de manière déterministe
        $conversation_file = "../../data/messages/friend/" . (strcmp($user1, $friend_username) < 0 ? "{$user1}-{$friend_username}.json" : "{$friend_username}-{$user1}.json");

        // Charger les messages existants ou initialiser un tableau vide
        $conversation = file_exists($conversation_file) ? json_decode(file_get_contents($conversation_file), true) : [];
        writeLog("Chargement de la conversation à partir du fichier : $conversation_file");

        // Ajouter le nouveau message à la conversation
        $conversation[] = [
            'sender' => $user1,
            'content' => $message,
            'timestamp' => time()
        ];

        // Debugging: Vérifier le contenu avant d'écrire
        $jsonData = json_encode($conversation);
        if ($jsonData === false) {
            writeLog("Erreur lors de l'encodage JSON : " . json_last_error_msg());
            die("Erreur lors de l'encodage JSON: " . json_last_error_msg());
        }

        // Enregistrer la conversation mise à jour dans le fichier JSON
        if (file_put_contents($conversation_file, $jsonData) === false) {
            writeLog("Error: Unable to write to conversation file: $conversation_file");
            echo "Error: Unable to write to conversation file. Check file permissions.";
            exit();
        }

        writeLog("Conversation mise à jour avec succès pour $user1 et $friend_username.");

        // Rediriger vers la page de conversation
        header("Location: ../conversation.php?friend_id=$friend_id");
        exit();
    } else {
        writeLog("Error: Invalid request.");
        echo "Error: Invalid request.";
    }

} else {
    writeLog("Erreur lors de l'upload ou fichier non fourni.");
    echo "Erreur lors de l'upload. Vérifiez le fichier ou le formulaire.";
}

// Fonction pour générer un lien chiffré
function generateEncryptedLink($fileName) {
    $secretKey = 'nardann_chat>facebook'; // Utilise une clé secrète pour le chiffrement
    $encryptedFileName = openssl_encrypt($fileName, 'AES-128-ECB', $secretKey, 0, str_repeat(' ', 16)); // Ajouter un IV pour AES
    return "access_image.php?img=" . urlencode($encryptedFileName);
}
?>

