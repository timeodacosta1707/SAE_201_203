<?php
session_start();
if (!isset($_SESSION['reset_email'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['code'] == $_SESSION['reset_code']) {
        $_SESSION['code_verified'] = true;
        header("Location: verification_code.html");
        exit();
    } else {
        echo "Code incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="icon" href="../images/logo.png">
    <title>Vérification du code</title>
</head>

<body style="background-color: rgb(235, 235, 235);">
    <header>
        <nav class="navbar">
            <img class="logo_nav" src="../images/logo_iut.png" alt="IUT Marne La Vallée Logo">
        </nav>
    </header>

    <div class="email">
        <h1>Vérification du code</h1>
        <p>Veuillez saisir le code à 6 chiffres qui a été envoyé à votre adresse email. <a id="retour_form"
                href="./mdp_oublie.html">Retour</a></p>

        <p class="texte-verification">Ce code expirera dans 10 minutes</p>

        <form method="POST" action="#" id="verification-form">
            <div class="code-conteneur">
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" class="case" maxlength="1" inputmode="numeric" required>
            </div>

            <button type="submit" class="bouton-envoyer" id="bouton-envoyer">Vérifier</button>
            <a href="#" class="renvoyer-code" id="renvoyer-code">Renvoyer le code</a>
        </form>
    </div>

    <script src="../JS/verif_code.js"></script>
</body>

</html>