<?php
require_once 'connexionBDD.php';
session_start();

if (!isset($_SESSION['code_verified']) || !$_SESSION['code_verified']) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    $stmt = $conn->prepare("UPDATE Personne SET mot_de_passe = ? WHERE email = ?");
    $stmt->execute([$hashed, $email]);


    session_unset();
    session_destroy();

    echo "Mot de passe mis à jour avec succès. <a href='index.html'>Se connecter</a>";
}
?>

<form method="POST">
    <h2>Nouveau mot de passe</h2>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
    <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
    <input type="submit" value="Mettre à jour">
</form>