<?php
include 'connexion.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID du matériel non spécifié']);
    exit;
}

$id = intval($_GET['id']);

try {
    $sql = "SELECT * FROM Materiel WHERE Id_materiel = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($material) {
        echo json_encode(['success' => true, 'material' => $material]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Matériel non trouvé']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>