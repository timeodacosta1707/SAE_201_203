<?php
    require_once 'connexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $num_etudiant = $_POST['num_etudiant'];
        $mdp = $_POST['mdp'];
        $mdp_confirm = $_POST['mdp_confirm'];
        $checkbox = $_POST['checkbox'];

        $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if($user) {
            header('Location: ../index.php?erreur=4');
        } else if($mdp_confirm !== $mdp) {
            header('Location: ../index.php?erreur=5');
        } else if(!isset($checkbox)) {
            header('Location: ../index.php?erreur=6');
        } else {
            session_start();
            if(isset($email, $date_naissance, $num_etudiant, $mdp, $mdp_confirm)) {
                $mdp_hashed = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO Personne (Id_user, nom, prenom, email, date_naissance, mot_de_passe) VALUES (:Id_user, :nom, :prenom, :email, :date_naissance, :mot_de_passe)");
                $stmt->execute([
                    'Id_user' => $num_etudiant,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'date_naissance' => $date_naissance,
                    'mot_de_passe' => $mdp_hashed
                ]);

                $stmt = $pdo->prepare("INSERT INTO Etudiant (Id_etudiant) VALUES (:num_etudiant)");
                $stmt->execute(['num_etudiant' => $num_etudiant]);
                $_SESSION['data']['user'] = $email;
                header('Location: ../PHP/accueil.php');
            } else {
                header('Location: ../index.php?erreur=7');
            }
        }
    }
?>
