<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['data']['reset_email'] = $_POST['email'];
        
        header("Location: ../traitement/envoyer_code.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/navbar_secondaire.css">
    <link rel="stylesheet" href="../../CSS/stylemdpoublie.css">
    <link rel="icon" href="../../images/logo.png">
    <title><?php echo ($_GET['from'] === "index") ? "Mot de passe oublié" : "Modifier le mot de passe" ?></title>
</head>
<body>
    <header>
        <nav class="navbar">
            <img class="logo_nav" src="../../images/logo_iut.png" alt="IUT Marne La Vallée Logo">
        </nav>
    </header>

    <div class="email">
        <?php if (isset($_GET['from'])): ?>
            <?php
                switch ($_GET['from']) {
                    case 'index':
                        echo '<h1>Mot de passe oublié</h1>';
                        break;
                    case 'profile':
                        echo '<h1>Changer de mot de passe</h1>';
                        break;
                    default:
                        echo '<p style="color: red;">Erreur inconnue.</p>';
                }
            ?>
        <?php endif; ?>
        <?php if (isset($_GET['erreur'])): ?>
            <?php
                switch ($_GET['erreur']) {
                    case '0':
                        echo '<p style="color: red;">Une erreur est survenue lors de l\'envoie du mail</p>';
                        break;
                    case '1':
                        echo '<p style="color: red;">Adresse mail introuvable.</p>';
                        break;
                    case '2':
                        echo '<p style="color: red;">Impossible d\'envoyer un mail à cette adresse.</p>';
                        break;
                    default:
                        echo '<p style="color: red;">Erreur inconnue.</p>';
                }
            ?>
        <?php endif; ?>
        <p>Veuillez saisir votre adresse courriel ci-dessous et nous vous enverrons un courriel pour changer votre mot de passe. Vous avez fait une erreur ? <?php if ($_GET['from'] === "index"): ?> <a id="retour_form" href="../../index.php">Retourner à la page de connexion</a> <?php else: ?> <a id="retour_form" href="../pages/profile.php">Retourner à la page de profil</a> <?php endif; ?>.</p>
        <form method="POST" action="../traitement/envoyer_code.php">
            <input type="email" name="email" id="email" placeholder="Entrez votre adresse mail">
            <button type="submit" class="bouton-envoyer" id="bouton-envoyer">Envoyer</button>
        </form>
    </div>
</body>
</html>
