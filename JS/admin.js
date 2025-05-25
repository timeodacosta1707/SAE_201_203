// Fonction pour basculer l'affichage des champs d'édition
function toggleEditFields() {
    const role = document.getElementById('editRole').value;
    const etudiantFields = document.getElementById('edit-etudiant-fields');
    const professeurFields = document.getElementById('edit-professeur-fields');

    // Cacher tous les champs spécifiques
    etudiantFields.style.display = 'none';
    professeurFields.style.display = 'none';

    // Afficher les champs selon le rôle
    if (role === 'etudiant') {
        etudiantFields.style.display = 'block';
    } else if (role === 'professeur') {
        professeurFields.style.display = 'block';
    }
}

// Fonction pour éditer un utilisateur
function editUser(userId, nom, prenom, email, role, date_embauche, annee, groupe_td, groupe_tp, promotion, telephone, date_naissance, numero_carte) {
    console.log('Données reçues:', {
        userId, nom, prenom, email, role, date_embauche,
        annee, groupe_td, groupe_tp, promotion, telephone,
        date_naissance, numero_carte
    });

    // Remplir les champs de base
    document.getElementById('editUserId').value = userId;
    document.getElementById('editNom').value = nom;
    document.getElementById('editPrenom').value = prenom;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    document.getElementById('editTelephone').value = telephone || '';
    
    // Réinitialiser tous les champs spécifiques
    document.getElementById('editDateNaissance').value = '';
    document.getElementById('editNumeroCarte').value = '';
    document.getElementById('editFormation').value = '';
    document.getElementById('editAnnee').value = '';
    document.getElementById('editGroupeTD').value = '';
    document.getElementById('editGroupeTP').value = '';
    document.getElementById('editDateEmbauche').value = '';
    
    // Remplir les champs selon le rôle
    if (role === 'etudiant') {
        if (date_naissance) document.getElementById('editDateNaissance').value = date_naissance;
        if (numero_carte) document.getElementById('editNumeroCarte').value = numero_carte;
        if (promotion) document.getElementById('editFormation').value = promotion;
        if (annee) document.getElementById('editAnnee').value = annee;
        if (groupe_td) document.getElementById('editGroupeTD').value = groupe_td;
        if (groupe_tp) document.getElementById('editGroupeTP').value = groupe_tp;
    } else if (role === 'professeur') {
        if (date_embauche) document.getElementById('editDateEmbauche').value = date_embauche;
    }
    
    // Afficher/masquer les champs appropriés
    toggleEditFields();
    
    // Ouvrir le modal
    const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editUserModal.show();
}

// Fonction pour supprimer un utilisateur
function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        fetch('../traitement/delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userId=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Utilisateur supprimé avec succès');
                location.reload();
            } else {
                alert('Erreur lors de la suppression : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression');
        });
    }
}

// Fonction pour basculer l'affichage des champs
function toggleFields() {
    const role = document.getElementById('role').value;
    const professeurFields = document.getElementById('professeur-fields');
    const agentFields = document.getElementById('agent-fields');

    // Cacher tous les champs
    professeurFields.style.display = 'none';
    agentFields.style.display = 'none';

    // Afficher les champs correspondants au rôle
    if (role === 'professeur') {
        professeurFields.style.display = 'block';
        document.getElementById('date_embauche').required = true;
    } else {
        document.getElementById('date_embauche').required = false;
    }
}

// Fonction pour valider le formulaire d'ajout d'utilisateur
function validateAddUserForm() {
    const role = document.getElementById('role').value;
    let isValid = true;
    let errorMessage = '';

    // Validation des champs communs
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!nom || !prenom || !email || !password) {
        errorMessage = 'Veuillez remplir tous les champs obligatoires.';
        isValid = false;
    }

    // Validation des champs spécifiques selon le rôle
    if (role === 'professeur') {
        const dateEmbauche = document.getElementById('date_embauche').value;
        if (!dateEmbauche) {
            errorMessage = 'Veuillez remplir la date d\'embauche du professeur.';
            isValid = false;
        }
    }

    if (!isValid) {
        alert(errorMessage);
    }

    return isValid;
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter l'événement de soumission au formulaire
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            console.log('Formulaire soumis');
            console.log('Rôle sélectionné:', document.getElementById('role').value);
            console.log('Nom:', document.getElementById('nom').value);
            console.log('Prénom:', document.getElementById('prenom').value);
            console.log('Email:', document.getElementById('email').value);
            
            if (!validateAddUserForm()) {
                e.preventDefault();
            }
        });
    }

    // Initialiser les champs
    toggleFields();
    toggleEditFields();
    
    // Configuration de la recherche et du filtrage
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const tableRows = document.querySelectorAll('.users-table tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;

        tableRows.forEach(row => {
            const nom = row.cells[0].textContent.toLowerCase();
            const prenom = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const roleElement = row.cells[3].querySelector('span');
            const role = roleElement ? roleElement.textContent.toLowerCase() : '';

            const matchesSearch = nom.includes(searchTerm) || 
                                prenom.includes(searchTerm) || 
                                email.includes(searchTerm);
            
            const matchesRole = selectedRole === 'all' || 
                              (selectedRole === 'etudiant' && role.includes('étudiant')) ||
                              (selectedRole === 'professeur' && role.includes('professeur')) ||
                              (selectedRole === 'agent' && role.includes('agent')) ||
                              (selectedRole === 'admin' && role.includes('administrateur'));

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    // Ajouter les écouteurs d'événements pour la recherche et le filtrage
    if (searchInput && roleFilter) {
        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    }
}); 