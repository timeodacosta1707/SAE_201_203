<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/navbar_secondaire.css">
    <link rel="stylesheet" href="../CSS/stylenouveaumdp.css">
    <link rel="icon" href="../images/logo.png">
    <title>Nouveau mot de passe</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <img class="logo_nav" src="../images/logo_iut.png" alt="IUT Marne La Vallée Logo">
        </nav>
    </header>

    <div class="conteneur">
        <h1>Nouveau mot de passe</h1>
        <?php if (isset($_GET['erreur'])): ?>
            <?php
                switch ($_GET['erreur']) {
                    case '0':
                        echo '<p style="color: red;">Les mots de passe ne correspondent pas.</p>';
                        break;
                    default:
                        echo '<p style="color: red;">Erreur inconnue.</p>';
                }
            ?>
        <?php endif; ?>
        <p>Veuillez saisir votre nouveau mot de passe.</p>
        <form method="POST" action="./nouv_mdp.php">
            <input type="password" name="nouv_mdp" id="nouv_mdp" placeholder="Entrez votre nouveau mot de passe" required>
            <input type="password" name="conf_nouv_mdp" id="conf_nouv_mdp" placeholder="Entrez à nouveau votre mot de passe" required>

            <button type="submit" class="bouton-valider" id="bouton-valider">Valider</button>
        </form>
    </div>
</body>
</html>