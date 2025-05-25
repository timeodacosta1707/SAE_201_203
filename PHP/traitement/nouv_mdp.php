<?php
require_once 'connexion.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nouv_mdp = $_POST["nouv_mdp"];
    $conf_nouv_mdp = $_POST["conf_nouv_mdp"];

    if($nouv_mdp !== $conf_nouv_mdp) {
        header("Location: nouveau_mdp.php?erreur=0" . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $_SESSION['data']['reset_email']]);
        $user = $stmt->fetch();

        if ($user) {
            if(isset($nouv_mdp, $conf_nouv_mdp)) {
                $mdp_hashed = password_hash($nouv_mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE Personne SET password = :password WHERE email = :email");
                $stmt->execute([
                    'password' => $mdp_hashed,
                    'email' => $_SESSION['data']['reset_email']
                ]);

                header("Location: " . (($_GET['from'] === "index") ? "../index.php?mdp_modif=true" : "../pages/profile.php"));
                exit();
            } else {
                header('Location: ../index.php?erreur=default');
                exit();
            }
        }
    }
}
?>