<?php
session_start();
require_once 'connexion.php';
require_once 'check_user.php';

header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['data']['user']) || !isAdmin($pdo, $_SESSION['data']['user'])) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit();
}

$userId = $_POST['userId'];

try {
    // Vérifier que l'utilisateur à supprimer n'est pas un admin
    $stmt = $pdo->prepare("
        SELECT CASE
            WHEN adm.Id IS NOT NULL THEN 'admin'
            ELSE 'non-admin'
        END as role
        FROM Personne p
        LEFT JOIN Administration adm ON p.Id_user = adm.Id
        WHERE p.Id_user = :userId
    ");
    $stmt->execute(['userId' => $userId]);
    $userRole = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRole['role'] === 'admin') {
        echo json_encode(['success' => false, 'message' => 'Impossible de supprimer un administrateur']);
        exit();
    }

    // Vérifier que l'utilisateur n'essaie pas de se supprimer lui-même
    $stmt = $pdo->prepare("SELECT email FROM Personne WHERE Id_user = :userId");
    $stmt->execute(['userId' => $userId]);
    $userEmail = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userEmail['email'] === $_SESSION['data']['user']) {
        echo json_encode(['success' => false, 'message' => 'Impossible de supprimer votre propre compte']);
        exit();
    }

    $pdo->beginTransaction();

    // Supprimer de toutes les tables de rôles
    $stmt = $pdo->prepare("DELETE FROM Etudiant WHERE Id = :userId");
    $stmt->execute(['userId' => $userId]);

    $stmt = $pdo->prepare("DELETE FROM Professeur WHERE Id = :userId");
    $stmt->execute(['userId' => $userId]);

    $stmt = $pdo->prepare("DELETE FROM Agent WHERE Id = :userId");
    $stmt->execute(['userId' => $userId]);

    // Supprimer de la table Personne
    $stmt = $pdo->prepare("DELETE FROM Personne WHERE Id_user = :userId");
    $stmt->execute(['userId' => $userId]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
} 