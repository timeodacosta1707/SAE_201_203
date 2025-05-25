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
    $nom = $_POST['salleName'] ?? '';
    $capacite = $_POST['salleCapacite'] ?? '';
    $description = $_POST['salleDescription'] ?? '';
    
    if (empty($nom) || empty($capacite) || empty($description)) {
        echo json_encode(['success' => false, 'error' => 'Tous les champs sont obligatoires']);
        exit();
    }

    // Gestion de l'image
    $image = 'images/default.jpg'; // Image par défaut
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

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO Salle (nom, capacite, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $capacite, $description, $image]);

    // Récupérer la salle nouvellement créée
    $id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM Salle WHERE Id_salle = ?");
    $stmt->execute([$id]);
    $salle = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'salle' => $salle]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
}
?> 