"use strict";

// Sélection des éléments nécessaires
const form = document.querySelector("#login-form");
const loginInput = document.querySelector("#login");
const passwordInput = document.querySelector("#password");
const togglePasswordBtn = document.querySelector("#icon-toggle-password");
const errorMessage = document.querySelector("#error-message");

// Fonction de validation du champ de pseudo/email
function isValidLogin(login) {
  // On considère que le login peut être un email ou un pseudo
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(login) || login.length >= 3;  // Si ce n'est pas un email, on vérifie que le pseudo fait au moins 3 caractères
}

// Fonction de validation du mot de passe
function validatePassword(password) {
  if (password.length < 6) {
    return "Le mot de passe doit contenir au moins 6 caractères.";
  }
  return "";
}

// Gestion de la soumission du formulaire
form.addEventListener("submit", function (event) {
  event.preventDefault(); // Empêche la soumission par défaut
  errorMessage.textContent = ""; // Réinitialise les erreurs

  const login = loginInput.value.trim();
  const password = passwordInput.value;

  // Validation des champs
  if (!isValidLogin(login)) {
    errorMessage.textContent = "Veuillez entrer un pseudo ou un email valide.";
    return;
  }

  const passwordError = validatePassword(password);
  if (passwordError) {
    errorMessage.textContent = passwordError;
    return;
  }

  // Si tout est valide, on peut simuler une connexion réussie
  alert("Connexion réussie !");
  // Redirige vers la page d'accueil ou tableau de bord
  window.location.href = "dashboard.html"; // Change cette URL selon ta structure
});

// Fonction pour afficher/masquer le mot de passe
function togglePasswordVisibility(inputField, toggleButton) {
  const isPasswordHidden = inputField.type === "password";
  inputField.type = isPasswordHidden ? "text" : "password";

  // Change l'icône
  toggleButton.classList.toggle('fa-eye'); // Icône d'œil ouvert
  toggleButton.classList.toggle('fa-eye-slash'); // Icône d'œil barré
}

// Événements pour afficher/masquer le mot de passe
togglePasswordBtn.addEventListener('click', function () {
  togglePasswordVisibility(passwordInput, togglePasswordBtn);
});
