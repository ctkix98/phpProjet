document.addEventListener("DOMContentLoaded", () => {
  const passwordInput = document.getElementById("password");
  const pseudoInput = document.getElementById("pseudo");
  const pseudoMessage = document.getElementById("pseudo-message");
  const requirements = {
    length: document.getElementById("length"),
    uppercase: document.getElementById("uppercase"),
    lowercase: document.getElementById("lowercase"),
    number: document.getElementById("number"),
    special: document.getElementById("special"),
  };

  //To check the pseudo lenght
  pseudoInput.addEventListener("input", () => {
    const pseudo = pseudoInput.value;

    // Vérification de la longueur du pseudo
    if (pseudo.length >= 4) {
      pseudoMessage.style.color = "green";
    } else {
      pseudoMessage.style.color = "red"; 
  });

  //PASSWORD
  // Regex patterns for each requirement
  const patterns = {
    length: /.{8,}/,
    uppercase: /[A-Z]/,
    lowercase: /[a-z]/,
    number: /\d/,
    special: /[!@#$%^&*()\-+={}[\]:;"'<>,.?\/|\\]/,
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

//To display the form when the user wants to update password or delete account
function toggleForm(formId) {
  const forms = document.querySelectorAll("form[id^='form']");
  forms.forEach((form) => {
    if (form.id === formId) {
      form.style.display =
        form.style.display === "none" || form.style.display === ""
          ? "block"
          : "none";
    } else {
      form.style.display = "none"; // To hide others forms
    }
  });
}

// Ajouter un écouteur d'événement pour vérifier les mots de passe
document.addEventListener("DOMContentLoaded", function () {
  const formChangerMotDePasse = document.getElementById(
    "formChangerMotDePasse"
  );
  const erreurMotDePasse = document.getElementById("erreurMotDePasse");

  if (formChangerMotDePasse) {
    formChangerMotDePasse.addEventListener("submit", function (event) {
      const nouveauMotDePasse = formChangerMotDePasse.querySelector(
        'input[name="nouveauMotDePasse"]'
      ).value;
      const confirmerMotDePasse = formChangerMotDePasse.querySelector(
        'input[name="confirmerMotDePasse"]'
      ).value;

      if (nouveauMotDePasse !== confirmerMotDePasse) {
        event.preventDefault();
        erreurMotDePasse.textContent =
          "Les mots de passe ne sont pas identiques. Veuillez les vérifier.";
        erreurMotDePasse.style.display = "block";
      } else {
        erreurMotDePasse.style.display = "none";
      }
    });
  }
});
