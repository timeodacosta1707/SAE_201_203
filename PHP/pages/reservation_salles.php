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
    // Redirect agents to reservations page
    if ($isAdmin || $isAgent) {
        header('Location: ./mes_reservations.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de salles - Étudiants</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link rel="stylesheet" href="../../CSS/stylesalles.css">
    <link rel="stylesheet" href="../../CSS/styleresms.css">
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
                        <a class="nav-link" href="./accueil.php">Accueil</a>
                    </li>
                    <?php if(!$isProfesseur): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_materiels.php">Matériel</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salles</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./gestion-salles-materiels.php">Matériels et Salles</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./mes_reservations.php">Réservations</a>
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
        <section class="banniere">
            <div class="container">
                <h1>Réservation de salles</h1>
                <p>Réservez une salle pour vos études ou travaux de groupe</p>
            </div>
        </section>
        <main class="container my-5">
            <div class="search-filter-section mb-5">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une salle...">
                            <button class="btn btn-search" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="listeSalles">
                <?php
                // Récupérer toutes les salles
                $stmt = $pdo->query("SELECT * FROM Salle ORDER BY nom");
                $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($salles) > 0) {
                    foreach ($salles as $salle) {
                        $id = $salle['Id_salle'];
                        $nom = htmlspecialchars($salle['nom']);
                        $description = htmlspecialchars($salle['description']);
                        $capacite = htmlspecialchars($salle['capacite']);
                        $image = htmlspecialchars($salle['image']);
                        ?>
                        <div class="col material-item" data-name="<?php echo $nom; ?>">
                            <div class="card h-100">
                                <div class="status-badge available">Disponible</div>
                                <img src="../../<?php echo $image; ?>" class="card-img-top" alt="<?php echo $nom; ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $nom; ?></h5>
                                    <p class="card-text"><?php echo $description; ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-tag me-1"></i>Salle
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-users me-1"></i>Capacité: <?php echo $capacite; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="reservation_form.php?type=salle&id=<?php echo $id; ?>&name=<?php echo urlencode($nom); ?>" class="btn btn-reserve w-100">
                                        <i class="fas fa-calendar-check me-1"></i>Réserver
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center"><p>Aucune salle disponible</p></div>';
                }
                ?>
            </div>

            <div id="noResults" class="text-center my-5 d-none">
                <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                <h3>Aucune salle trouvée</h3>
                <p class="text-muted">Essayez de modifier vos critères de recherche</p>
            </div>
        </main>

        <div class="modal fade" id="modalReservation" tabindex="-1" aria-labelledby="modalReservationLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalReservationLabel">Réserver une salle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="calendar"></div>
                            </div>
                            <div class="col-md-6">
                                <form id="formReservation">
                                    <input type="hidden" id="salleId" name="salleId">
                                    
                                    <div class="mb-3">
                                        <h5 id="salleNom" class="fw-bold"></h5>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="dateReservation" class="form-label">Date de réservation</label>
                                        <input type="date" class="form-control" id="dateReservation" name="dateReservation" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="heureDebut" class="form-label">Heure de début</label>
                                        <input type="time" class="form-control" id="heureDebut" name="heureDebut" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="heureFin" class="form-label">Heure de fin</label>
                                        <input type="time" class="form-control" id="heureFin" name="heureFin" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="motifReservation" class="form-label">Motif de réservation</label>
                                        <textarea class="form-control" id="motifReservation" name="motifReservation" rows="3" required></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" onclick="submitReservation()">Réserver</button>
                    </div>
                </div>
            </div>
        </div>

        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
        <script src="../../JS/reservation-salles.js"></script>
        <script src="../../JS/navbar.js"></script>
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
                        <li><a href="./mes_reservations.html">Réservations</a></li>
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
</body>
</html>