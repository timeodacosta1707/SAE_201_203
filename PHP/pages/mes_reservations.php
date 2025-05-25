<?php
    session_start();
    if(!isset($_SESSION['data']['user'])) {
        header('Location: ../../index.php');
        exit();
    }
    require_once '../traitement/connexion.php';
    require_once '../traitement/check_user.php';
    $isAdmin = isAdmin($pdo, $_SESSION['data']['user']);
    $isAgent = isAgent($pdo, $_SESSION['data']['user']);
    $isProfesseur = isProfesseur($pdo, $_SESSION['data']['user']);

    // Traitement des actions d'acceptation/refus des réservations
    if ($isAdmin && isset($_POST['action']) && isset($_POST['reservation_id'])) {
        $action = $_POST['action'];
        $reservation_id = $_POST['reservation_id'];
        
        try {
            // Vérifier que la réservation existe et est en attente
            $sql = "SELECT statut FROM Reservation WHERE Id_reservation = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $reservation_id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation && $reservation['statut'] === 'En attente') {
                if ($action === 'accepter') {
                    $new_statut = 'Acceptée';
                } elseif ($action === 'refuser') {
                    $new_statut = 'Refusée';
                }
                
                if (isset($new_statut)) {
                    $sql = "UPDATE Reservation SET statut = :statut WHERE Id_reservation = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':statut' => $new_statut, ':id' => $reservation_id]);
                    
                    // Ajouter un message de succès
                    $_SESSION['message'] = "La réservation a été " . strtolower($new_statut) . " avec succès.";
                    $_SESSION['message_type'] = "success";
                }
            } else {
                $_SESSION['message'] = "Cette réservation n'existe pas ou n'est plus en attente.";
                $_SESSION['message_type'] = "error";
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
            $_SESSION['message'] = "Une erreur est survenue lors de la mise à jour du statut.";
            $_SESSION['message_type'] = "error";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/stylemesreservations.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <style>
    </style>
    <link rel="stylesheet" href="../../CSS/reservations.css">
    <title>Mes réservations - Université Gustave Eiffel</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="navbar-brand d-flex align-items-center">
                <img src="../../images/Republique.png" alt="République Française" class="logo-republique">
                <img src="https://discret.univ-gustave-eiffel.fr/fileadmin/_processed_/b/4/csm_logo_univ_gustave_eiffel_rvb_e3ea850fc1.png"
                    alt="Université Gustave Eiffel" class="logo-universite">
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <?php if (!$isAdmin && !$isAgent): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/accueil.php">Accueil</a>
                    </li>
                    <?php if (!$isProfesseur): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_materiels.php">Matériel</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_salles.php">Salles</a>
                    </li>
                    <?php elseif ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./gestion-salles-materiels.php">Matériels et Salles</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Réservations</a>
                    </li>
                    <?php if (!$isAdmin && !$isAgent && !$isProfesseur): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../../HTML/contact.html">Contact</a>
                    </li>
                    <?php endif; ?>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin_users.php">
                            Administration
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center d-none d-lg-flex">
                    <div class="dropdown">
                        <div class="icone-profil" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="./profile.php">
                                    <i class="fas fa-user-circle"></i> <span>Mon profil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../traitement/se_deconnecter.php">
                                    <i class="fas fa-sign-out-alt"></i> <span>Se déconnecter</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex align-items-center d-lg-none justify-content-end mt-3">
                    <div class="dropdown">
                        <div class="icone-profil" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="./profile.php">
                                    <i class="fas fa-user-circle"></i> <span>Mon profil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../traitement/se_deconnecter.php">
                                    <i class="fas fa-sign-out-alt"></i> <span>Se déconnecter</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php if (!$isAdmin): ?>
    <header class="profile-header">
        <div class="container">
            <h1>Mes Réservations</h1>
        </div>
    </header>
    <?php endif; ?>
    <?php if ($isAdmin): ?>
    <header class="profile-header">
        <div class="container">
            <h1>Toutes les Réservations</h1>
        </div>
    </header>
    <?php endif; ?>
    <?php if ($isProfesseur): ?>
    <header class="profile-header">
        <div class="container">
            <h1>Gestion des Réservations</h1>
        </div>
    </header>
    <?php endif; ?>
    <div class="container my-5">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <?php if ($isProfesseur): ?>
                <!-- Tableau des réservations personnelles -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Mes réservations</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT r.*, p.nom, p.prenom 
                               FROM Reservation r 
                               LEFT JOIN Personne p ON r.type_user = p.email 
                               WHERE r.type_user = :email 
                               ORDER BY r.date_reservation DESC";
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':email', $_SESSION['data']['user']);
                        $stmt->execute();
                        $mesReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($mesReservations) > 0) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Date</th>';
                            echo '<th>Créneau</th>';
                            echo '<th>Type</th>';
                            echo '<th>Statut</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            
                            foreach ($mesReservations as $reservation) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($reservation['date_reservation']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['creneau']) . '</td>';
                                echo '<td>' . ($reservation['Id_salle'] ? 'Salle' : 'Matériel') . '</td>';
                                echo '<td>';
                                $statut = htmlspecialchars($reservation['statut']);
                                $badgeClass = '';
                                switch($statut) {
                                    case 'En attente':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'Refusée':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    case 'Acceptée':
                                        $badgeClass = 'bg-success';
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                                
                                if ($isAdmin && $statut === 'En attente') {
                                    echo '<form method="post" style="display:inline-flex;gap:5px;">';
                                    echo '<input type="hidden" name="reservation_id" value="' . $reservation['Id_reservation'] . '">';
                                    echo '<button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">Accepter</button>';
                                    echo '<button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>';
                                    echo '</form>';
                                } else {
                                    echo '<span class="badge ' . $badgeClass . '">' . $statut . '</span>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">Aucune réservation personnelle</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Tableau de toutes les réservations -->
                <div class="card">
                    <div class="card-header">
                        <h2>Toutes les réservations</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT r.*, p.nom, p.prenom 
                               FROM Reservation r 
                               LEFT JOIN Personne p ON r.type_user = p.email 
                               ORDER BY r.date_reservation DESC";
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $toutesReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($toutesReservations) > 0) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Date</th>';
                            echo '<th>Créneau</th>';
                            echo '<th>Type</th>';
                            echo '<th>Statut</th>';
                            echo '<th>Utilisateur</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            
                            foreach ($toutesReservations as $reservation) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($reservation['date_reservation']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['creneau']) . '</td>';
                                echo '<td>' . ($reservation['Id_salle'] ? 'Salle' : 'Matériel') . '</td>';
                                echo '<td>';
                                $statut = htmlspecialchars($reservation['statut']);
                                $badgeClass = '';
                                switch($statut) {
                                    case 'En attente':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'Refusée':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    case 'Acceptée':
                                        $badgeClass = 'bg-success';
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                                
                                if ($isAdmin && $statut === 'En attente') {
                                    echo '<form method="post" style="display:inline-flex;gap:5px;">';
                                    echo '<input type="hidden" name="reservation_id" value="' . $reservation['Id_reservation'] . '">';
                                    echo '<button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">Accepter</button>';
                                    echo '<button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>';
                                    echo '</form>';
                                } else {
                                    echo '<span class="badge ' . $badgeClass . '">' . $statut . '</span>';
                                }
                                echo '</td>';
                                echo '<td>' . htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) . '</td>';
                                echo '</tr>';
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">Aucune réservation</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo $isAdmin ? 'Toutes les réservations' : 'Mes réservations'; ?></h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($isAdmin) {
                            $sql = "SELECT r.Id_reservation, r.*, p.nom, p.prenom 
                                   FROM Reservation r 
                                   LEFT JOIN Personne p ON r.type_user = p.email 
                                   ORDER BY r.date_reservation DESC";
                        } else {
                            $sql = "SELECT r.Id_reservation, r.*, p.nom, p.prenom 
                                   FROM Reservation r 
                                   LEFT JOIN Personne p ON r.type_user = p.email 
                                   WHERE r.type_user = :email 
                                   ORDER BY r.date_reservation DESC";
                        }
                        
                        $stmt = $pdo->prepare($sql);
                        if (!$isAdmin) {
                            $stmt->bindParam(':email', $_SESSION['data']['user']);
                        }
                        $stmt->execute();
                        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($reservations) > 0) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Date</th>';
                            echo '<th>Créneau</th>';
                            echo '<th>Type</th>';
                            echo '<th>Statut</th>';
                            if ($isAdmin) {
                                echo '<th>Utilisateur</th>';
                            }
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            
                            foreach ($reservations as $reservation) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($reservation['date_reservation']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['creneau']) . '</td>';
                                echo '<td>' . ($reservation['Id_salle'] ? 'Salle' : 'Matériel') . '</td>';
                                echo '<td>';
                                $statut = htmlspecialchars($reservation['statut']);
                                $badgeClass = '';
                                switch($statut) {
                                    case 'En attente':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'Refusée':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    case 'Acceptée':
                                        $badgeClass = 'bg-success';
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                                
                                if ($isAdmin && $statut === 'En attente') {
                                    echo '<form method="post" style="display:inline-flex;gap:5px;">';
                                    echo '<input type="hidden" name="reservation_id" value="' . $reservation['Id_reservation'] . '">';
                                    echo '<button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">Accepter</button>';
                                    echo '<button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>';
                                    echo '</form>';
                                } else {
                                    echo '<span class="badge ' . $badgeClass . '">' . $statut . '</span>';
                                }
                                echo '</td>';
                                if ($isAdmin) {
                                    echo '<td>' . htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) . '</td>';
                                }
                                echo '</tr>';
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">Aucune réservation</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (!$isAdmin): ?>
    <footer class="pied-page">
        <div class="container">
            <div class="contenu-pied-page">
                <div class="colonne-footer">
                    <h3>IUT de Meaux</h3>
                    <div class="reseaux-sociaux">
                        <a href="https://www.facebook.com/UniversiteGustaveEiffel" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/c/Universit%C3%A9GustaveEiffel" target="_blank"><i
                                class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/universitegustaveeiffel" target="_blank"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/school/universit%C3%A9-gustave-eiffel/" target="_blank"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="colonne-footer">
                    <h3>Liens rapides</h3>
                    <ul class="list-unstyled">
                        <li><a href="./accueil.php">Accueil</a></li>
                        <li><a href="./reservation_materiels.php">Matériel</a></li>
                        <li><a href="./reservation_salles.php">Salles</a></li>
                        <li><a href="./mes_reservations.html">Réservations</a></li>
                        <li><a href="../../HTML/contact.html">Contact</a></li>
                    </ul>
                </div>

                <div class="colonne-footer">
                    <h3>Contact</h3>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt"></i> IUT de Marne-La-Vallée, Site de Meaux</li>
                        <li><i class="fas fa-phone"></i> 01 64 36 44 15</li>
                        <li><i class="fas fa-envelope"></i> contact@iut.fr</li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2025 Université Gustave Eiffel - IUT Marne-la-Vallée. Tous droits réservés.</p>
                <a href="../../HTML/mentionlegal.html">Mentions Legales</a>
                <a href="./conditionutilisation.php">Conditions d'utilisations</a>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    <script src="../../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>