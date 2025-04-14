<?php
require_once 'connexionBDD.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];


    $stmt = $conn->prepare("SELECT * FROM Personne WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $code = rand(100000, 999999); 
        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;

        $to = $email;
        $subject = "Code de réinitialisation du mot de passe";
        $message = "Voici votre code de réinitialisation : $code";
        $headers = "From: noreply@tonsite.com";

        if (mail($to, $subject, $message, $headers)) {
            header("Location: ../HTML/verification_code.php");
            exit();
        } else {
            echo "Erreur lors de l'envoi du mail.";
        }
    } else {
        echo "Aucun compte associé à cette adresse mail.";
    }
}
?>