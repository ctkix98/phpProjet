document.addEventListener("DOMContentLoaded", () => {
    const isbnInput = document.getElementById("isbn");
    const isbnMessage = document.getElementById("isbn-error");
    const submitButton = document.querySelector("button[type='submit']");
  
    // Expression régulière pour valider l'ISBN avec des tirets ou des espaces
    const isbnPattern = /^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d- ]+$/;
  
    // Fonction pour vérifier la validité de l'ISBN
    const validateISBN = () => {
      const isbnValue = isbnInput.value.trim();
  
      // Vérifier si l'ISBN correspond au format requis
      if (isbnPattern.test(isbnValue)) {
        isbnMessage.style.display = "none"; // Masquer le message d'erreur si valide
        isbnInput.style.borderColor = "";  // Réinitialiser la bordure du champ
        submitButton.disabled = false; // Activer le bouton de soumission si l'ISBN est valide
      } else {
        isbnMessage.style.display = "block"; // Afficher le message d'erreur si invalide
        isbnMessage.textContent = "L'ISBN doit être au format valide (10 ou 13 chiffres avec des tirets ou des espaces).";
        isbnInput.style.borderColor = "red";  // Ajouter une bordure rouge pour indiquer l'erreur
        submitButton.disabled = true; // Désactiver le bouton de soumission si l'ISBN est invalide
      }
    };
  
    // Ajouter un événement d'écoute sur le champ ISBN pour vérifier la valeur à chaque entrée
    isbnInput.addEventListener("input", validateISBN);
  
    // Initialiser l'état du bouton de soumission (désactivé au départ)
    submitButton.disabled = true;
  
    // Valider l'ISBN lorsque le formulaire est soumis
    const form = document.querySelector("form");
    form.addEventListener("submit", (event) => {
      const isbnValue = isbnInput.value.trim();
      if (!isbnPattern.test(isbnValue)) {
        event.preventDefault();  // Empêcher la soumission du formulaire si l'ISBN est invalide
        isbnMessage.style.display = "block"; // Afficher le message d'erreur
        isbnMessage.textContent = "L'ISBN doit être au format valide (10 ou 13 chiffres avec des tirets ou des espaces).";
        isbnInput.style.borderColor = "red"; // Ajouter une bordure rouge pour indiquer l'erreur
      }
    });
  });
  