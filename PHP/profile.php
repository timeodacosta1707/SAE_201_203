<?php
    session_start();
    if(!isset($_SESSION['data']['user'])) {
        header('Location: ../index.php');
        exit();
    }
    require_once 'connexion.php';
    
    $email = $_SESSION['data']['user'];

    $stmt = $pdo->prepare("
        SELECT p.*, e.adresse, e.groupe_td, e.groupe_tp, e.promotion 
        FROM Personne p 
        LEFT JOIN Etudiant e ON p.Id_user = e.Id_etudiant 
        WHERE p.email = :email
    ");
    $stmt->execute(['email' => $email]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        header('Location: ../index.php?erreur=2');
        exit();
    }
    
    function formatValue($value) {
        return ($value === null || $value === '') ? 'Non renseigné' : $value;
    }
    
    $user = [
        'nom' => formatValue($userData['nom']),
        'prenom' => formatValue($userData['prenom']),
        'email' => formatValue($userData['email']),
        'telephone' => formatValue($userData['telephone']),
        'formation' => formatValue($userData['promotion']),
        'annee' => formatValue($userData['groupe_td']),
    ];

    function generateInitialsAvatar($firstName, $lastName) {
        $firstInitial = mb_substr($firstName, 0, 1, 'UTF-8');
        $lastInitial = mb_substr($lastName, 0, 1, 'UTF-8');
        $initials = strtoupper($firstInitial . $lastInitial);
        $hash = md5($firstName . $lastName);
        $hue = hexdec(substr($hash, 0, 2)) % 360; 
        
        return [
            'initials' => $initials,
            'backgroundColor' => "hsl($hue, 65%, 50%)",
            'textColor' => "#ffffff"
        ];
    }

    $initialsAvatar = generateInitialsAvatar($user['prenom'], $user['nom']);
    $message = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $telephone = $_POST['telephone'];
        $formation = $_POST['formation'];
        $annee = $_POST['annee'];
        
        $stmt = $pdo->prepare("UPDATE Personne SET telephone = :telephone WHERE email = :email");
        $stmt->execute([
            'telephone' => $telephone,
            'email' => $email
        ]);
        
        $stmt = $pdo->prepare("UPDATE Etudiant SET promotion = :promotion, groupe_td = :groupe_td WHERE Id_etudiant = :id_etudiant");
        $stmt->execute([
            'promotion' => $formation === "" ? null : $formation,
            'groupe_td' => $annee === "" ? null : $annee,
            'id_etudiant' => $userData['Id_user']
        ]);
        
        $user['telephone'] = formatValue($telephone);
        $user['formation'] = formatValue($formation);
        $user['annee'] = formatValue($annee);        
        $message = '<div class="alert alert-success">Votre profil a été mis à jour avec succès!</div>';
    }

    if (isset($_GET['mdp_modif']) && $_GET['mdp_modif'] == 'true') {
        echo "<div class='alert alert-success'>Votre mot de passe a été modifié avec succès.</div>";
    }   
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Université Gustave Eiffel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/styleprofile.css">
    <link rel="icon" href="../images/logo.png">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="navbar-brand d-flex align-items-center">
                <img src="../images/Republique.png" alt="République Française" class="logo-republique">
                <img src="https://discret.univ-gustave-eiffel.fr/fileadmin/_processed_/b/4/csm_logo_univ_gustave_eiffel_rvb_e3ea850fc1.png"
                    alt="Université Gustave Eiffel" class="logo-universite">
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Matériel</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Salles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="../HTML/contact.html">Contact</a></li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <div class="icone-profil" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle"></i> Mon profil</a></li>
                            <li><a class="dropdown-item" href="./se_deconnecter.php"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <header class="profile-header">
        <div class="container">
            <h1>Mon Profil</h1>
        </div>
    </header>

    <section class="profile-content">
        <div class="container">
            <?php if (!empty($message)) echo $message; ?>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <div class="initials-avatar" style="background-color: <?php echo $initialsAvatar['backgroundColor']; ?>; color: <?php echo $initialsAvatar['textColor']; ?>">
                                <?php echo $initialsAvatar['initials']; ?>
                            </div>
                            <h2><?php echo $user['prenom'] . ' ' . $user['nom']; ?></h2>
                            <p><?php echo $user['formation'] . ' - ' . $user['annee']; ?></p>
                        </div>
                        <div class="profile-card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                                </button>
                                <a href="./mdp_oublie.php?from=profile" class="btn btn-outline-secondary">
                                    <i class="fas fa-key me-2"></i>Changer mon mot de passe
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h3><i class="fas fa-info-circle me-2"></i>Informations personnelles</h3>
                        </div>
                        <div class="profile-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Nom</b></div>
                                        <div class="profile-info-value"><?php echo $user['nom']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Prénom</b></div>
                                        <div class="profile-info-value"><?php echo $user['prenom']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Email</b></div>
                                        <div class="profile-info-value"><?php echo $user['email']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Téléphone</b></div>
                                        <div class="profile-info-value"><?php echo $user['telephone']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Formation</b></div>
                                        <div class="profile-info-value"><?php echo $user['formation']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Année</b></div>
                                        <div class="profile-info-value"><?php echo $user['annee']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-couliut text-white">
                    <h5 class="modal-title">Modifier mon profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" value="<?php echo $user['nom']; ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" value="<?php echo $user['prenom']; ?>" disabled>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>" disabled>
                                <small class="text-muted">L'email ne peut pas être modifié</small>
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                    value="<?php echo trim(str_replace(' ', '', $user['telephone'] === 'Non renseigné' ? '' : $user['telephone'])); ?>" 
                                    maxlength="10" 
                                    pattern="[0-9]{10}" 
                                    placeholder="0123456789"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)">
                                <small class="text-muted">Format: 10 chiffres sans espaces</small>                            
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="formation" class="form-label">Formation</label>
                                <select class="form-control" id="formation" name="formation">
                                    <option value="">-- Sélectionnez votre formation --</option>
                                    <option value="BUT MMI" <?php if($user['formation'] == 'BUT MMI') echo 'selected'; ?>>BUT MMI</option>
                                    <option value="BUT GEA" <?php if($user['formation'] == 'BUT GEA') echo 'selected'; ?>>BUT GEA</option>
                                    <option value="BUT TC" <?php if($user['formation'] == 'BUT TC') echo 'selected'; ?>>BUT TC</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="annee" class="form-label">Année</label>
                                <select class="form-control" id="annee" name="annee">
                                    <option value="">-- Sélectionnez votre année --</option>
                                    <option value="1ère année" <?php if($user['annee'] == '1ère année') echo 'selected'; ?>>1ère année</option>
                                    <option value="2ème année" <?php if($user['annee'] == '2ème année') echo 'selected'; ?>>2ème année</option>
                                    <option value="3ème année" <?php if($user['annee'] == '3ème année') echo 'selected'; ?>>3ème année</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class='fas fa-save'></i> Enregistrer
                        </button>
                    </div>
                </form>
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
						<li><a href="#">Matériel</a></li>
						<li><a href="#">Salles</a></li>
						<li><a href="#">Réservations</a></li>
						<li><a href="../HTML/contact.html">Contact</a></li>
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
				<a href="../HTML/mentionlegal.html">Mentions Legales</a>
				<a href="#">Conditions d'utilisations</a>
			</div>
		</div>
	</footer>

    <script src="../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>