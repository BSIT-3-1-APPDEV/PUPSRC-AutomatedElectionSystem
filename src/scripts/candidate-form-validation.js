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
  position: "Please select a position.",
  section: "Please select a section.",
  photo: "Please select a JPG or PNG file."
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
const validateName = (input) => validateInput(input, /^[a-zA-Z]+$/, 50, errorMessages[input.id]);

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


// Position validation
positionInput.addEventListener("change", () => validateInput(positionInput, /.+/, 0, errorMessages.position));

// Section validation
sectionInput.addEventListener("change", () => validateInput(sectionInput, /.+/, 0, errorMessages.section));

// Photo validation
function validatePhoto() {
  const file = photoInput.files[0];
  const fileType = file ? file.name.split(".").pop().toLowerCase() : "";

  const isValid = file && (fileType === "jpg" || fileType === "png");
  const errorElement = document.getElementById("photo_error");
  if (!isValid) {
    errorElement.textContent = errorMessages.photo;
    errorElement.style.color = "red";
    photoInput.style.borderColor = "red"; // Add red border
  } else {
    errorElement.textContent = "";
    photoInput.style.borderColor = ""; // Remove red border
  }
  return isValid;
}
photoInput.addEventListener("change", validatePhoto);

// Form submission
form.addEventListener("submit", function (event) {
  const isFormValid = validateName(firstNameInput) &&
                      validateName(lastNameInput) &&
                      validateInput(positionInput, /.+/, 0, errorMessages.position) &&
                      validateInput(sectionInput, /.+/, 0, errorMessages.section) &&
                      validatePhoto();

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
  const isFormValid = validateName(firstNameInput) &&
                      validateName(lastNameInput) &&
                      validateInput(positionInput, /.+/, 0, errorMessages.position) &&
                      validateInput(sectionInput, /.+/, 0, errorMessages.section) &&
                      validatePhoto();

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

