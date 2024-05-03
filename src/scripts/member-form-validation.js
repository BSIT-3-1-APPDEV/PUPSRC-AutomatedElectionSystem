//---------FORM VALIDATION--------------//
// Get the form elements
const form = document.getElementById("admin-form");
const firstNameInput = document.getElementById("first_name");
const middleNameInput = document.getElementById("middle_name");
const lastNameInput = document.getElementById("last_name");
const emailInput = document.getElementById("email");
const firstNameError = document.getElementById("first_name_error");
const middleNameError = document.getElementById("middle_name_error");
const lastNameError = document.getElementById("last_name_error");
const emailError = document.getElementById("email_error");

// First Name validation
function validateFirstName() {
  if (firstNameInput.value.length > 50 || !firstNameInput.value.match(/^[a-zA-Z]+$/)) {
    firstNameError.textContent = "Please enter a valid name.";
    firstNameError.style.color = "red";
    firstNameInput.style.borderColor = "red"; // Add red border
  } else {
    firstNameError.textContent = "";
    firstNameInput.style.borderColor = ""; // Remove red border
  }
}
firstNameInput.addEventListener("input", validateFirstName);

// Middle Name validation
function validateMiddleName() {
  if (middleNameInput.value.length > 50 || !middleNameInput.value.match(/^[a-zA-Z]+$/)) {
    middleNameError.textContent = "Please enter a valid name.";
    middleNameError.style.color = "red";
    middleNameInput.style.borderColor = "red"; // Add red border
  } else {
    middleNameError.textContent = "";
    middleNameInput.style.borderColor = ""; // Remove red border
  }
}
middleNameInput.addEventListener("input", validateMiddleName);

// Last Name validation
function validateLastName() {
  if (lastNameInput.value.length > 50 || !lastNameInput.value.match(/^[a-zA-Z]+$/)) {
    lastNameError.textContent = "Please enter a valid name.";
    lastNameError.style.color = "red";
    lastNameInput.style.borderColor = "red"; // Add red border
  } else {
    lastNameError.textContent = "";
    lastNameInput.style.borderColor = ""; // Remove red border
  }
}
lastNameInput.addEventListener("input", validateLastName);

// Email validation
const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
function validateEmail() {
  if (!emailInput.value.match(emailPattern)) {
    emailError.textContent = "Please enter a valid email address.";
    emailError.style.color = "red";
    emailInput.style.borderColor = "red"; // Add red border
  } else {
    emailError.textContent = "";
    emailInput.style.borderColor = ""; // Remove red border
  }
}
emailInput.addEventListener("input", validateEmail);

// Form submission
form.addEventListener("submit", function (event) {
  validateFirstName();
  validateMiddleName();
  validateLastName();
  validateEmail();

  // If there are any errors, prevent form submission
  if (
    firstNameError.textContent ||
    middleNameError.textContent ||
    lastNameError.textContent ||
    emailError.textContent
  ) {
    event.preventDefault();
  }
});


//---------CREATE BUTTON DISABLE/ABLE--------------//

// Get the submit button
const submitButton = document.querySelector('.button-create');

// Initially disable the submit button and add the 'button-disabled' class
submitButton.disabled = true;
submitButton.classList.add('button-disabled');

// Add event listeners to input fields
firstNameInput.addEventListener('input', toggleSubmitButton);
middleNameInput.addEventListener('input', toggleSubmitButton);
lastNameInput.addEventListener('input', toggleSubmitButton);
emailInput.addEventListener('input', toggleSubmitButton);

// Function to toggle submit button state
function toggleSubmitButton() {
  const isFormValid =
    firstNameInput.value.length > 0 &&
    middleNameInput.value.length > 0 &&
    lastNameInput.value.length > 0 &&
    emailInput.value.length > 0 &&
    !firstNameError.textContent &&
    !middleNameError.textContent &&
    !lastNameError.textContent &&
    !emailError.textContent;

  submitButton.disabled = !isFormValid;

  if (isFormValid) {
    submitButton.classList.remove('button-disabled');
  } else {
    submitButton.classList.add('button-disabled');
  }
}

// Add CSS class for disabled button state
const styleElem = document.head.appendChild(document.createElement("style"));
styleElem.innerHTML = `
  .button-disabled {
    background-color: gray !important;
    cursor: not-allowed !important;
  }
`;

//---------MODAL--------------//