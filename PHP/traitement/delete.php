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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit();
}

$type = $_POST['type'] ?? '';
$id = $_POST['id'] ?? '';

if (empty($type) || empty($id)) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit();
}

try {
    if ($type === 'salle') {
        // Récupérer l'image avant la suppression
        $stmt = $pdo->prepare("SELECT image FROM Salle WHERE Id_salle = ?");
        $stmt->execute([$id]);
        $salle = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($salle) {
            // Supprimer l'image si elle existe et n'est pas l'image par défaut
            if ($salle['image'] && $salle['image'] !== 'images/default.jpg' && file_exists('../../' . $salle['image'])) {
                unlink('../../' . $salle['image']);
            }

            // Supprimer la salle de la base de données
            $stmt = $pdo->prepare("DELETE FROM Salle WHERE Id_salle = ?");
            $stmt->execute([$id]);

            echo json_encode([
                'success' => true,
                'message' => 'Salle supprimée avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Salle non trouvée'
            ]);
        }
    } elseif ($type === 'materiel') {
        // Récupérer l'image avant la suppression
        $stmt = $pdo->prepare("SELECT image FROM Materiel WHERE Id_materiel = ?");
        $stmt->execute([$id]);
        $materiel = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($materiel) {
            // Supprimer l'image si elle existe et n'est pas l'image par défaut
            if ($materiel['image'] && $materiel['image'] !== 'images/default.jpg' && file_exists('../../' . $materiel['image'])) {
                unlink('../../' . $materiel['image']);
            }

            // Supprimer le matériel de la base de données
            $stmt = $pdo->prepare("DELETE FROM Materiel WHERE Id_materiel = ?");
            $stmt->execute([$id]);

            echo json_encode([
                'success' => true,
                'message' => 'Matériel supprimé avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Matériel non trouvé'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Type invalide'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données'
    ]);
}
?> 