<?php
session_start();
if(!isset($_SESSION['data']['user'])) {
    header('Location: ../../index.php');
    exit();
}
require_once '../traitement/connexion.php';
require_once '../traitement/check_admin.php';
$isAdmin = isAdmin($pdo, $_SESSION['data']['user']);

if (!$isAdmin) {
    header('Location: ./accueil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Salles et Matériels - Université Gustave Eiffel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link rel="icon" href="../../images/logo.png">
    <style>
        .container {
            margin-top: 100px;
            margin-bottom: 50px;
        }
        .section-title {
            margin-bottom: 30px;
            color: var(--couleur-primaire);
        }
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: var(--couleur-primaire);
            color: white;
        }
        .btn-action {
            margin-right: 5px;
        }
    </style>
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
                        <a class="nav-link" href="./gestion-salles-materiels.php">Matériels et Salles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./mes_reservations.html">Réservations</a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin_users.php">Administration</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="section-title">Gestion des Salles et Matériels</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Gestion des Salles</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary btn-action">Ajouter une salle</button>
                        <button class="btn btn-info btn-action">Modifier une salle</button>
                        <button class="btn btn-danger btn-action">Supprimer une salle</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Gestion du Matériel</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary btn-action">Ajouter du matériel</button>
                        <button class="btn btn-info btn-action">Modifier du matériel</button>
                        <button class="btn btn-danger btn-action">Supprimer du matériel</button>
                    </div>
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
				<a href="../../HTML/conditionutilisation.html">Conditions d'utilisations</a>
			</div>
		</div>
	</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 