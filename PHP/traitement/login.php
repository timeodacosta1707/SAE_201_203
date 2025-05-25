<?php
    require_once 'connexion.php';
    require_once 'check_user.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $ip = $_SERVER['REMOTE_ADDR'];
        
        // Vérifier si l'IP ou l'email est bloqué
        $stmt = $pdo->prepare("SELECT COUNT(*) as attempts, MAX(attempt_time) as last_attempt, is_blocked, block_expires 
                              FROM login_attempts 
                              WHERE (email = :email OR ip_address = :ip) 
                              AND attempt_time > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
        $stmt->execute(['email' => $email, 'ip' => $ip]);
        $attempts = $stmt->fetch();
        
        // Vérifier si l'utilisateur est bloqué
        if ($attempts['is_blocked'] && new DateTime($attempts['block_expires']) > new DateTime()) {
            $now = new DateTime();
            $blockExpires = new DateTime($attempts['block_expires']);
            $timeLeft = $now->diff($blockExpires);
            $minutesLeft = ($timeLeft->i > 0) ? $timeLeft->i : 1; // Au moins 1 minute si moins
            header('Location: ../../index.php?erreur=blocked&time=' . $minutesLeft);
            exit();
        }
        
        // Vérifier si le nombre de tentatives dépasse 5
        if ($attempts['attempts'] >= 5) {
            // Bloquer l'utilisateur
            $blockExpires = new DateTime();
            $blockExpires->add(new DateInterval('PT10M')); // Ajoute 10 minutes
            
            $stmt = $pdo->prepare("UPDATE login_attempts 
                                 SET is_blocked = 1, 
                                     block_expires = :block_expires 
                                 WHERE email = :email OR ip_address = :ip");
            $stmt->execute([
                'email' => $email,
                'ip' => $ip,
                'block_expires' => $blockExpires->format('Y-m-d H:i:s')
            ]);
            
            header('Location: ../../index.php?erreur=blocked&time=10');
            exit();
        }

        // Vérifier les identifiants
        $stmt = $pdo->prepare("SELECT * FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            // Connexion réussie - réinitialiser les tentatives
            $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = :email OR ip_address = :ip");
            $stmt->execute(['email' => $email, 'ip' => $ip]);
            
            session_start();
            $_SESSION['data']['user'] = $user['email'];
            $isAdmin = isAdmin($pdo, $_SESSION['data']['user']);
            if(!$isAdmin) {
                header('Location: ../pages/accueil.php');
                exit();
            } else {
                header('Location: ../pages/admin_users.php');
                exit();
            }
        } else {
            // Échec de connexion - enregistrer la tentative
            $stmt = $pdo->prepare("INSERT INTO login_attempts (email, ip_address, attempt_time) VALUES (:email, :ip, NOW())");
            $stmt->execute(['email' => $email, 'ip' => $ip]);
            
            if (!$user) {
                header('Location: ../../index.php?erreur=2');
            } elseif (!password_verify($mdp, $user['mot_de_passe'])) {
                header('Location: ../../index.php?erreur=3');
            } else {
                header('Location: ../../index.php?erreur=1');
            }
            exit();
        }
    }
?>
