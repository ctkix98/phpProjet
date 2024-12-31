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
  