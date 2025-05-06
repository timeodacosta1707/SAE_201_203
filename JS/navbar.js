window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

document.querySelector('.icone-profil').addEventListener('click', function() {
    const menuProfil = document.querySelector('.menu-profil');
    menuProfil.style.display = menuProfil.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(event) {
    const iconeProfil = document.querySelector('.icone-profil');
    const menuProfil = document.querySelector('.menu-profil');
    
    if (!iconeProfil.contains(event.target) && !menuProfil.contains(event.target)) {
        menuProfil.style.display = 'none';
    }
});