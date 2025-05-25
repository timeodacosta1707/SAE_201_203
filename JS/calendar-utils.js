// Fonctions utilitaires pour la gestion des dates et du calendrier
const CalendarUtils = {
    // Vérifie si une date est valide (24h à l'avance minimum)
    isDateValid(date) {
        const now = new Date();
        const minDate = new Date(now.getTime() + 24 * 60 * 60 * 1000);
        minDate.setHours(0, 0, 0, 0);
        const checkDate = new Date(date);
        checkDate.setHours(0, 0, 0, 0);
        return checkDate >= minDate;
    },

    // Crée un événement dans le calendrier
    createCalendarEvent(calendar, startDate, endDate) {
        return calendar.addEvent({
            start: startDate,
            end: endDate,
            display: 'background',
            color: '#2f2a86'
        });
    },

    // Valide un champ de date
    validateDateInput(dateInput, errorElementId) {
        const selectedDate = new Date(dateInput.value);
        const errorElement = document.getElementById(errorElementId);
        
        if (!this.isDateValid(selectedDate)) {
            dateInput.classList.add('is-invalid');
            errorElement.style.display = 'block';
            return false;
        } else {
            dateInput.classList.remove('is-invalid');
            errorElement.style.display = 'none';
            return true;
        }
    },

    // Configuration du calendrier
    getCalendarConfig(type, calendar) {
        return {
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
                dows: [1, 2, 3, 4, 5]
            } : {
                startTime: '08:30',
                endTime: '18:00',
                dows: [0, 1, 2, 3, 4, 5, 6]
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
        };
    },

    // Gestion des événements de sélection pour le matériel
    handleMaterialSelection(calendar, startDate, endDate, info) {
        const currentDate = new Date(startDate);
        const endDateCopy = new Date(endDate);
        const events = [];

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
                const event = this.createCalendarEvent(calendar, dayStart, dayEnd);
                events.push(event);
            }

            currentDate.setDate(currentDate.getDate() + 1);
        }

        return events;
    },

    // Met à jour les champs du formulaire avec les dates sélectionnées
    updateFormFields(type, info) {
        if (type === 'materiel') {
            document.getElementById('dateDebut').value = info.startStr.split('T')[0];
            document.getElementById('dateFin').value = info.endStr.split('T')[0];
        } else {
            document.getElementById('dateReservation').value = info.startStr.split('T')[0];
        }
        document.getElementById('heureDebut').value = info.startStr.split('T')[1].substring(0, 5);
        document.getElementById('heureFin').value = info.endStr.split('T')[1].substring(0, 5);
    },

    // Vérifie la durée maximale de réservation
    checkMaxDuration(type, startDate, endDate) {
        const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
        
        if (type === 'salle' && diffDays > 1) {
            alert('Les réservations de salles sont limitées à 1 jour maximum.');
            return false;
        }
        
        if (type === 'materiel' && diffDays > 7) {
            alert('Les réservations de matériel sont limitées à 1 semaine maximum.');
            return false;
        }
        
        return true;
    }
};

// Export pour utilisation dans d'autres fichiers
export default CalendarUtils; 