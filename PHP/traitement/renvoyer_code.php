<?php
    session_start();
    
    $email = $_SESSION['data']['reset_email'];

    require_once 'connexion.php';

    $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: mdp_oublie.php?erreur=1" . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
        exit();
    }

    $code = rand(100000, 999999);
    $_SESSION['data']['reset_code'] = $code;

    $subject = "Code de réinitialisation du mot de passe";
    $message = "Voici votre nouveau code de réinitialisation : $code";
    $headers = "From: noreply@gestiondesmateriel.com";

    if (mail($email, $subject, $message, $headers)) {
        header("Location: ../pages/verification_code.php?renvoyer=true"  . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
        exit();
    } else {
        header("Location: ../pages/verification_code.php?erreur=1"  . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
        exit();
    }
?>
