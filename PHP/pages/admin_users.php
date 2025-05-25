<?php
    session_start();
    if(!isset($_SESSION['data']['user'])) {
        header('Location: ../../index.php');
        exit();
    }
    require_once '../traitement/connexion.php';
    require_once '../traitement/check_user.php';
    
    $isAdmin = isAdmin($pdo, $_SESSION['data']['user']);
    if (!$isAdmin) {
        header('Location: ./accueil.php');
        exit();
    }

    // Récupérer tous les utilisateurs
    $stmt = $pdo->prepare("
        SELECT p.*, 
               CASE
                   WHEN a.Id IS NOT NULL THEN 'agent'
                   WHEN adm.Id IS NOT NULL THEN 'admin'
                   WHEN e.Id IS NOT NULL THEN 'etudiant'
                   WHEN pr.Id IS NOT NULL THEN 'professeur'
               END as role,
               pr.date_embauche,
               e.promotion,
               e.annee,
               e.groupe_td,
               e.groupe_tp,
               e.numero_carte,
               p.date_naissance
        FROM Personne p
        LEFT JOIN Agent a ON p.Id_user = a.Id
        LEFT JOIN Administration adm ON p.Id_user = adm.Id
        LEFT JOIN Etudiant e ON p.Id_user = e.Id
        LEFT JOIN Professeur pr ON p.Id_user = pr.Id
        ORDER BY p.nom, p.prenom
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Université Gustave Eiffel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link rel="stylesheet" href="../../CSS/styleprofile.css">
    <link rel="stylesheet" href="../../CSS/admin.css">
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
                    <li class="nav-item">
                        <a class="nav-link" href="../../HTML/contact.html">Contact</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./gestion-salles-materiels.php">Matériels et Salles</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./mes_reservations.php">Réservations</a>
                    </li>
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

    <header class="admin-header">
        <div class="container">
            <h1>Administration des Utilisateurs</h1>
        </div>
    </header>

    <section class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="search-filter-section mb-5 mt-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="search-bar">
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un utilisateur...">
                        <button class="btn btn-search" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="roleFilter">
                            <option value="all">Tous les rôles</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="professeur">Professeur</option>
                            <option value="agent">Agent</option>
                            <option value="admin">Administrateur</option>
                        </select>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus"></i> Ajouter un utilisateur
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="users-table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                        <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge-admin">Administrateur</span>
                            <?php elseif ($user['role'] === 'professeur'): ?>
                                <span class="badge-professeur">Professeur</span>
                            <?php elseif ($user['role'] === 'agent'): ?>
                                <span class="badge-agent">Agent</span>
                            <?php elseif ($user['role'] === 'etudiant'): ?>
                                <span class="badge-etudiant">Étudiant</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if ($user['Id_user'] !== $_SESSION['data']['user'] && $user['role'] !== 'admin'): ?>
                                <button class="editBtn" onclick="editUser(<?php 
                                    echo $user['Id_user']; ?>, 
                                    '<?php echo htmlspecialchars($user['nom']); ?>', 
                                    '<?php echo htmlspecialchars($user['prenom']); ?>', 
                                    '<?php echo htmlspecialchars($user['email']); ?>',
                                    '<?php echo $user['role']; ?>',
                                    '<?php echo htmlspecialchars($user['date_embauche'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['annee'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['groupe_td'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['groupe_tp'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['promotion'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['date_naissance'] ?? ''); ?>',
                                    '<?php echo htmlspecialchars($user['numero_carte'] ?? ''); ?>'
                                )">
                                    <svg height="1em" viewBox="0 0 512 512">
                                        <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </button>
                                <?php if ($isAdmin && $user['role'] !== 'admin'): ?>
                                <button class="bin-button" onclick="deleteUser(<?php echo $user['Id_user']; ?>)">
                                    <svg class="bin-top" viewBox="0 0 39 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line>
                                        <line x1="12" y1="1.5" x2="26.0357" y2="1.5" stroke="white" stroke-width="3"></line>
                                    </svg>
                                    <svg class="bin-bottom" viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="path-1-inside-1_8_19" fill="white">
                                            <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                                        </mask>
                                        <path d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z" fill="white" mask="url(#path-1-inside-1_8_19)"></path>
                                        <path d="M12 6L12 29" stroke="white" stroke-width="4"></path>
                                        <path d="M21 6V29" stroke="white" stroke-width="4"></path>
                                    </svg>
                                </button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Ajout Utilisateur -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Ajouter un nouvel utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="../traitement/add_user.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="prenom">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Rôle</label>
                            <select class="form-control" id="role" name="role" required onchange="toggleFields()">
                                <option value="professeur">Professeur</option>
                                <option value="agent">Agent</option>
                            </select>
                        </div>

                        <div id="professeur-fields">
                            <div class="form-group mb-3">
                                <label for="date_embauche">Date d'embauche</label>
                                <input type="date" class="form-control" id="date_embauche" name="date_embauche">
                            </div>
                        </div>

                        <div id="agent-fields" style="display: none;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="addUserForm" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Utilisateur -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="../traitement/edit_user.php" method="POST">
                        <input type="hidden" name="userId" id="editUserId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="editNom">Nom</label>
                                    <input type="text" class="form-control" id="editNom" name="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="editPrenom">Prénom</label>
                                    <input type="text" class="form-control" id="editPrenom" name="prenom" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="editEmail">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="editTelephone">Téléphone</label>
                                    <input type="tel" class="form-control" id="editTelephone" name="telephone" 
                                        maxlength="10" pattern="[0-9]{10}" placeholder="0123456789">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editPassword">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>

                        <div class="form-group mb-3">
                            <label for="editRole">Rôle</label>
                            <select class="form-control" id="editRole" name="role" required onchange="toggleEditFields()">
                                <option value="etudiant">Étudiant</option>
                                <option value="professeur">Professeur</option>
                                <option value="agent">Agent</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>

                        <!-- Champs spécifiques aux étudiants -->
                        <div id="edit-etudiant-fields">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editDateNaissance">Date de naissance</label>
                                        <input type="date" class="form-control" id="editDateNaissance" name="date_naissance">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editNumeroCarte">Numéro de carte</label>
                                        <input type="text" class="form-control" id="editNumeroCarte" name="numero_carte" 
                                            maxlength="6" pattern="[0-9]{6}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editFormation">Formation</label>
                                        <select class="form-control" id="editFormation" name="formation">
                                            <option value="">-- Sélectionnez la formation --</option>
                                            <option value="BUT MMI">BUT MMI</option>
                                            <option value="BUT GEA">BUT GEA</option>
                                            <option value="BUT TC">BUT TC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editAnnee">Année</label>
                                        <select class="form-control" id="editAnnee" name="annee">
                                            <option value="">-- Sélectionnez l'année --</option>
                                            <option value="1ère année">1ère année</option>
                                            <option value="2ème année">2ème année</option>
                                            <option value="3ème année">3ème année</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editGroupeTD">Groupe TD</label>
                                        <select class="form-control" id="editGroupeTD" name="groupe_td">
                                            <option value="">-- Sélectionnez le TD --</option>
                                            <option value="TD1">TD1</option>
                                            <option value="TD2">TD2</option>
                                            <option value="TD3">TD3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="editGroupeTP">Groupe TP</label>
                                        <select class="form-control" id="editGroupeTP" name="groupe_tp">
                                            <option value="">-- Sélectionnez le TP --</option>
                                            <option value="TPA">TPA</option>
                                            <option value="TPB">TPB</option>
                                            <option value="TPC">TPC</option>
                                            <option value="TPD">TPD</option>
                                            <option value="TPE">TPE</option>
                                            <option value="TPF">TPF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques aux professeurs -->
                        <div id="edit-professeur-fields">
                            <div class="form-group mb-3">
                                <label for="editDateEmbauche">Date d'embauche</label>
                                <input type="date" class="form-control" id="editDateEmbauche" name="date_embauche">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="editUserForm" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../JS/navbar.js"></script>
    <script src="../../JS/admin.js"></script>
</body>
</html> 