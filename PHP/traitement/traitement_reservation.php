<?php
session_start();
require_once './connexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupération des données du formulaire
$type = $_POST['type'] ?? '';
$id = $_POST['id'] ?? '';
$dateReservation = $_POST['dateReservation'] ?? '';
$dateDebut = $_POST['dateDebut'] ?? '';
$dateFin = $_POST['dateFin'] ?? '';
$heureDebut = $_POST['heureDebut'] ?? '';
$heureFin = $_POST['heureFin'] ?? '';
$motifReservation = $_POST['motifReservation'] ?? '';
$quantiteReservation = $_POST['quantiteReservation'] ?? 1;

// Validation des données
if ($type === 'salle') {
    if (empty($dateReservation) || empty($heureDebut) || empty($heureFin) || empty($motifReservation)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        exit;
    }
} else {
    if (empty($dateDebut) || empty($dateFin) || empty($heureDebut) || empty($heureFin) || empty($motifReservation)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        exit;
    }
}

try {
    $pdo->beginTransaction();

    // Création du créneau horaire
    $creneau = $heureDebut . ' - ' . $heureFin;

    // Préparation de la requête d'insertion
    if ($type === 'salle') {
        $sql = "INSERT INTO Reservation (Id_salle, date_reservation, creneau, statut, reservation_salle, type_user) 
                VALUES (:id, :date_reservation, :creneau, 'En attente', :motif, :user)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date_reservation', $dateReservation);
    } else {
        $sql = "INSERT INTO Reservation (Id_materiel, date_reservation, creneau, statut, reservation_materiel, type_user) 
                VALUES (:id, :date_reservation, :creneau, 'En attente', :motif, :user)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date_reservation', $dateDebut);
    }

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':creneau', $creneau);
    $stmt->bindParam(':motif', $motifReservation);
    $stmt->bindParam(':user', $_SESSION['data']['user']);

    $stmt->execute();
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Réservation effectuée avec succès']);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la réservation: ' . $e->getMessage()]);
}
?>