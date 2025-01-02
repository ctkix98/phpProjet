document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const requirements = {
      length: document.getElementById("length"),
      uppercase: document.getElementById("uppercase"),
      lowercase: document.getElementById("lowercase"),
      number: document.getElementById("number"),
      special: document.getElementById("special"),
    };
  
    // Regex patterns for each requirement
    const patterns = {
      length: /.{8,}/,
      uppercase: /[A-Z]/,
      lowercase: /[a-z]/,
      number: /\d/,
      special: /[$!€£]/,
    };
  
    // Function to validate the password against each requirement
    const validatePassword = () => {
      const password = passwordInput.value;
  
      for (const [key, element] of Object.entries(requirements)) {
        if (patterns[key].test(password)) {
          element.classList.add("valid");
          element.classList.remove("invalid");
        } else {
          element.classList.add("invalid");
          element.classList.remove("valid");
        }
      }
    };
  
    // Add event listener to the password input
    passwordInput.addEventListener("input", validatePassword);
  
    // Initialize validation on page load
    validatePassword();
  });
  
  function toggleForm(formId) {
    const forms = document.querySelectorAll("form[id^='form']"); // Sélectionne tous les formulaires
    forms.forEach(form => {
        if (form.id === formId) {
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
        } else {
            form.style.display = 'none'; // Cache les autres formulaires
        }
    });
}

// Ajouter un écouteur d'événement pour vérifier les mots de passe
document.addEventListener("DOMContentLoaded", function() {
    const formChangerMotDePasse = document.getElementById('formChangerMotDePasse');
    const erreurMotDePasse = document.getElementById('erreurMotDePasse');

    if (formChangerMotDePasse) {
        formChangerMotDePasse.addEventListener('submit', function(event) {
            const nouveauMotDePasse = formChangerMotDePasse.querySelector('input[name="nouveauMotDePasse"]').value;
            const confirmerMotDePasse = formChangerMotDePasse.querySelector('input[name="confirmerMotDePasse"]').value;

            if (nouveauMotDePasse !== confirmerMotDePasse) {
                event.preventDefault(); // Bloque l'envoi du formulaire
                erreurMotDePasse.textContent = "Les mots de passe ne sont pas identiques. Veuillez les vérifier.";
                erreurMotDePasse.style.display = 'block'; // Affiche le message d'erreur
            } else {
                erreurMotDePasse.style.display = 'none'; // Cache le message d'erreur s'il n'y a pas de problème
            }
        });
    }
});