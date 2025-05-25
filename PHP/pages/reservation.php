<?php
	session_start();
	if(!isset($_SESSION['data']['user'])) {
		header('Location: ../../index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Réservation - Université Gustave Eiffel</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
	<link rel="stylesheet" href="../../CSS/footer.css">
	<link rel="stylesheet" href="../../CSS/stylereservation.css">
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
                    <li class="nav-item">
                        <a class="nav-link" href="./accueil.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_materiels.php">Matériel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_salles.php">Salles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./mes_reservations.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../HTML/contact.html">Contact</a>
                    </li>
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
			<h1>Effectuez <span>votre</span> réservation</h1>
			<p>Réservez facilement du matériel ou des salles pour vos projets</p>
		</div>
	</section>

	<section class="reservation-options py-5">
		<div class="container">
			<h2 class="titre-section">Que souhaitez-vous réserver ?</h2>
			
			<div class="row g-4 mt-3">
				<div class="col-md-6">
					<div class="card h-100 option-card">
						<div class="card-body text-center p-5">
							<div class="icon-container mb-4">
								<i class="fas fa-video fa-4x"></i>
							</div>
							<h3 class="card-title mb-4">Matériel</h3>
							<p class="card-text mb-4">Réservez des caméras, microphones, ordinateurs et autres équipements pour vos projets académiques.</p>
							<a href="./reservation_materiels.php" class="btn bg-couliut btn-lg rounded-pill px-4 custom-hover">Réserver du matériel</a>
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="card h-100 option-card">
						<div class="card-body text-center p-5">
							<div class="icon-container mb-4">
								<i class="fas fa-chalkboard-teacher fa-4x"></i>
							</div>
							<h3 class="card-title mb-4">Salles</h3>
							<p class="card-text mb-4">Réservez des salles de classe, studios ou espaces de travail pour vos cours et réunions.</p>
							<a href="./reservation_salles.php" class="btn bg-couliut btn-lg rounded-pill px-4 custom-hover">Réserver une salle</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="faq py-5 bg-light">
		<div class="container">
			<h2 class="titre-section">Questions fréquentes</h2>
			
			<div class="row mt-4">
				<div class="col-lg-8 mx-auto">
					<div class="accordion" id="faqAccordion">
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
									Combien de temps à l'avance dois-je réserver ?
								</button>
							</h2>
							<div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Nous recommandons de réserver au moins 48 heures à l'avance pour garantir la disponibilité du matériel ou des salles. Pour les équipements spécialisés, une réservation une semaine à l'avance est conseillée.
								</div>
							</div>
						</div>
						
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
									Quelle est la durée maximale d'une réservation ?
								</button>
							</h2>
							<div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									La durée maximale standard est de 7 jours pour le matériel et de 4 heures pour les salles. Des extensions peuvent être accordées selon la disponibilité et avec une justification pédagogique.
								</div>
							</div>
						</div>
						
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
									Comment puis-je annuler une réservation ?
								</button>
							</h2>
							<div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Vous pouvez annuler votre réservation en vous connectant à votre compte et en accédant à la section "Mes réservations". Les annulations doivent être effectuées au moins 24 heures avant l'heure de réservation prévue.
								</div>
							</div>
						</div>
						
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
									Que faire en cas de problème avec le matériel ?
								</button>
							</h2>
							<div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									En cas de problème technique, contactez immédiatement le service technique au 01 64 36 44 XX ou signalez le problème via l'application. Ne tentez pas de réparer l'équipement vous-même.
								</div>
							</div>
						</div>
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
						<li><i class="fas fa-envelope"></i>contact@iut.fr</li>
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