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

try {
    $nom = $_POST['materielName'] ?? '';
    $type_materiel = $_POST['materielType'] ?? '';
    $description = $_POST['materielDescription'] ?? '';
    $quantite = $_POST['materielQuantity'] ?? '';
    
    if (empty($nom) || empty($type_materiel) || empty($description) || empty($quantite)) {
        echo json_encode(['success' => false, 'error' => 'Tous les champs sont obligatoires']);
        exit();
    }

    // Gestion de l'image
    $image = 'images/default.jpg'; // Image par défaut
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

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO Materiel (nom, type_materiel, description, quantite, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $type_materiel, $description, $quantite, $image]);

    // Récupérer le matériel nouvellement créé
    $id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM Materiel WHERE Id_materiel = ?");
    $stmt->execute([$id]);
    $materiel = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'materiel' => $materiel]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
}
?> 