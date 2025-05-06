<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/styleindex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="./images/logo.png">
    <title>Accueil</title>
</head>

<body>
    <div class="container">
        <div class="partie_gauche">
            <img class="logo_form" src="./images/logo_iut.png" alt="Logo IUT">
            <h1 id="formTitle">Se connecter</h1>
            <?php if (isset($_GET['erreur'])): ?>
                <?php
                    switch ($_GET['erreur']) {
                        case '1':
                            echo '<p style="color: red;">Identifiants incorrects.</p>';
                            break;
                        case '2':
                            echo '<p style="color: red;">Compte non trouvé ou l\'adresse mail saisie est incorrecte.</p>';
                            break;
                        case '3':
                            echo '<p style="color: red;">Mot de passe incorrect.</p>';
                            break;
                        case '4':
                            echo '<p style="color: red;">Un compte existe déjà avec cette adresse mail.</p>';
                            break;
                        case '5':
                            echo '<p style="color: red;">Les mots de passe ne correspondent pas.</p>';
                            break;
                        case '6':
                            echo '<p style="color: red;">Veuillez accepter les conditions d\'utilisations.</p>';
                            break;
                        default:
                            echo '<p style="color: red;">Erreur inconnue.</p>';
                    }
                ?>
            <?php endif; ?>
            <?php if (isset($_GET['mdp_modif'])): ?>
                <?php
                    switch ($_GET['mdp_modif']) {
                        case 'true':
                            echo '<p style="color: green;">Votre mot de passe a été modifié avec succès.</p>';
                            break;
                        default:
                            echo '<p style="color: red;">Erreur inconnue.</p>';
                    }
                ?>
            <?php endif; ?>
            <div class="switch">
                <button class="tab active" id="loginTab">Se connecter</button>
                <button class="tab" id="registerTab">S'inscrire</button>
            </div>

            <div class="form" id="loginForm">
                <form action="./PHP/login.php" method="POST">
                    <input type="email" name="email" id="email" placeholder="example@mail.com" required><br>

                    <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required>

                    <a href="./PHP/mdp_oublie.php?from=index" id="mdp_oublie">Mot de passe oublié ?</a><br><br>

                    <input type="submit" value="Se connecter">
                </form>
            </div>

            <div class="form" id="registerForm">
                <form action="./PHP/register.php" method="POST">
                    <input type="email" name="email" placeholder="example@mail.com" required><br>

                    <div class="input-group">
                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prénom" required>
                    </div>

                    <div class="input-group">
                        <input type="date" name="date_naissance" placeholder="JJ/MM/AAAA" required>
                        <input type="text" name="num_etudiant" maxlength="6" placeholder="N° Étudiant" required>
                    </div>

                    <input type="password" name="mdp" id="mdp_register" placeholder="Mot de passe" required>

                    <input type="password" name="mdp_confirm" id="mdp_confirm" placeholder="Valider le mot de passe" required>

                    <label class="cont">
                        <input type="checkbox" name="checkbox">J'accepte les&nbsp;<a href="" id="cond_util">Conditions d'Utilisations</a>
                    </label><br><br>

                    <input type="submit" value="S'inscrire">
                </form>
            </div>
        </div>

        <div class="partie_droite">
            <img src="./images/photo_iut.jpg" alt="IUT" class="logo">
        </div>
    </div>

    <script src="./JS/switch_form.js"></script>
</body>

</html>
