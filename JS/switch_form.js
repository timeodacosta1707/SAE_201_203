const loginTab = document.getElementById("loginTab");
const registerTab = document.getElementById("registerTab");
const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");
const formTitle = document.getElementById("formTitle");

loginTab.addEventListener("click", function () {
    loginTab.classList.add("active");
    registerTab.classList.remove("active");
    loginForm.style.display = "block";
    registerForm.style.display = "none";
    formTitle.textContent = "Se connecter";
});

registerTab.addEventListener("click", function () {
    registerTab.classList.add("active");
    loginTab.classList.remove("active");
    loginForm.style.display = "none";
    registerForm.style.display = "block";
    formTitle.textContent = "S'inscrire en tant qu'étudiant";
});