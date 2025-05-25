<?php
session_start();
if(!isset($_SESSION['data']['user'])) {
    header('Location: ../../index.php');
    exit();
}
require_once 'connexion.php';
require_once 'check_user.php';
$isAdmin = isAdmin($pdo, $_SESSION['data']['user']);

if (!$isAdmin) {
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit();
}

$id = $_GET['id'];
$type = $_GET['type'];

try {
    if ($type === 'salle') {
        $stmt = $pdo->prepare("SELECT * FROM Salle WHERE Id_salle = ?");
        $stmt->execute([$id]);
        $salle = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($salle) {
            echo json_encode(['success' => true, 'salle' => $salle]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Salle non trouvée']);
        }
    } elseif ($type === 'materiel') {
        $stmt = $pdo->prepare("SELECT * FROM Materiel WHERE Id_materiel = ?");
        $stmt->execute([$id]);
        $materiel = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($materiel) {
            echo json_encode(['success' => true, 'materiel' => $materiel]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Matériel non trouvé']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Type invalide']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
}
?> 