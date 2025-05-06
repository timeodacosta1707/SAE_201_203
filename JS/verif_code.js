document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll('.case');
    const boutonEnvoyer = document.getElementById("bouton-envoyer");

    inputs[0].focus();

    function verifierChamps() {
        const tousRemplis = Array.from(inputs).every(input => input.value.length === 1);
        boutonEnvoyer.disabled = !tousRemplis;
    }

    verifierChamps();

    inputs.forEach((input, index) => {
        input.addEventListener('input', function (e) {
            const value = e.target.value;

            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            verifierChamps();
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
});
