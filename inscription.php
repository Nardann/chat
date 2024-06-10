<?php
include 'basedonnée.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, password) 
    
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Inscriptionn effectuée');</script>";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }


    $conn->close();
}
?>
