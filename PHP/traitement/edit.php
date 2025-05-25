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
        $nom = $_POST['salleName'] ?? '';
        $capacite = $_POST['salleCapacite'] ?? '';
        $description = $_POST['salleDescription'] ?? '';
        
        if (empty($nom) || empty($capacite) || empty($description)) {
            echo json_encode(['success' => false, 'error' => 'Tous les champs sont obligatoires']);
            exit();
        }

        // Gestion de l'image si une nouvelle est fournie
        $image = null;
        if (isset($_FILES['salleImage']) && $_FILES['salleImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../images/salles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['salleImage']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['salleImage']['tmp_name'], $uploadFile)) {
                $image = 'images/salles/' . $newFileName;
            }
        }

        // Mise à jour dans la base de données
        if ($image) {
            $stmt = $pdo->prepare("UPDATE Salle SET nom = ?, capacite = ?, description = ?, image = ? WHERE Id_salle = ?");
            $stmt->execute([$nom, $capacite, $description, $image, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE Salle SET nom = ?, capacite = ?, description = ? WHERE Id_salle = ?");
            $stmt->execute([$nom, $capacite, $description, $id]);
        }

        // Récupérer les données mises à jour
        $stmt = $pdo->prepare("SELECT * FROM Salle WHERE Id_salle = ?");
        $stmt->execute([$id]);
        $salle = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'salle' => $salle]);

    } elseif ($type === 'materiel') {
        $nom = $_POST['materielName'] ?? '';
        $type_materiel = $_POST['materielType'] ?? '';
        $description = $_POST['materielDescription'] ?? '';
        $quantite = $_POST['materielQuantity'] ?? '';
        
        if (empty($nom) || empty($type_materiel) || empty($description) || empty($quantite)) {
            echo json_encode(['success' => false, 'error' => 'Tous les champs sont obligatoires']);
            exit();
        }

        // Gestion de l'image si une nouvelle est fournie
        $image = null;
        if (isset($_FILES['materielImage']) && $_FILES['materielImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../images/materiels/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['materielImage']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['materielImage']['tmp_name'], $uploadFile)) {
                $image = 'images/materiels/' . $newFileName;
            }
        }

        // Mise à jour dans la base de données
        if ($image) {
            $stmt = $pdo->prepare("UPDATE Materiel SET nom = ?, type_materiel = ?, description = ?, quantite = ?, image = ? WHERE Id_materiel = ?");
            $stmt->execute([$nom, $type_materiel, $description, $quantite, $image, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE Materiel SET nom = ?, type_materiel = ?, description = ?, quantite = ? WHERE Id_materiel = ?");
            $stmt->execute([$nom, $type_materiel, $description, $quantite, $id]);
        }

        // Récupérer les données mises à jour
        $stmt = $pdo->prepare("SELECT * FROM Materiel WHERE Id_materiel = ?");
        $stmt->execute([$id]);
        $materiel = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'materiel' => $materiel]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Type invalide']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
}
?> 