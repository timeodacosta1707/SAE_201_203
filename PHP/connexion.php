<?php
session_start();
require_once 'connexionBDD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST["identifiant"];
    $mdp = $_POST["mdp"];

    $stmt = $conn->prepare("SELECT * FROM Personne WHERE email = ? OR Id_user = ?");
    $stmt->execute([$identifiant, $identifiant]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user'] = $user;

        if (!empty($user['Id_prof'])) {
            header("Location: dashboard_professeur.php");
        } elseif (!empty($user['Id_agent'])) {
            header("Location: dashboard_agent.php");
        } elseif (!empty($user['Id_Admin'])) {
            header("Location: dashboard_admin.php");
        } elseif (!empty($user['Id_etudiant'])) {
            header("Location: dashboard_etudiant.php");
        } else {
            echo "Type de compte non reconnu.";
        }
    } else {
        echo "Identifiants incorrects.";
    }
}
?>