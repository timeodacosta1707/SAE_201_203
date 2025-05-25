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
    <link rel="stylesheet" href="../../CSS/stylereservation.css">
    <link rel="stylesheet" href="../../CSS/styleresms.css">
    <link rel="stylesheet" href="../../CSS/gestion.css">
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
            <h1>Gestion des Salles et Matériels</h1>
        </div>
    </header>

    <div class="container mt-4">        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Gestion des Salles</h5>
                    </div>
                    <div class="card-body">
                        <button class="icon-btn add-btn" onclick="openAddSalleModal()">
                            <div class="add-icon"></div>
                            <div class="btn-txt">Ajouter une salle</div>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Gestion du Matériel</h5>
                    </div>
                    <div class="card-body">
                        <button class="icon-btn add-btn" onclick="openAddMaterielModal()">
                            <div class="add-icon"></div>
                            <div class="btn-txt">Ajouter du matériel</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des salles -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Liste des salles</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="sallesTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Capacité</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM Salle ORDER BY nom");
                            while ($salle = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr data-id='" . $salle['Id_salle'] . "'>";
                                echo "<td><img src='../../" . (isset($salle['image']) ? htmlspecialchars($salle['image']) : 'images/default.jpg') . "' alt='" . (isset($salle['nom']) ? htmlspecialchars($salle['nom']) : 'Salle sans nom') . "' style='width: 50px; height: 50px; object-fit: cover;'></td>";
                                echo "<td>" . (isset($salle['nom']) ? htmlspecialchars($salle['nom']) : '') . "</td>";
                                echo "<td>" . (isset($salle['capacite']) ? htmlspecialchars($salle['capacite']) : '') . "</td>";
                                echo "<td>" . (isset($salle['description']) ? htmlspecialchars($salle['description']) : '') . "</td>";
                                echo "<td>
                                    <div class='action-buttons'>
                                        <button class='editBtn' onclick='editSalle(" . $salle['Id_salle'] . ")'>
                                            <svg height='1em' viewBox='0 0 512 512'>
                                                <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                            </svg>
                                        </button>
                                        <button class='bin-button' onclick='deleteSalle(" . $salle['Id_salle'] . ")'>
                                            <svg class='bin-top' viewBox='0 0 39 7' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                <line y1='5' x2='39' y2='5' stroke='white' stroke-width='4'></line>
                                                <line x1='12' y1='1.5' x2='26.0357' y2='1.5' stroke='white' stroke-width='3'></line>
                                            </svg>
                                            <svg class='bin-bottom' viewBox='0 0 33 39' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                <mask id='path-1-inside-1_8_19' fill='white'>
                                                    <path d='M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z'></path>
                                                </mask>
                                                <path d='M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z' fill='white' mask='url(#path-1-inside-1_8_19)'></path>
                                                <path d='M12 6L12 29' stroke='white' stroke-width='4'></path>
                                                <path d='M21 6V29' stroke='white' stroke-width='4'></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tableau des matériels -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Liste des matériels</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="materielsTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Quantité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM Materiel ORDER BY nom");
                            while ($materiel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr data-id='" . $materiel['Id_materiel'] . "'>";
                                echo "<td><img src='../../" . (isset($materiel['image']) ? htmlspecialchars($materiel['image']) : 'images/default.jpg') . "' alt='" . (isset($materiel['nom']) ? htmlspecialchars($materiel['nom']) : 'Matériel sans nom') . "' style='width: 50px; height: 50px; object-fit: cover;'></td>";
                                echo "<td>" . (isset($materiel['nom']) ? htmlspecialchars($materiel['nom']) : '') . "</td>";
                                echo "<td>" . (isset($materiel['type_materiel']) ? htmlspecialchars($materiel['type_materiel']) : '') . "</td>";
                                echo "<td>" . (isset($materiel['description']) ? htmlspecialchars($materiel['description']) : '') . "</td>";
                                echo "<td>" . (isset($materiel['quantite']) ? htmlspecialchars($materiel['quantite']) : '') . "</td>";
                                echo "<td>
                                    <div class='action-buttons'>
                                        <button class='editBtn' onclick='editMateriel(" . $materiel['Id_materiel'] . ")'>
                                            <svg height='1em' viewBox='0 0 512 512'>
                                                <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                            </svg>
                                        </button>
                                        <button class='bin-button' onclick='deleteMateriel(" . $materiel['Id_materiel'] . ")'>
                                            <svg class='bin-top' viewBox='0 0 39 7' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                <line y1='5' x2='39' y2='5' stroke='white' stroke-width='4'></line>
                                                <line x1='12' y1='1.5' x2='26.0357' y2='1.5' stroke='white' stroke-width='3'></line>
                                            </svg>
                                            <svg class='bin-bottom' viewBox='0 0 33 39' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                <mask id='path-1-inside-1_8_19' fill='white'>
                                                    <path d='M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z'></path>
                                                </mask>
                                                <path d='M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z' fill='white' mask='url(#path-1-inside-1_8_19)'></path>
                                                <path d='M12 6L12 29' stroke='white' stroke-width='4'></path>
                                                <path d='M21 6V29' stroke='white' stroke-width='4'></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout Matériel -->
    <div class="modal fade" id="addMaterielModal" tabindex="-1" aria-labelledby="addMaterielModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterielModalLabel">Ajouter du matériel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMaterielForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="materielImage" class="form-label">Image du matériel</label>
                            <input type="file" class="form-control" id="materielImage" name="materielImage" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="materielName" class="form-label">Nom du matériel</label>
                            <input type="text" class="form-control" id="materielName" name="materielName" required>
                        </div>
                        <div class="form-group">
                            <label for="materielType">Type de matériel</label>
                            <select class="form-control" id="materielType" name="materielType" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="audiovisuel">Audiovisuel</option>
                                <option value="informatique">Informatique</option>
                                <option value="multimedia">Multimédia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="materielDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="materielDescription" name="materielDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="materielQuantity" class="form-label">Quantité initiale</label>
                            <input type="number" class="form-control" id="materielQuantity" name="materielQuantity" min="1" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="submitMateriel()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout Salle -->
    <div class="modal fade" id="addSalleModal" tabindex="-1" aria-labelledby="addSalleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSalleModalLabel">Ajouter une salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSalleForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="salleImage" class="form-label">Image de la salle</label>
                            <input type="file" class="form-control" id="salleImage" name="salleImage" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="salleName" class="form-label">Nom de la salle</label>
                            <input type="text" class="form-control" id="salleName" name="salleName" required>
                        </div>
                        <div class="mb-3">
                            <label for="salleCapacite" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="salleCapacite" name="salleCapacite" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="salleDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="salleDescription" name="salleDescription" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="submitSalle()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Salle -->
    <div class="modal fade" id="editSalleModal" tabindex="-1" aria-labelledby="editSalleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSalleModalLabel">Modifier une salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSalleForm" enctype="multipart/form-data">
                        <input type="hidden" id="editSalleId" name="id">
                        <div class="mb-3">
                            <label for="editSalleImage" class="form-label">Image de la salle</label>
                            <input type="file" class="form-control" id="editSalleImage" name="salleImage" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="editSalleName" class="form-label">Nom de la salle</label>
                            <input type="text" class="form-control" id="editSalleName" name="salleName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSalleCapacite" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="editSalleCapacite" name="salleCapacite" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSalleDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editSalleDescription" name="salleDescription" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditSalle()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Matériel -->
    <div class="modal fade" id="editMaterielModal" tabindex="-1" aria-labelledby="editMaterielModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMaterielModalLabel">Modifier un matériel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMaterielForm" enctype="multipart/form-data">
                        <input type="hidden" id="editMaterielId" name="id">
                        <div class="mb-3">
                            <label for="editMaterielImage" class="form-label">Image du matériel</label>
                            <input type="file" class="form-control" id="editMaterielImage" name="materielImage" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="editMaterielName" class="form-label">Nom du matériel</label>
                            <input type="text" class="form-control" id="editMaterielName" name="materielName" required>
                        </div>
                        <div class="form-group">
                            <label for="editMaterielType">Type de matériel</label>
                            <select class="form-control" id="editMaterielType" name="materielType" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="audiovisuel">Audiovisuel</option>
                                <option value="informatique">Informatique</option>
                                <option value="multimedia">Multimédia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMaterielDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editMaterielDescription" name="materielDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editMaterielQuantity" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="editMaterielQuantity" name="materielQuantity" min="1" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditMateriel()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../JS/navbar.js"></script>
    <script src="../../JS/gestion.js"></script>
</body>
</html> 