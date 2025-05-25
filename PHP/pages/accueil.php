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
	<title>Université Gustave Eiffel - Système de Réservation</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../../CSS/styleaccueil.css">
	<link rel="stylesheet" href="../../CSS/navbar.css">
	<link rel="stylesheet" href="../../CSS/footer.css">
	<link rel="stylesheet" href="../../CSS/commentmarche.css">
	<link rel="icon" href="../../images/logo.png">
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
                        <a class="nav-link" href="#">Accueil</a>
                    </li>
					<?php if (!$isProfesseur): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_materiels.php">Matériel</a>
                    </li>
					<?php endif; ?>
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
			<h1>Réservez <span>votre</span> matériel</h1>
			<p>Caméras, micros, salles de classe et bien plus</p>
			<a href="./reservation.php" class="btn bg-couliut btn-lg rounded-pill px-4 custom-hover">Réserver maintenant</a>
		</div>
	</section>

	<?php if (!$isProfesseur): ?>
	<section id="services" class="services">
		<div class="container">
			<h2 class="titre-section">Nos catégories de matériel</h2>
			<div class="row g-4">
				<div class="col-md-6 col-lg-3">
					<div class="card h-100 carte-service">
						<div class="image-service cameras d-flex align-items-center justify-content-center">
							<i class="fas fa-video"></i>
						</div>
						<div class="card-body">
							<h3 class="card-title">Caméras</h3>
							<p class="card-text">Caméras professionnelles, appareils photo et accessoires pour vos
								projets multimédias.</p>
							<a href="#" class="lien-service">Voir les disponibilités →</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3">
					<div class="card h-100 carte-service">
						<div class="image-service audio d-flex align-items-center justify-content-center">
							<i class="fas fa-microphone"></i>
						</div>
						<div class="card-body">
							<h3 class="card-title">Microphones</h3>
							<p class="card-text">Micros cravate, micros main, enregistreurs et équipement audio
								professionnel.</p>
							<a href="#" class="lien-service">Voir les disponibilités →</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3">
					<div class="card h-100 carte-service">
						<div class="image-service salles d-flex align-items-center justify-content-center">
							<i class="fas fa-chalkboard-teacher"></i>
						</div>
						<div class="card-body">
							<h3 class="card-title">Salles de classe</h3>
							<p class="card-text">Salles équipées pour les cours, les réunions ou les événements avec matériel
								inclus.</p>
							<a href="#" class="lien-service">Voir les disponibilités →</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-3">
					<div class="card h-100 carte-service">
						<div class="image-service tablettes d-flex align-items-center justify-content-center">
							<i class="fas fa-laptop"></i>
						</div>
						<div class="card-body">
							<h3 class="card-title">Autres équipements</h3>
							<p class="card-text">Ordinateurs, tablettes, projecteurs et tout le matériel nécessaire pour
								vos études.</p>
							<a href="#" class="lien-service">Voir les disponibilités →</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<section class="reservation-process py-5 bg-light">
		<div class="container">
			<h2 class="titre-section">Comment ça marche ?</h2>
			
			<div class="row g-4 mt-3">
				<div class="col-md-3">
					<div class="process-step text-center">
						<div class="step-number">1</div>
						<div class="step-icon">
							<i class="fas fa-search"></i>
						</div>
						<h4>Choisissez</h4>
						<p>Sélectionnez <?php echo $isProfesseur ? "la salle" : "le type de matériel ou la salle" ?> dont vous avez besoin</p>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="process-step text-center">
						<div class="step-number">2</div>
						<div class="step-icon">
							<i class="fas fa-calendar-alt"></i>
						</div>
						<h4>Sélectionnez</h4>
						<p>Choisissez la date et l'heure de votre réservation</p>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="process-step text-center">
						<div class="step-number">3</div>
						<div class="step-icon">
							<i class="fas fa-clipboard-check"></i>
						</div>
						<h4>Confirmez</h4>
						<p>Vérifiez les détails et confirmez votre réservation</p>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="process-step text-center">
						<div class="step-number">4</div>
						<div class="step-icon">
							<i class="fas fa-check-circle"></i>
						</div>
						<h4>Récupérez</h4>
						<p><?php echo $isProfesseur ? "Accédez" : "Récupérez votre matériel ou accédez" ?> à votre salle à la date prévue de votre réservation</p>
					</div>
				</div>
			</div>
		</div>
	</section>

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
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../../JS/navbar.js"></script>
</body>

</html>
