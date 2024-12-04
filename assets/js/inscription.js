"use strict";

// Sélection des éléments nécessaires
const form = document.querySelector("form");
const emailInput = document.querySelector("input[type='email']");
const passwordInput = document.querySelector("#password");
const confirmPasswordInput = document.querySelector("#confirm-password");
const strengthBar = document.querySelector("#strength-bar");
const togglePasswordBtn = document.querySelector("#icon-toggle-password");
const toggleConfirmPasswordBtn = document.querySelector("#icon-toggle-confirm-password");
const errorMessage = document.querySelector("#error-message");

// Fonction de validation d'email
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Fonction de validation des mots de passe
function validatePasswords(password, confirmPassword) {
  if (password !== confirmPassword) {
    return "Les mots de passe ne correspondent pas.";
  }
  if (password.length < 6) {
    return "Le mot de passe doit contenir au moins 6 caractères.";
  }
  return "";
}

// Gestion de la soumission du formulaire
form.addEventListener("submit", function (event) {
  event.preventDefault(); // Empêche la soumission par défaut
  errorMessage.textContent = ""; // Réinitialise les erreurs

  const email = emailInput.value.trim();
  const password = passwordInput.value;
  const confirmPassword = confirmPasswordInput.value;

  // Validation des champs
  if (!isValidEmail(email)) {
    errorMessage.textContent = "Veuillez entrer une adresse email valide.";
    return;
  }

  const passwordError = validatePasswords(password, confirmPassword);
  if (passwordError) {
    errorMessage.textContent = passwordError;
    return;
  }

  // Soumission réussie (simulation)
  alert("Inscription réussie !");
  window.location.href = "connexion.html"; // Redirige vers la page de connexion
});

// Gestion de l'affichage/masquage du mot de passe
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

