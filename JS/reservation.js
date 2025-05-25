document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const type = calendarEl.dataset.type;
    let currentEvent = null;
    let isSelecting = false;
    let selectionStart = null;
    let selectedEvents = [];
    let calendar;
    let formData = null;
    
    // Initialisation du modal
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    
    // Obtenir la date d'aujourd'hui à minuit
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: type === 'materiel' ? 'timeGridWeek' : 'timeGridDay',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: type === 'materiel' ? 'timeGridWeek,timeGridDay' : ''
        },
        slotMinTime: '08:30:00',
        slotMaxTime: '18:00:00',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        selectMirror: true,
        unselectAuto: false,
        selectMinDistance: 5,
        longPressDelay: 100,
        selectConstraint: type === 'materiel' ? {
            startTime: '08:30',
            endTime: '18:00',
            dows: [1, 2, 3, 4, 5]  // Lundi à Vendredi
        } : {
            startTime: '08:30',
            endTime: '18:00',
            dows: [0, 1, 2, 3, 4, 5, 6]
        },
        selectAllow: function(selectInfo) {
            if (type === 'materiel' && calendar.view.type === 'timeGridWeek') {
                return false;
            }
            const startDate = new Date(selectInfo.start);
            const now = new Date();
            const minDate = new Date(now.getTime() + 24 * 60 * 60 * 1000); // 24h à partir de maintenant
            startDate.setHours(0, 0, 0, 0);
            minDate.setHours(0, 0, 0, 0);
            return startDate >= minDate;
        },
        select: function(info) {
            // Nettoyer les sélections précédentes
            selectedEvents.forEach(event => event.remove());
            selectedEvents = [];
            
            const startDate = new Date(info.start);
            const endDate = new Date(info.end);
            
            // Vérification de la durée maximale pour les salles (1 jour)
            if (type === 'salle') {
                const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                if (diffDays > 1) {
                    alert('Les réservations de salles sont limitées à 1 jour maximum.');
                    calendar.unselect();
                    return;
                }
            }
            
            // Vérification de la durée maximale pour le matériel (1 semaine)
            if (type === 'materiel') {
                const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                if (diffDays > 7) {
                    alert('Les réservations de matériel sont limitées à 1 semaine maximum.');
                    calendar.unselect();
                    return;
                }
            }
            
            // Création de l'événement pour chaque jour sélectionné
            if (type === 'materiel') {
                const currentDate = new Date(startDate);
                const endDateCopy = new Date(endDate);
                
                // Ajuster les heures pour la fin de la sélection
                endDateCopy.setHours(parseInt(info.endStr.split('T')[1].split(':')[0]));
                endDateCopy.setMinutes(parseInt(info.endStr.split('T')[1].split(':')[1]));
                
                while (currentDate <= endDateCopy) {
                    const dayStart = new Date(currentDate);
                    dayStart.setHours(parseInt(info.startStr.split('T')[1].split(':')[0]));
                    dayStart.setMinutes(parseInt(info.startStr.split('T')[1].split(':')[1]));
                    
                    const dayEnd = new Date(currentDate);
                    dayEnd.setHours(parseInt(info.endStr.split('T')[1].split(':')[0]));
                    dayEnd.setMinutes(parseInt(info.endStr.split('T')[1].split(':')[1]));
                    
                    // Ne créer l'événement que si c'est un jour de semaine (1-5)
                    if (dayStart.getDay() >= 1 && dayStart.getDay() <= 5) {
                        const event = calendar.addEvent({
                            start: dayStart,
                            end: dayEnd,
                            display: 'background',
                            color: '#2f2a86'
                        });
                        selectedEvents.push(event);
                    }
                    
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            } else {
                const event = calendar.addEvent({
                    start: info.start,
                    end: info.end,
                    display: 'background',
                    color: '#2f2a86'
                });
                selectedEvents.push(event);
            }
            
            if (type === 'materiel') {
                document.getElementById('dateDebut').value = info.startStr.split('T')[0];
                document.getElementById('dateFin').value = info.endStr.split('T')[0];
            } else {
                document.getElementById('dateReservation').value = info.startStr.split('T')[0];
            }
            document.getElementById('heureDebut').value = info.startStr.split('T')[1].substring(0, 5);
            document.getElementById('heureFin').value = info.endStr.split('T')[1].substring(0, 5);
        },
        unselect: function() {
            isSelecting = false;
            selectionStart = null;
        },
        datesSet: function(info) {
            // Mise à jour du message en fonction de la vue
            const selectionHint = document.querySelector('.selection-hint');
            if (type === 'materiel') {
                if (info.view.type === 'timeGridDay') {
                    selectionHint.innerHTML = '<i class="fas fa-info-circle"></i> Cliquez et faites glisser ou remplissez le formulaire pour sélectionner un créneau horaire';
                } else {
                    selectionHint.innerHTML = '<i class="fas fa-info-circle"></i> Utilisez le formulaire pour sélectionner les dates';
                }
            }

            // Gestion du message de restriction de 24h
            const todayButton = document.querySelector('.fc-today-button');
            const existingMessage = document.querySelector('.restriction-message');
            
            // Supprimer le message existant s'il y en a un
            if (existingMessage) {
                existingMessage.remove();
            }

            // Vérifier si on est sur le jour d'aujourd'hui
            const currentDate = new Date();
            const viewDate = new Date(info.view.currentStart);
            const isToday = currentDate.toDateString() === viewDate.toDateString();

            // Ajouter le message uniquement si on est sur le jour d'aujourd'hui
            if (todayButton && isToday) {
                const restrictionMessage = document.createElement('span');
                restrictionMessage.className = 'ms-2 restriction-message';
                restrictionMessage.style.fontSize = '0.9em';
                restrictionMessage.style.backgroundColor = '#ffebee';
                restrictionMessage.style.color = '#d32f2f';
                restrictionMessage.style.padding = '2px 8px';
                restrictionMessage.style.borderRadius = '4px';
                restrictionMessage.innerHTML = '<i class="fas fa-info-circle"></i> Réservation minimum 24h à l\'avance';
                todayButton.parentNode.insertBefore(restrictionMessage, todayButton.nextSibling);
            }

            // Réappliquer les événements lors du changement de vue
            if (selectedEvents.length > 0) {
                // Supprimer tous les événements existants
                calendar.getEvents().forEach(event => event.remove());
                
                // Recréer tous les événements sélectionnés
                selectedEvents.forEach(event => {
                    const newEvent = calendar.addEvent({
                        start: event.start,
                        end: event.end,
                        display: 'background',
                        color: '#2f2a86'
                    });
                    // Remplacer l'ancien événement par le nouveau
                    const index = selectedEvents.indexOf(event);
                    if (index > -1) {
                        selectedEvents[index] = newEvent;
                    }
                });
            }
        },
        views: {
            timeGridDay: {
                titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
            },
            timeGridWeek: {
                titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
            }
        },
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00',
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    calendar.render();

    // Ajout d'un gestionnaire d'événements pour le clic maintenu
    calendarEl.addEventListener('mousedown', function(e) {
        if (type === 'salle') {
            isSelecting = true;
        }
    });

    calendarEl.addEventListener('mouseup', function(e) {
        if (type === 'salle') {
            isSelecting = false;
        }
    });

    // Ajout des écouteurs d'événements pour les champs de date et d'heure
    const dateInputs = ['dateReservation', 'dateDebut', 'dateFin'];
    const timeInputs = ['heureDebut', 'heureFin'];
    const submitButton = document.querySelector('button[type="submit"]');
    
    function checkFormValidity() {
        const hasInvalidInputs = document.querySelectorAll('.is-invalid').length > 0;
        submitButton.disabled = hasInvalidInputs;
    }
    
    dateInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                if (id === 'dateDebut' || id === 'dateReservation') {
                    const selectedDate = new Date(this.value);
                    const now = new Date();
                    const minDate = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                    minDate.setHours(0, 0, 0, 0);
                    selectedDate.setHours(0, 0, 0, 0);

                    const errorElement = document.getElementById(id + 'Error');
                    if (selectedDate < minDate) {
                        this.classList.add('is-invalid');
                        errorElement.style.display = 'block';
                    } else {
                        this.classList.remove('is-invalid');
                        errorElement.style.display = 'none';
                    }
                } else if (id === 'dateFin') {
                    const dateFin = this.value;
                    
                    if (dateFin) {
                        const finDate = new Date(dateFin);
                        const now = new Date();
                        const minDate = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                        minDate.setHours(0, 0, 0, 0);
                        finDate.setHours(0, 0, 0, 0);

                        const errorElement = document.getElementById('dateFinError');
                        if (finDate < minDate) {
                            this.classList.add('is-invalid');
                            errorElement.style.display = 'block';
                        } else {
                            this.classList.remove('is-invalid');
                            errorElement.style.display = 'none';
                        }
                    }
                }
                checkFormValidity();
                updateCalendarSelection();
            });
        }
    });
    
    timeInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                updateCalendarSelection();
                checkFormValidity();
            });
        }
    });

    // Vérification initiale
    checkFormValidity();

    // Mise à jour du calendrier lors de la modification manuelle des champs
    function updateCalendarSelection() {
        // Suppression de tous les événements existants
        calendar.getEvents().forEach(event => event.remove());
        
        let startDate, endDate;
        if (type === 'materiel') {
            const dateDebut = document.getElementById('dateDebut').value;
            const dateFin = document.getElementById('dateFin').value;
            const heureDebut = document.getElementById('heureDebut').value;
            const heureFin = document.getElementById('heureFin').value;
            
            if (dateDebut && dateFin && heureDebut && heureFin) {
                startDate = new Date(`${dateDebut}T${heureDebut}`);
                endDate = new Date(`${dateFin}T${heureFin}`);
                
                // Création d'un événement pour chaque jour
                const currentDate = new Date(startDate);
                while (currentDate < endDate) {
                    const dayStart = new Date(currentDate);
                    dayStart.setHours(parseInt(heureDebut.split(':')[0]));
                    dayStart.setMinutes(parseInt(heureDebut.split(':')[1]));
                    
                    const dayEnd = new Date(currentDate);
                    dayEnd.setHours(parseInt(heureFin.split(':')[0]));
                    dayEnd.setMinutes(parseInt(heureFin.split(':')[1]));
                    
                    calendar.addEvent({
                        start: dayStart,
                        end: dayEnd,
                        display: 'background',
                        color: '#2f2a86'
                    });
                    
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            }
        } else {
            const dateReservation = document.getElementById('dateReservation').value;
            const heureDebut = document.getElementById('heureDebut').value;
            const heureFin = document.getElementById('heureFin').value;
            
            if (dateReservation && heureDebut && heureFin) {
                startDate = new Date(`${dateReservation}T${heureDebut}`);
                endDate = new Date(`${dateReservation}T${heureFin}`);
                
                calendar.addEvent({
                    start: startDate,
                    end: endDate,
                    display: 'background',
                    color: '#2f2a86'
                });
            }
        }
    }

    // Gestion du formulaire
    document.getElementById('formReservation').addEventListener('submit', function(e) {
        e.preventDefault();
        formData = new FormData(this);

        // Remplir les détails dans le modal
        if (type === 'materiel') {
            document.getElementById('modalDateDebut').textContent = formData.get('dateDebut');
            document.getElementById('modalDateFin').textContent = formData.get('dateFin');
            document.getElementById('modalQuantite').textContent = formData.get('quantiteReservation');
        } else {
            document.getElementById('modalDateDebut').textContent = formData.get('dateReservation');
            document.getElementById('modalDateFin').textContent = formData.get('dateReservation');
        }
        document.getElementById('modalHeureDebut').textContent = formData.get('heureDebut');
        document.getElementById('modalHeureFin').textContent = formData.get('heureFin');
        document.getElementById('modalMotif').textContent = formData.get('motifReservation');

        confirmationModal.show();
    });

    // Gestion de la confirmation de réservation
    document.getElementById('confirmReservation').addEventListener('click', function() {
        if (!formData) return;

        fetch('../traitement/traitement_reservation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Réservation effectuée avec succès !');
                window.location.href = 'mes_reservations.php';
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la réservation');
        })
        .finally(() => {
            confirmationModal.hide();
        });
    });
});