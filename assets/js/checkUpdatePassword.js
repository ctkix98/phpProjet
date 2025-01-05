document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("nouveauMotDePasse");
    const confirmPasswordInput = document.getElementById("confirmerMotDePasse");
    const formChangerMotDePasse = document.getElementById("formChangerMotDePasse");
    const requirements = {
      length: document.getElementById("length"),
      uppercase: document.getElementById("uppercase"),
      lowercase: document.getElementById("lowercase"),
      number: document.getElementById("number"),
      special: document.getElementById("special"),
    };
  
    // Regex patterns for each requirement
    const patterns = {
      length: /.{8,}/, // At least 8 characters
      uppercase: /[A-Z]/, // At least one uppercase letter
      lowercase: /[a-z]/, // At least one lowercase letter
      number: /\d/, // At least one number
      special: /[!@#$%^&*()\-+={}[\]:;"'<>,.?\/|\\]/, // At least one special character
    };
  
    // Function to validate the password against each requirement
    const validatePassword = () => {
      const password = passwordInput.value;
      let isValid = true;
  
      // Check each requirement
      for (const [key, element] of Object.entries(requirements)) {
        if (patterns[key].test(password)) {
          element.classList.add("valid");
          element.classList.remove("invalid");
        } else {
          element.classList.add("invalid");
          element.classList.remove("valid");
          isValid = false;
        }
      }
      return isValid; // Return true if all conditions are met, otherwise false
    };
  
    // Add event listener to the password input
    passwordInput.addEventListener("input", validatePassword);
  
    // Initialize validation on page load
    validatePassword();
  
    // Check if the confirmation password matches
    confirmPasswordInput.addEventListener("input", () => {
      const errorElement = document.getElementById("erreurMotDePasse");
  
      if (passwordInput.value !== confirmPasswordInput.value) {
        errorElement.textContent = "Les mots de passe ne sont pas identiques. Veuillez les vérifier.";
        errorElement.style.display = "block";
      } else {
        errorElement.style.display = "none";
      }
    });
  
    // Prevent form submission if password is invalid or doesn't match
    formChangerMotDePasse.addEventListener("submit", function (event) {
      const errorElement = document.getElementById("erreurMotDePasse");
      const isPasswordValid = validatePassword(); // Check if the password meets all criteria
      const arePasswordsMatching = passwordInput.value === confirmPasswordInput.value; // Check if passwords match
  
      // Check if the password meets the criteria and if the confirmation matches
      if (!isPasswordValid || !arePasswordsMatching) {
        event.preventDefault(); // Prevent form submission
        if (!isPasswordValid) {
          errorElement.textContent = "Le mot de passe ne respecte pas tous les critères.";
          errorElement.style.display = "block";
        }
        if (!arePasswordsMatching) {
          errorElement.textContent = "Les mots de passe ne sont pas identiques. Veuillez les vérifier.";
          errorElement.style.display = "block";
        }
      } else {
        errorElement.style.display = "none"; // Hide error if all is correct
      }
    });
  });
  
  // To display the form when the user wants to update password or delete account
  function toggleForm(formId) {
    const forms = document.querySelectorAll("form[id^='form']");
    forms.forEach((form) => {
      if (form.id === formId) {
        form.style.display =
          form.style.display === "none" || form.style.display === ""
            ? "block"
            : "none";
      } else {
        form.style.display = "none"; // To hide other forms
      }
    });
  }
  