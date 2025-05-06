<?php
    include 'connexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            session_start();
            $_SESSION['data']['user'] = $user['email'];
            header('Location: ../PHP/accueil.php');
            exit();
        } else {
            if (!$user) {
                header('Location: ../index.php?erreur=2');
            } elseif (!password_verify($mdp, $user['mot_de_passe'])) {
                header('Location: ../index.php?erreur=3');
            } else {
                header('Location: ../index.php?erreur=1');
            }
            exit();
        }
    }
?>
