<?php
    session_start();
    require_once 'connexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $to = $_POST['email'];

        $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $to]);
        $user = $stmt->fetch();

        if (!$user) {
            header("Location: ../pages/mdp_oublie.php?erreur=1" . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
            exit();
        }

        $code = rand(100000, 999999);
        $_SESSION['data']['reset_code'] = $code;
        $_SESSION['data']['reset_email'] = $to;

        $subject = "Code de réinitialisation du mot de passe";
        $message = "Voici votre code de réinitialisation : $code";
        $headers = "From: noreply@gestiondesmateriel.com";

        if (mail($to, $subject, $message, $headers)) {
            header("Location: " . (($_GET['from'] === "index") ? "../pages/verification_code.php?from=index" : "../pages/verification_code.php?from=profile"));
            exit();
        } else {
            header("Location: ../pages/mdp_oublie.php?erreur=2" . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
            exit();
        }
    } 
?>
