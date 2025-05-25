<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/navbar_secondaire.css">
    <link rel="stylesheet" href="../../CSS/styleverificationcode.css">
    <link rel="icon" href="../../images/logo.png">
    <title>Vérification du code</title>
</head>

<body style="background-color: rgb(235, 235, 235);">
    <header>
        <nav class="navbar">
            <img class="logo_nav" src="../../images/logo_iut.png" alt="IUT Marne La Vallée Logo">
        </nav>
    </header>

    <div class="code_conteneur">
        <h1>Vérification du code</h1>
        <?php if (isset($_GET['erreur'])): ?>
            <?php
                switch ($_GET['erreur']) {
                    case '0':
                        echo '<p style="color: red;">Les codes ne correspondent pas.</p>';
                        break;
                    case '1':
                        echo '<p style="color: red;">Une erreur est survenue.</p>';
                        break;
                    default:
                        echo '<p style="color: red;">Erreur inconnue.</p>';
                }
            ?>
        <?php endif; ?>
        
        <?php if (isset($_GET['renvoyer']) && $_GET['renvoyer'] == 'true'): ?>
            <p style="color: green;">Un nouveau code a été envoyé à votre adresse email.</p>
        <?php endif; ?>
        
        <p>Veuillez saisir le code à 6 chiffres qui a été envoyé à votre adresse email. <a id="retour_form"
                href="../traitement/mdp_oublie.php">Retour</a></p>

        <p class="texte-verification">Ce code expirera dans 10 minutes</p>

        <form method="POST" action="../traitement/verif_code.php" id="verification-form">
            <div class="code-conteneur">
                <input type="text" name="case1" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" name="case2" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" name="case3" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" name="case4" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" name="case5" class="case" maxlength="1" inputmode="numeric" required>
                <input type="text" name="case6" class="case" maxlength="1" inputmode="numeric" required>
            </div>

            <button type="submit" class="bouton-envoyer" id="bouton-envoyer">Vérifier</button>
            <a href="../traitement/renvoyer_code.php" class="renvoyer-code" id="renvoyer-code">Renvoyer le code</a>
        </form>
    </div>

    <script src="../../JS/verif_code.js"></script>

</body>

</html>