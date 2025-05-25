<?php
session_start();
if(!isset($_SESSION['data']['user'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../traitement/connexion.php';
require_once '../traitement/check_user.php';
$isAdmin = isAdmin($pdo, $_SESSION['data']['user']);

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
$name = $_GET['name'] ?? '';

if (!$type || !$id || !$name) {
    header('Location: ' . ($type === 'salle' ? 'reservation_salles.php' : 'reservation_materiels.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - <?= htmlspecialchars($name) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="../../CSS/reservation_form.css">
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
                    <?php if (!$isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./accueil.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_materiels.php">Matériel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_salles.php">Salles</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./gestion-salles-materiels.php">Matériels et Salles</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./mes_reservations.php">Réservations</a>
                    </li>
                    <?php if (!$isAdmin): ?>
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

    <section class="banniere">
        <div class="container">
            <h1>Réservation de <?= htmlspecialchars($name) ?></h1>
            <p>Choisissez une date et une heure pour votre réservation</p>
        </div>
    </section>

    <div class="container reservation-container">
        <div class="row">
            <div class="col-md-6">
                <div class="calendar-container">
                    <div class="selection-hint">
                        <i class="fas fa-info-circle"></i>
                        <?php if ($type === 'materiel'): ?>
                            Cliquez et faites glisser pour sélectionner un créneau sur plusieurs jours (max 1 semaine)
                        <?php else: ?>
                            Cliquez et faites glisser ou remplissez le formulaire pour sélectionner un créneau horaire
                        <?php endif; ?>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="mb-4">Réservation de <?= htmlspecialchars($name) ?></h2>
                    <form id="formReservation">
                        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        
                        <?php if ($type === 'materiel'): ?>
                        <div class="mb-3">
                            <label for="dateDebut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="dateDebut" name="dateDebut" required>
                            <div class="invalid-feedback" id="dateDebutError">
                                La réservation doit être faite au moins 24h à l'avance
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="dateFin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="dateFin" name="dateFin" required>
                            <div class="invalid-feedback" id="dateFinError">
                                La réservation doit être faite au moins 24h à l'avance
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="mb-3">
                            <label for="dateReservation" class="form-label">Date de réservation</label>
                            <input type="date" class="form-control" id="dateReservation" name="dateReservation" required>
                            <div class="invalid-feedback" id="dateReservationError">
                                La réservation doit être faite au moins 24h à l'avance
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="heureDebut" class="form-label">Heure de début</label>
                            <input type="time" class="form-control" id="heureDebut" name="heureDebut" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="heureFin" class="form-label">Heure de fin</label>
                            <input type="time" class="form-control" id="heureFin" name="heureFin" required>
                        </div>
                        
                        <?php if ($type === 'materiel'): ?>
                        <div class="mb-3">
                            <label for="quantiteReservation" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="quantiteReservation" name="quantiteReservation" min="1" value="1" required>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="motifReservation" class="form-label">Motif de réservation</label>
                            <textarea class="form-control" id="motifReservation" name="motifReservation" rows="3" required></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="<?= $type === 'salle' ? 'reservation_salles.php' : 'reservation_materiels.php' ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-couliut">Réserver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation de réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir effectuer cette réservation ?</p>
                    <div class="reservation-details">
                        <p><strong>Date de début :</strong> <span id="modalDateDebut"></span></p>
                        <p><strong>Date de fin :</strong> <span id="modalDateFin"></span></p>
                        <p><strong>Heure de début :</strong> <span id="modalHeureDebut"></span></p>
                        <p><strong>Heure de fin :</strong> <span id="modalHeureFin"></span></p>
                        <?php if ($type === 'materiel'): ?>
                        <p><strong>Quantité :</strong> <span id="modalQuantite"></span></p>
                        <?php endif; ?>
                        <p><strong>Motif :</strong> <span id="modalMotif"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-couliut" id="confirmReservation">Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="pied-page">
        <div class="container">
            <div class="contenu-pied-page">
                <div class="colonne-footer">
                    <h3>IUT de Meaux</h3>
                    <div class="reseaux-sociaux">
                        <a href="https://www.facebook.com/UniversiteGustaveEiffel" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/c/Universit%C3%A9GustaveEiffel" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/universitegustaveeiffel" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/school/universit%C3%A9-gustave-eiffel/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="colonne-footer">
                    <h3>Liens rapides</h3>
                    <ul class="list-unstyled">
                        <li><a href="./accueil.php">Accueil</a></li>
                        <li><a href="./reservation_materiels.php">Matériel</a></li>
                        <li><a href="./reservation_salles.php">Salles</a></li>
                        <li><a href="./mes_reservations.php">Réservations</a></li>
                        <li><a href="../../HTML/contact.html">Contact</a></li>
                    </ul>
                </div>

                <div class="colonne-footer">
                    <h3>Contact</h3>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt"></i> IUT de Marne-La-Vallée, Site de Meaux</li>
                        <li><i class="fas fa-phone"></i> 01 64 36 44 10</li>
                        <li><i class="fas fa-envelope"></i> mmi-meaux-dir.iut@univ-eiffel.fr</li>
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

    <script src="../../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    <script src="../../JS/reservation.js"></script>
    <script>
        // Initialisation des données pour le calendrier
        document.getElementById('calendar').dataset.type = '<?= $type ?>';
    </script>
</body>
</html>