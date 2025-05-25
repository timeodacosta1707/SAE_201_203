<?php
session_start();
require_once 'connexion.php';
require_once 'check_user.php';

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['data']['user']) || !isAdmin($pdo, $_SESSION['data']['user'])) {
    header('Location: ../../index.php');
    exit();
}

// Vérifier si tous les champs requis sont présents
if (!isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['role'])) {
    $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
    header('Location: ../pages/admin_users.php');
    exit();
}

// Vérifier que le rôle est valide (professeur ou agent)
if (!in_array($_POST['role'], ['professeur', 'agent'])) {
    $_SESSION['error'] = "Rôle invalide. Seuls les professeurs et agents peuvent être ajoutés.";
    header('Location: ../pages/admin_users.php');
    exit();
}

try {
    // Commencer une transaction
    $pdo->beginTransaction();

    // Insérer dans la table Personne d'abord
    $stmt = $pdo->prepare("INSERT INTO Personne (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
    $stmt->execute([
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'mot_de_passe' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);

    // Récupérer l'ID généré pour Personne
    $personneId = $pdo->lastInsertId();

    // Insérer dans la table appropriée selon le rôle
    if ($_POST['role'] === 'professeur') {
        if (!isset($_POST['date_embauche'])) {
            throw new Exception("La date d'embauche est requise pour un professeur.");
        }

        $stmt = $pdo->prepare("INSERT INTO Professeur (Id, date_embauche) VALUES (?, ?)");
        $stmt->execute([
            $personneId,
            $_POST['date_embauche']
        ]);
    } elseif ($_POST['role'] === 'agent') {
        $stmt = $pdo->prepare("INSERT INTO Agent (Id) SELECT Id_user FROM Personne WHERE Id_user = ?");
        $stmt->execute([$personneId]);
    }

    // Valider la transaction
    $pdo->commit();

    $_SESSION['success'] = "Utilisateur ajouté avec succès.";
    header('Location: ../pages/admin_users.php');
    exit();

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
    header('Location: ../pages/admin_users.php');
    exit();
} 