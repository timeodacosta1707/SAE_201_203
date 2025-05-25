<?php
    session_start();
    if(!isset($_SESSION['data']['user'])) {
        header('Location: ../../index.php');
        exit();
    }
    require_once '../traitement/connexion.php';
    require_once '../traitement/check_user.php';
    $isAdmin = isAdmin($pdo, $_SESSION['data']['user']);
    $isProfesseur = isProfesseur($pdo, $_SESSION['data']['user']);
    $isAgent = isAgent($pdo, $_SESSION['data']['user']);
    
    $email = $_SESSION['data']['user'];

    $stmt = $pdo->prepare("
        SELECT p.*, e.annee, e.groupe_td, e.groupe_tp, e.promotion, e.numero_carte,
               pr.date_embauche,
               CASE WHEN a.Id IS NOT NULL THEN 1 ELSE 0 END as is_admin,
               CASE WHEN pr.Id IS NOT NULL THEN 1 ELSE 0 END as is_professeur
        FROM Personne AS p
        LEFT JOIN Etudiant AS e ON p.Id_user = e.Id
        LEFT JOIN Administration AS a ON p.Id_user = a.Id
        LEFT JOIN Professeur AS pr ON p.Id_user = pr.Id
        WHERE p.email = :email
    ");
    $stmt->execute(['email' => $email]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userData) {
        header('Location: ../../index.php?erreur=2');
        exit();
    }
    
    function formatValue($value) {
        return ($value === null || $value === '') ? 'Non renseigné' : $value;
    }
    
    $isAdmin = $userData['is_admin'] == 1;
    $isProfesseur = $userData['is_professeur'] == 1;
    
    $user = [
        'nom' => formatValue($userData['nom']),
        'prenom' => formatValue($userData['prenom']),
        'email' => formatValue($userData['email']),
        'telephone' => formatValue($userData['telephone']),
        'formation' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['promotion']),
        'annee' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['annee']),
        'groupe_td' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['groupe_td']),
        'groupe_tp' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['groupe_tp']),
        'date_embauche' => $isProfesseur ? formatValue($userData['date_embauche']) : 'Non renseigné',
        'date_naissance' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['date_naissance']),
        'numero_carte' => $isAdmin || $isProfesseur ? 'Non renseigné' : formatValue($userData['numero_carte'])
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
        $professeurUpdated = false;
        $personneUpdated = false;
        $etudiantUpdated = false;
        
        if (!$isProfesseur) {
            $telephone = $_POST['telephone'];
            $stmt = $pdo->prepare("UPDATE Personne SET telephone = :telephone WHERE email = :email");
            $stmt->execute([
                'telephone' => $telephone,
                'email' => $email
            ]);
            $personneUpdated = $stmt->rowCount() > 0;
        }
        
        if ($isProfesseur) {
            $date_embauche = $_POST['date_embauche'];
            
            $stmt = $pdo->prepare("UPDATE Professeur SET date_embauche = :date_embauche WHERE Id = :id_professeur");
            $stmt->execute([
                'date_embauche' => $date_embauche,
                'id_professeur' => $userData['Id_user']
            ]);
            $professeurUpdated = $stmt->rowCount() > 0;
        } elseif (!$isAdmin) {
            $formation = $_POST['formation'];
            $groupe_td = $_POST['groupe_td'];
            $groupe_tp = $_POST['groupe_tp'];
            $annee = $_POST['annee'];
            
            $stmt = $pdo->prepare("UPDATE Etudiant SET promotion = :promotion, groupe_td = :groupe_td, groupe_tp = :groupe_tp, annee = :annee WHERE Id = :id_etudiant");
            $stmt->execute([
                'promotion' => $formation === "" ? null : $formation,
                'groupe_td' => $groupe_td === "" ? null : $groupe_td,
                'groupe_tp' => $groupe_tp === "" ? null : $groupe_tp,
                'annee' => $annee === "" ? null : $annee,
                'id_etudiant' => $userData['Id_user']
            ]);
            $etudiantUpdated = $stmt->rowCount() > 0;
        }
        
        if ($isProfesseur) {
            if (!$professeurUpdated) {
                $message = '<div class="alert alert-warning">Aucune modification n\'a été effectuée.</div>';
            } else {
                $user['date_embauche'] = formatValue($date_embauche);
                $message = '<div class="alert alert-success">Votre profil a été mis à jour avec succès!</div>';
            }
        } else if (!$personneUpdated && (!$isAdmin && !$isProfesseur && !$etudiantUpdated)) {
            $message = '<div class="alert alert-warning">Aucune modification n\'a été effectuée.</div>';
        } else if ($personneUpdated || $etudiantUpdated) {
            if (!$isProfesseur) {
                $user['telephone'] = formatValue($telephone);
            }
            if (!$isAdmin) {
                $user['formation'] = formatValue($formation);
                $user['groupe_td'] = formatValue($groupe_td);
                $user['groupe_tp'] = formatValue($groupe_tp);
                $user['annee'] = formatValue($annee);
            }
            $message = '<div class="alert alert-success">Votre profil a été mis à jour avec succès!</div>';
        }
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
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link rel="stylesheet" href="../../CSS/styleprofile.css">
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
                        <a class="nav-link" href="./accueil.php">Accueil</a>
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
                                <a class="dropdown-item" href="#">
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
                                <a class="dropdown-item" href="#">
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
                            <p><?php 
                                if ($isAdmin) {
                                    echo "Administrateur";
                                } elseif ($isProfesseur) {
                                    echo "Professeur";
                                } else {
                                    echo $user['formation'] . ' - ' . $user['annee'];
                                }
                            ?></p>
                        </div>
                        <div class="profile-card-body">
                            <div class="d-grid gap-2">
                                <?php if (!$isAdmin): ?>
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                                </button>
                                <?php endif; ?>
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
                                <?php if ($isAdmin): ?>
                                <div class="col-md-12">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Pseudonyme</b></div>
                                        <div class="profile-info-value"><?php echo $user['prenom'] . ' ' . $user['nom']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Email</b></div>
                                        <div class="profile-info-value"><?php echo $user['email']; ?></div>
                                    </div>
                                </div>
                                <?php elseif ($isProfesseur): ?>
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
                                        <div class="profile-info-label"><b>Date d'embauche</b></div>
                                        <div class="profile-info-value"><?php echo $user['date_embauche']; ?></div>
                                    </div>
                                </div>
                                <?php else: ?>
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
                                        <div class="profile-info-label"><b>Numéro de carte</b></div>
                                        <div class="profile-info-value"><?php echo $user['numero_carte']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Date de naissance</b></div>
                                        <div class="profile-info-value"><?php echo $user['date_naissance']; ?></div>
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
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Groupe TD</b></div>
                                        <div class="profile-info-value"><?php echo $user['groupe_td']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="profile-info-item">
                                        <div class="profile-info-label"><b>Groupe TP</b></div>
                                        <div class="profile-info-value"><?php echo $user['groupe_tp']; ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                            <?php if ($isProfesseur): ?>
                            <div class="col-md-6">
                                <label for="date_embauche" class="form-label">Date d'embauche</label>
                                <input type="date" class="form-control" id="date_embauche" name="date_embauche" value="<?php echo $user['date_embauche']; ?>">
                            </div>
                            <?php else: ?>
                            <div class="col-md-6">
                                <label for="numero_carte" class="form-label">Numéro de carte</label>
                                <input type="text" class="form-control" id="numero_carte" name="numero_carte" value="<?php echo $user['numero_carte']; ?>" disabled>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!$isAdmin && !$isProfesseur): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $user['date_naissance']; ?>" disabled>
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
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="groupe_td" class="form-label">Groupe TD</label>
                                <select class="form-control" id="groupe_td" name="groupe_td">
                                    <option value="">-- Sélectionnez votre TD --</option>
                                    <option value="TD1" <?php if($user['groupe_td'] == 'TD1') echo 'selected'; ?>>TD1</option>
                                    <option value="TD2" <?php if($user['groupe_td'] == 'TD2') echo 'selected'; ?>>TD2</option>
                                    <option value="TD3" <?php if($user['groupe_td'] == 'TD3') echo 'selected'; ?>>TD3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="groupe_tp" class="form-label">Groupe TP</label>
                                <select class="form-control" id="groupe_tp" name="groupe_tp">
                                    <option value="">-- Sélectionnez votre TP --</option>
                                    <option value="TPA" <?php if($user['groupe_tp'] == 'TPA') echo 'selected'; ?>>TPA</option>
                                    <option value="TPB" <?php if($user['groupe_tp'] == 'TPB') echo 'selected'; ?>>TPB</option>
                                    <option value="TPC" <?php if($user['groupe_tp'] == 'TPC') echo 'selected'; ?>>TPC</option>
                                    <option value="TPD" <?php if($user['groupe_tp'] == 'TPD') echo 'selected'; ?>>TPD</option>
                                    <option value="TPE" <?php if($user['groupe_tp'] == 'TPE') echo 'selected'; ?>>TPE</option>
                                    <option value="TPF" <?php if($user['groupe_tp'] == 'TPF') echo 'selected'; ?>>TPF</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
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
<?php if (!$isAdmin): ?>
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
<?php endif; ?>
    <script src="../../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>