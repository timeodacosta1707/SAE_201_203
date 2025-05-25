// Fonction pour ouvrir le modal d'ajout de matériel
function openAddMaterielModal() {
    const modal = new bootstrap.Modal(document.getElementById('addMaterielModal'));
    modal.show();
}

// Fonction pour soumettre le formulaire d'ajout de matériel
function submitMateriel() {
    const form = document.getElementById('addMaterielForm');
    const formData = new FormData(form);

    fetch('../traitement/add_materiel.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ajouter la nouvelle ligne au tableau
            const tbody = document.querySelector('#materielsTable tbody');
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-id', data.materiel.Id_materiel);
            newRow.innerHTML = `
                <td><img src="../../${data.materiel.image}" alt="${data.materiel.nom}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                <td>${data.materiel.nom}</td>
                <td>${data.materiel.type_materiel.charAt(0).toUpperCase() + data.materiel.type_materiel.slice(1)}</td>
                <td>${data.materiel.description}</td>
                <td>${data.materiel.quantite}</td>
                <td>
                    <div class='action-buttons'>
                        <button class='editBtn' onclick='editMateriel(${data.materiel.Id_materiel})'>
                            <svg height='1em' viewBox='0 0 512 512'>
                                <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                            </svg>
                        </button>
                        <button class='bin-button' onclick='deleteMateriel(${data.materiel.Id_materiel})'>
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
                </td>`;
            tbody.insertBefore(newRow, tbody.firstChild);

            // Fermer le modal et réinitialiser le formulaire
            const modal = bootstrap.Modal.getInstance(document.getElementById('addMaterielModal'));
            modal.hide();
            form.reset();
        } else {
            alert('Erreur lors de l\'ajout du matériel: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'ajout du matériel');
    });
}

// Fonctions pour éditer et supprimer
function editMateriel(id) {
    // Récupérer les données du matériel
    fetch(`../traitement/get.php?id=${id}&type=materiel`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const materiel = data.materiel;
                document.getElementById('editMaterielId').value = materiel.Id_materiel;
                document.getElementById('editMaterielName').value = materiel.nom;
                document.getElementById('editMaterielType').value = materiel.type_materiel;
                document.getElementById('editMaterielDescription').value = materiel.description;
                document.getElementById('editMaterielQuantity').value = materiel.quantite;
                
                const modal = new bootstrap.Modal(document.getElementById('editMaterielModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la récupération des données du matériel');
        });
}

function submitEditMateriel() {
    const form = document.getElementById('editMaterielForm');
    const formData = new FormData(form);
    formData.append('type', 'materiel');

    fetch('../traitement/edit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour la ligne dans le tableau
            const row = document.querySelector(`#materielsTable tr[data-id="${data.materiel.Id_materiel}"]`);
            if (row) {
                row.innerHTML = `
                    <td><img src="../../${data.materiel.image}" alt="${data.materiel.nom}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                    <td>${data.materiel.nom}</td>
                    <td>${data.materiel.type_materiel.charAt(0).toUpperCase() + data.materiel.type_materiel.slice(1)}</td>
                    <td>${data.materiel.description}</td>
                    <td>${data.materiel.quantite}</td>
                    <td>
                        <div class='action-buttons'>
                            <button class='editBtn' onclick='editMateriel(${data.materiel.Id_materiel})'>
                                <svg height='1em' viewBox='0 0 512 512'>
                                    <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                </svg>
                            </button>
                            <button class='bin-button' onclick='deleteMateriel(${data.materiel.Id_materiel})'>
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
                    </td>`;
            }

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editMaterielModal'));
            modal.hide();
        } else {
            alert('Erreur lors de la modification du matériel: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la modification du matériel');
    });
}

function deleteMateriel(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce matériel ?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('type', 'materiel');

        fetch('../traitement/delete.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer la ligne du tableau
                const row = document.querySelector(`#materielsTable tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression du matériel');
        });
    }
}

// Fonction pour ouvrir le modal d'ajout de salle
function openAddSalleModal() {
    const modal = new bootstrap.Modal(document.getElementById('addSalleModal'));
    modal.show();
}

// Fonction pour soumettre le formulaire d'ajout de salle
function submitSalle() {
    const form = document.getElementById('addSalleForm');
    const formData = new FormData(form);

    fetch('../traitement/add_salle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ajouter la nouvelle ligne au tableau
            const tbody = document.querySelector('#sallesTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><img src="../../${data.salle.image}" alt="${data.salle.nom}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                <td>${data.salle.nom}</td>
                <td>${data.salle.capacite}</td>
                <td>${data.salle.description}</td>
                <td>
                    <div class='action-buttons'>
                        <button class='editBtn' onclick='editSalle(${data.salle.Id_salle})'>
                            <svg height='1em' viewBox='0 0 512 512'>
                                <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                            </svg>
                        </button>
                        <button class='bin-button' onclick='deleteSalle(${data.salle.Id_salle})'>
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
                </td>`;
            tbody.insertBefore(newRow, tbody.firstChild);

            // Fermer le modal et réinitialiser le formulaire
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSalleModal'));
            modal.hide();
            form.reset();
        } else {
            alert('Erreur lors de l\'ajout de la salle: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'ajout de la salle');
    });
}

// Fonctions pour les salles
function editSalle(id) {
    // Récupérer les données de la salle
    fetch(`../traitement/get.php?id=${id}&type=salle`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const salle = data.salle;
                document.getElementById('editSalleId').value = salle.Id_salle;
                document.getElementById('editSalleName').value = salle.nom;
                document.getElementById('editSalleCapacite').value = salle.capacite;
                document.getElementById('editSalleDescription').value = salle.description;
                
                const modal = new bootstrap.Modal(document.getElementById('editSalleModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la récupération des données de la salle');
        });
}

function submitEditSalle() {
    const form = document.getElementById('editSalleForm');
    const formData = new FormData(form);
    formData.append('type', 'salle');

    fetch('../traitement/edit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour la ligne dans le tableau
            const row = document.querySelector(`#sallesTable tr[data-id="${data.salle.Id_salle}"]`);
            if (row) {
                row.innerHTML = `
                    <td><img src="../../${data.salle.image}" alt="${data.salle.nom}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                    <td>${data.salle.nom}</td>
                    <td>${data.salle.capacite}</td>
                    <td>${data.salle.description}</td>
                    <td>
                        <div class='action-buttons'>
                            <button class='editBtn' onclick='editSalle(${data.salle.Id_salle})'>
                                <svg height='1em' viewBox='0 0 512 512'>
                                    <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                </svg>
                            </button>
                            <button class='bin-button' onclick='deleteSalle(${data.salle.Id_salle})'>
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
                    </td>`;
            }

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editSalleModal'));
            modal.hide();
        } else {
            alert('Erreur lors de la modification de la salle: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la modification de la salle');
    });
}

function deleteSalle(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('type', 'salle');

        fetch('../traitement/delete.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`#sallesTable tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression de la salle');
        });
    }
} 