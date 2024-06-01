
// Get form elements
const adminForm = document.getElementById("admin-form");
const firstNameInput = document.getElementById("first_name");
const middleNameInput = document.getElementById("middle_name");
const lastNameInput = document.getElementById("last_name");
const emailInputField = document.getElementById("email");
const emailErrorField = document.getElementById("email_error");
const roleSelect = document.getElementById("role");
const roleError = document.getElementById("role_error");
const suffixInput = document.getElementById("suffix");
const suffixError = document.getElementById("suffix_error");

// Email validation pattern
const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(?!\.c0m$)(?!@test)$/;

// Email validation function
function validateEmail() {
    const emailValue = emailInputField.value.trim();

    if (!emailValue.match(emailPattern)) {
        emailErrorField.textContent = "Please enter a valid email address.";
        emailErrorField.style.color = "red";
        emailInputField.style.borderColor = "red";
    } else {
        emailErrorField.textContent = "";
        emailInputField.style.borderColor = "";
    }
}

// Add event listener for email input
emailInputField.addEventListener("input", validateEmail);

// Form submission
adminForm.addEventListener("submit", function (event) {
    validateEmail();

    // If there is an email error, prevent form submission
    if (emailErrorField.textContent) {
        event.preventDefault();
    }
});

// First Name validation
function validateFirstName() {
    const firstNameInput = document.getElementById("first_name");
    const firstNameError = document.getElementById("first_name_error");
    const namePattern = /^[a-zA-Z]+(\s?[a-zA-Z]+)*$/;

    if (!namePattern.test(firstNameInput.value)) {
        firstNameError.textContent = "Please enter a valid name.";
        firstNameError.style.color = "red";
        firstNameInput.style.borderColor = "red";
    } else {
        firstNameError.textContent = "";
        firstNameInput.style.borderColor = "";
    }
}
firstNameInput.addEventListener("input", validateFirstName);

// Middle Name validation
function validateMiddleName() {
    const middleNameInput = document.getElementById("middle_name");
    const middleNameError = document.getElementById("middle_name_error");
    const namePattern = /^[a-zA-Z]+(\s?[a-zA-Z]+)*$/;

    if (middleNameInput.value.length > 0 && !namePattern.test(middleNameInput.value)) {
        middleNameError.textContent = "Please enter a valid name.";
        middleNameError.style.color = "red";
        middleNameInput.style.borderColor = "red";
    } else {
        middleNameError.textContent = "";
        middleNameInput.style.borderColor = "";
    }
}
middleNameInput.addEventListener("input", validateMiddleName);

// Suffix validation
function validateSuffix() {
    const suffixInput = document.getElementById('suffix');
    const suffixError = document.getElementById('suffix_error');

    if (suffixInput.value.length > 0 && (!suffixInput.value.match(/^[a-zA-Z]+$/) || suffixInput.value.length > 3)) {
        suffixError.textContent = 'Please enter a valid suffix.';
        suffixError.style.color = 'red';
        suffixInput.style.borderColor = 'red';
    } else {
        suffixError.textContent = '';
        suffixInput.style.borderColor = '';
    }
}
suffixInput.addEventListener("input", validateSuffix);

// Last Name validation
function validateLastName() {
    const lastNameInput = document.getElementById("last_name");
    const lastNameError = document.getElementById("last_name_error");
    const namePattern = /^[a-zA-Z]+(\s?[a-zA-Z]+)*$/;

    if (!namePattern.test(lastNameInput.value)) {
        lastNameError.textContent = "Please enter a valid name.";
        lastNameError.style.color = "red";
        lastNameInput.style.borderColor = "red";
    } else {
        lastNameError.textContent = "";
        lastNameInput.style.borderColor = "";
    }
}
lastNameInput.addEventListener("input", validateLastName);

// Role validation
function validateRole() {
  const roleSelect = document.getElementById("role");
  const roleError = document.getElementById("role_error");

  if (roleError) { // Check if roleError is not null
      if (roleSelect.value === "") {
          roleError.textContent = "Please select a role.";
          roleError.style.color = "red";
          roleSelect.style.borderColor = "red";
      } else {
          roleError.textContent = "";
          roleSelect.style.borderColor = "";
      }
  }
}
roleSelect.addEventListener("change", validateRole);

//---------CREATE BUTTON DISABLE/ABLE--------------//

// Get the submit button
const submitButton = document.querySelector('.button-create');

// Initially disable the submit button and add the 'button-disabled' class
submitButton.disabled = true;
submitButton.classList.add('button-disabled');

// Add event listeners to input fields
firstNameInput.addEventListener('input', toggleSubmitButton);
lastNameInput.addEventListener('input', toggleSubmitButton);
emailInputField.addEventListener('input', toggleSubmitButton);
roleSelect.addEventListener('change', toggleSubmitButton);

// Function to toggle submit button state
function toggleSubmitButton() {
    const isFormValid =
        firstNameInput.value.trim().length > 0 &&
        lastNameInput.value.trim().length > 0 &&
        emailInputField.value.trim().length > 0 &&
        (roleSelect.value === 'head_admin' || roleSelect.value === 'admin');

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

document.addEventListener('DOMContentLoaded', function() {
    var createdModal = new bootstrap.Modal(document.getElementById('createdModal'));
    var modalContent = document.querySelector('#createdModal .modal-content');
  
    modalContent.addEventListener('click', function(event) {
      if (event.target.classList.contains('close-mark')) {
        createdModal.hide();
      }
    });
  });