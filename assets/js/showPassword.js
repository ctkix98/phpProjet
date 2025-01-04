"use strict";

// Sélection des éléments nécessaires
const passwordInput = document.querySelector("#password");
const confirmPasswordInput = document.querySelector("#confirm-password");
const togglePasswordBtn = document.querySelector("#icon-toggle-password");
const toggleConfirmPasswordBtn = document.querySelector("#icon-toggle-confirm-password");


// Fonction pour afficher ou masquer les mots de passe
function togglePasswordVisibility(inputField, toggleButton) {
    const isPasswordHidden = inputField.type === "password";
    inputField.type = isPasswordHidden ? "text" : "password";

    // Change l'icône
    toggleButton.classList.toggle('fa-eye'); // Icône d'œil ouvert
    toggleButton.classList.toggle('fa-eye-slash'); // Icône d'œil barré
}

// Événements pour afficher/masquer les mots de passe
togglePasswordBtn.addEventListener('click', function () {
    togglePasswordVisibility(passwordInput, togglePasswordBtn);
});

toggleConfirmPasswordBtn.addEventListener('click', function () {
    togglePasswordVisibility(confirmPasswordInput, toggleConfirmPasswordBtn); 
});



