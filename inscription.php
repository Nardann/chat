<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$servername = "127.0.0.1";
$username = "u477148587_hugo"; // Remplacez par votre nom d'utilisateur MySQL
$password = "C-est-le-0"; // Remplacez par votre mot de passe MySQL
$dbname = "u477148587_hugo";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Inscriptionn effectuée');</script>";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }


    $conn->close();
}
?>
