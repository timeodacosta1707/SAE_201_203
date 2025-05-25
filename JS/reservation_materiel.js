document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filtreCategorie = document.getElementById('filtreCategorie');
    const btnFilter = document.getElementById('btnFilter');
    const listeMateriel = document.getElementById('listeMateriel');
    const noResults = document.getElementById('noResults');
    const modalReservation = new bootstrap.Modal(document.getElementById('modalReservation'));
    const btnConfirmerReservation = document.getElementById('btnConfirmerReservation');
    
    function filterMaterials() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoryFilter = filtreCategorie.value;
        let visibleCount = 0;
        
        const materialItems = document.querySelectorAll('.material-item');
        materialItems.forEach(item => {
            const materialName = item.getAttribute('data-name').toLowerCase();
            const materialType = item.getAttribute('data-type');
            
            const matchesSearch = materialName.includes(searchTerm);
            const matchesCategory = categoryFilter === '' || materialType === categoryFilter;
            
            if (matchesSearch && matchesCategory) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
    
    searchInput.addEventListener('input', filterMaterials);
    filtreCategorie.addEventListener('change', filterMaterials);
    btnFilter.addEventListener('click', filterMaterials);
    
    const reserveButtons = document.querySelectorAll('.btn-reserve');
    reserveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const materialId = this.getAttribute('data-id');
            const materialName = this.getAttribute('data-name');
            
            document.getElementById('materielId').value = materialId;
            document.getElementById('materielNom').textContent = materialName;
            
            document.getElementById('heureDebut').value = '08:00';
            document.getElementById('heureFin').value = '18:00';
        });
    });
    
    const dateReservation = document.getElementById('dateReservation');
    if (dateReservation) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${yyyy}-${mm}-${dd}`;
        dateReservation.setAttribute('min', formattedDate);
        dateReservation.value = formattedDate;
    }
    
    const heureDebut = document.getElementById('heureDebut');
    const heureFin = document.getElementById('heureFin');
    
    heureDebut.addEventListener('change', function() {
        if (heureDebut.value >= heureFin.value) {
            const [hours, minutes] = heureDebut.value.split(':');
            let newHours = parseInt(hours) + 1;
            if (newHours > 23) newHours = 23;
            heureFin.value = `${String(newHours).padStart(2, '0')}:${minutes}`;
        }
    });
    
    btnConfirmerReservation.addEventListener('click', function() {
        const form = document.getElementById('formReservation');
        if (form.checkValidity()) {
            const materielId = document.getElementById('materielId').value;
            const dateReservation = document.getElementById('dateReservation').value;
            const heureDebut = document.getElementById('heureDebut').value;
            const heureFin = document.getElementById('heureFin').value;
            const quantite = document.getElementById('quantiteReservation').value;
            const motif = document.getElementById('motifReservation').value;
            
            const reservationData = {
                materielId: materielId,
                dateReservation: dateReservation,
                heureDebut: heureDebut,
                heureFin: heureFin,
                quantite: quantite,
                motif: motif
            };
            
            console.log('Données de réservation:', reservationData);
            
            alert('Votre réservation a été enregistrée avec succès !');
            
            modalReservation.hide();
        } else {
            form.reportValidity();
        }
    });
    
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
});