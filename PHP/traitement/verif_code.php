<?php
    session_start();
    require_once './connexion.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = $_SESSION['data']['reset_code'];
        $email = $_SESSION['data']['reset_email'];
        $code_entre = $_POST['case1'] . $_POST['case2'] . $_POST['case3'] . $_POST['case4'] . $_POST['case5'] . $_POST['case6'];

        if ($code_entre == $code) {
            header("Location: ../pages/nouveau_mdp.php" . (($_GET['from'] === "index") ? "?from=index" : "?from=profile"));
            exit();
        } else {
            header("Location: ../pages/verification_code.php?erreur=0"  . (($_GET['from'] === "index") ? "&from=index" : "&from=profile"));
            exit();
        }
    }
?>
