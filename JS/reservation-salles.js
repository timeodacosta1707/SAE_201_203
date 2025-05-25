// Initialisation du calendrier
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        select: function(info) {
            document.getElementById('dateReservation').value = info.startStr.split('T')[0];
            document.getElementById('heureDebut').value = info.startStr.split('T')[1].substring(0, 5);
            document.getElementById('heureFin').value = info.endStr.split('T')[1].substring(0, 5);
        }
    });
    calendar.render();

    // Fonction de recherche
    const searchInput = document.getElementById('searchInput');
    const items = document.querySelectorAll('.material-item');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        let hasResults = false;

        items.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            if (name.includes(searchTerm)) {
                item.style.display = '';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('noResults').classList.toggle('d-none', hasResults);
    });

    // Initialiser les boutons de réservation
    document.querySelectorAll('.btn-reserve').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            document.getElementById('salleId').value = id;
            document.getElementById('salleNom').textContent = name;
        });
    });
});

// Fonction pour soumettre la réservation
function submitReservation() {
    const form = document.getElementById('formReservation');
    const formData = new FormData(form);
    formData.append('type', 'salle');

    fetch('../traitement/reservation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Réservation effectuée avec succès !');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalReservation'));
            modal.hide();
            form.reset();
        } else {
            alert('Erreur lors de la réservation: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la réservation');
    });
} 