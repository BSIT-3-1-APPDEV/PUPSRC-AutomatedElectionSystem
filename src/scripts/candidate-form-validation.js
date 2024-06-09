//---------FORM VALIDATION--------------//

// Get the form elements
const form = document.getElementById("candidate-form");
const firstNameInput = document.getElementById("first_name");
const middleNameInput = document.getElementById("middle_name");
const lastNameInput = document.getElementById("last_name");
const positionInput = document.getElementById("position");
const sectionInput = document.getElementById("section");
const photoInput = document.getElementById("photo");
const submitButton = document.querySelector('.button-create');
const errorMessages = {
  first_name: "Please enter a valid name.",
  middle_name: "Please enter a valid name.",
  last_name: "Please enter a valid name.",
};

// Utility function to validate inputs
function validateInput(input, regex, maxLength, errorMessage) {
  const errorElement = document.getElementById(`${input.id}_error`);
  if (input.value.length > maxLength || (input.value.length > 0 && !input.value.match(regex))) {
    errorElement.textContent = errorMessage;
    errorElement.style.color = "red";
    input.style.borderColor = "red"; // Add red border
    return false;
  } else {
    errorElement.textContent = "";
    input.style.borderColor = ""; // Remove red border
    return true;
  }
}

// Validate name inputs
const validateName = (input) => validateInput(input, /^[a-z ,.'-]+$/i, 50, errorMessages[input.id]);

//Prevent Spaces
function preventLeadingSpace(event) {
  const input = event.target;
  if (input.value.startsWith(' ')) {
      input.value = input.value.trim(); // Remove leading space
  }
  // Replace multiple consecutive spaces with a single space
  input.value = input.value.replace(/\s{2,}/g, ' ');
}

 // Event listeners to prevent whitespaces
 $("#first_name").on("input", function (event) {
  preventLeadingSpace(event);
});
$("#last_name").on("input", function (event) {          
  preventLeadingSpace(event);
});
$("#middle_name").on("input", function (event) {
  preventLeadingSpace(event);
});

// First Name validation
firstNameInput.addEventListener("input", () => validateName(firstNameInput));

// Middle Name validation
middleNameInput.addEventListener("input", () => {
  if (middleNameInput.value.trim() === "") {
    document.getElementById(`${middleNameInput.id}_error`).textContent = "";
    middleNameInput.style.borderColor = ""; // Remove red border if empty
    return true;
  }
  return validateName(middleNameInput);
});

// Last Name validation
lastNameInput.addEventListener("input", () => validateName(lastNameInput));



// Form submission
form.addEventListener("submit", function (event) {
  const isFormValid = validateName(firstNameInput) &&
    validateName(lastNameInput) &&
    validateName(middleNameInput);
  if (!isFormValid) {
    event.preventDefault();
  }
});

//---------CREATE BUTTON DISABLE/ABLE--------------//

// Initially disable the submit button and add the 'button-disabled' class
submitButton.disabled = true;
submitButton.classList.add('button-disabled');

// Function to toggle submit button state
function toggleSubmitButton() {
  const isFormValid = firstNameInput.value.trim() !== "" &&
    lastNameInput.value.trim() !== "" &&
    positionInput.value.trim() !== "" &&
    sectionInput.value.trim() !== "" &&
    photoInput.files.length > 0;

  submitButton.disabled = !isFormValid;
  submitButton.classList.toggle('button-disabled', !isFormValid);
}

// Add event listeners to input fields
[firstNameInput, lastNameInput, positionInput, sectionInput, photoInput].forEach(input => {
  input.addEventListener("input", toggleSubmitButton);
  input.addEventListener("change", toggleSubmitButton);
});

// Add CSS class for disabled button state
const styleElem = document.head.appendChild(document.createElement("style"));
styleElem.innerHTML = `
  .button-disabled {
    background-color: gray !important;
    cursor: not-allowed !important;
  }
`;
