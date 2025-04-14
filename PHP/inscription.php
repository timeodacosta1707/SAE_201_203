<?php
require_once 'connexionBDD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST["identifiant"];
    $date_naissance = $_POST["date_naissance"];
    $num_etudiant = $_POST["num_etudiant"];
    $mdp = $_POST["mdp"];
    $mdp_confirm = $_POST["mdp_confirm"];

    if ($mdp !== $mdp_confirm) {
        die("Les mots de passe ne correspondent pas.");
    }

    $mdp_hashed = password_hash($mdp, PASSWORD_DEFAULT);


    $stmt1 = $conn->prepare("INSERT INTO Etudiant (Id_etudiant) VALUES (?)");
    $stmt1->execute([$num_etudiant]);


    $stmt2 = $conn->prepare("INSERT INTO Personne (Id_user, nom, prenom, email, date_naissance, mot_de_passe, Id_etudiant) VALUES (?, '', '', ?, ?, ?, ?)");
    $stmt2->execute([$num_etudiant, $identifiant, $date_naissance, $mdp_hashed, $num_etudiant]);

    echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
}
?>