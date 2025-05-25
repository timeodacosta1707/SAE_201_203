<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Université Gustave Eiffel - Conditions d'utilisation</title>
    <link rel="icon" href="../../images/logo.png" type="image/png">
    <link rel="stylesheet" href="../../CSS/navbar.css">
    <link rel="stylesheet" href="../../CSS/stylecondition.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
</head>

<header>
    <?php if(!(isset($_GET['from']) && $_GET['from'] == 'register')): ?>
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
    <?php endif; ?>
</header>

<body>
    <section class="banniere-mentions">
        <div class="container">
            <h1>Conditions d'utilisation</h1>
            <p>Veuillez lire attentivement les conditions d'utilisation suivantes avant d'utiliser notre plateforme de
                réservation de matériel.</p>
        </div>
    </section>

    <section class="mentions-section">
        <div class="container">
            <div class="mentions-card">
                <h2>Objet du service</h2>
                <p>Le service de réservation de matériel de l'Université Gustave Eiffel est une plateforme en ligne
                    destinée exclusivement aux étudiants, enseignants et personnels de l'université. Ce service permet
                    de réserver du matériel et des salles à des fins pédagogiques et académiques.</p>
                <p>L'utilisation de ce service est strictement réservée aux activités universitaires et scolaires dans
                    le cadre des formations dispensées par l'Université Gustave Eiffel.</p>
            </div>

            <div class="mentions-card">
                <h2>Conditions d'accès</h2>
                <p><strong>Utilisateurs autorisés :</strong> Seuls les étudiants inscrits, les enseignants et le
                    personnel administratif de l'Université Gustave Eiffel peuvent accéder à ce service.</p>
                <p><strong>Authentification :</strong> L'accès au service nécessite une authentification via les
                    identifiants universitaires fournis par l'établissement.</p>
                <p><strong>Responsabilité :</strong> Chaque utilisateur est responsable de la confidentialité de ses
                    identifiants et de toutes les actions effectuées avec son compte.</p>
            </div>

            <div class="mentions-card">
                <h2>Règles de réservation</h2>
                <p><strong>Durée :</strong> Les réservations sont limitées à une durée maximale définie par
                    l'administration selon le type de matériel ou de salle.</p>
                <p><strong>Anticipation :</strong> Les réservations doivent être effectuées au minimum 24 heures à
                    l'avance et au maximum 30 jours avant la date souhaitée.</p>
                <p><strong>Annulation :</strong> Toute annulation doit être effectuée au moins 12 heures avant le début
                    de la réservation. Les annulations répétées sans motif valable pourront entraîner des restrictions
                    d'accès au service.</p>
                <p><strong>Priorité :</strong> Les enseignants ont priorité sur les réservations pour les activités
                    pédagogiques. L'administration se réserve le droit de modifier ou d'annuler une réservation en cas
                    de besoin urgent.</p>
            </div>

            <div class="mentions-card">
                <h2>Utilisation du matériel</h2>
                <p><strong>État du matériel :</strong> L'utilisateur s'engage à vérifier l'état du matériel lors de sa
                    prise en charge et à signaler immédiatement tout dysfonctionnement ou dommage constaté.</p>
                <p><strong>Responsabilité :</strong> L'utilisateur est entièrement responsable du matériel emprunté
                    pendant toute la durée de la réservation. En cas de perte, vol ou dégradation, l'utilisateur pourra
                    être tenu de rembourser les frais de réparation ou de remplacement.</p>
                <p><strong>Utilisation conforme :</strong> Le matériel doit être utilisé conformément à sa destination
                    et aux instructions fournies. Toute utilisation non conforme est strictement interdite.</p>
                <p><strong>Restitution :</strong> Le matériel doit être restitué dans l'état où il a été emprunté, à la
                    date et l'heure prévues. Tout retard non justifié pourra entraîner des sanctions.</p>
            </div>

            <div class="mentions-card">
                <h2>Sanctions</h2>
                <p>En cas de non-respect des présentes conditions d'utilisation, l'Université Gustave Eiffel se réserve
                    le droit d'appliquer les sanctions suivantes :</p>
                <ul>
                    <li>Avertissement écrit</li>
                    <li>Suspension temporaire de l'accès au service</li>
                    <li>Exclusion définitive du service</li>
                    <li>Facturation des dommages causés</li>
                    <li>Sanctions disciplinaires conformément au règlement intérieur de l'université</li>
                </ul>
            </div>

            <div class="mentions-card">
                <h2>Protection des données personnelles</h2>
                <p>Les données personnelles collectées dans le cadre du service de réservation sont utilisées
                    exclusivement pour la gestion des réservations et la communication liée au service.</p>
                <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit
                    d'accès, de rectification et de suppression de vos données personnelles. Pour exercer ces droits,
                    veuillez contacter le délégué à la protection des données de l'université à l'adresse :
                    dpo@univ-eiffel.fr</p>
            </div>

            <div class="mentions-card">
                <h2>Modification des conditions d'utilisation</h2>
                <p>L'Université Gustave Eiffel se réserve le droit de modifier les présentes conditions d'utilisation à
                    tout moment. Les utilisateurs seront informés des modifications par email et/ou par une notification
                    sur la plateforme.</p>
                <p>L'utilisation continue du service après notification des modifications vaut acceptation des nouvelles
                    conditions.</p>
            </div>

            <div class="mentions-card">
                <h2>Contact</h2>
                <p>Pour toute question concernant ces conditions d'utilisation ou pour signaler un problème, veuillez
                    nous contacter :</p>
                <ul>
                    <li>Par téléphone : 01 64 36 44 15</li>
                    <li>Par email : contact@iut.fr</li>
                    <li>Par courrier : IUT de Marne-La-Vallée, Site de Meaux</li>
                </ul>
                <div class="contact-button">
                    <a href="./contact.html" class="btn bg-couliut btn-lg rounded-pill px-4 custom-hover">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
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
                <?php if(!(isset($_GET['from']) && $_GET['from'] == 'register')): ?>
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
                <?php endif; ?>
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
                <?php if(!(isset($_GET['from']) && $_GET['from'] == 'register')): ?>
                <a href="../../HTML/mentionlegal.html">Mentions Legales</a>
                <a href="#">Conditions d'utilisations</a>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <script src="../../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>