"use strict";

// Sélection des éléments nécessaires
document.addEventListener('DOMContentLoaded', () => {
    console.log("Chargement du DOM terminé");

    //to change passeword
    const ancienPasswordInput = document.querySelector("#ancienMotDePasse");
    const nouveauPasswordInput = document.querySelector("#nouveauMotDePasse");
    const confirmNewPasswordInput = document.querySelector("#confirmerMotDePasse");

    const toggleAncienPasswordBtn = document.querySelector("#icon-toggle-password-old");
    const toggleNouveauPasswordBtn = document.querySelector("#icon-toggle-password-new");
    const toggleConfirmNewPasswordBtn = document.querySelector("#icon-toggle-password-confirm");

    console.log(toggleAncienPasswordBtn); // Vérifiez ce qui est retourné

    if (ancienPasswordInput && toggleAncienPasswordBtn) {
        toggleAncienPasswordBtn.addEventListener('click', function () {
            togglePasswordVisibility(ancienPasswordInput, toggleAncienPasswordBtn);
        });
    }

    if (nouveauPasswordInput && toggleNouveauPasswordBtn) {
        toggleNouveauPasswordBtn.addEventListener('click', function () {
            togglePasswordVisibility(nouveauPasswordInput, toggleNouveauPasswordBtn);
        });
    }

    if (confirmNewPasswordInput && toggleConfirmNewPasswordBtn) {
        toggleConfirmNewPasswordBtn.addEventListener('click', function () {
            togglePasswordVisibility(confirmNewPasswordInput, toggleConfirmNewPasswordBtn);
        });
    }
});

// Fonction pour afficher ou masquer les mots de passe
function togglePasswordVisibility(inputField, toggleButton) {
    const isPasswordHidden = inputField.type === "password";
    inputField.type = isPasswordHidden ? "text" : "password";

    // Change l'icône
    toggleButton.classList.toggle('fa-eye'); // Icône d'œil ouvert
    toggleButton.classList.toggle('fa-eye-slash'); // Icône d'œil barré
}