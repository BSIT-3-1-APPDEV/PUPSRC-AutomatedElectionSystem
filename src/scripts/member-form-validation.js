// Validate form inputs
const form = document.getElementById('admin-form');
const lastNameInput = document.getElementById('last_name');
const firstNameInput = document.getElementById('first_name');
const middleNameInput = document.getElementById('middle_name');
const emailInput = document.getElementById('email');

const lastNameError = document.getElementById('last_name_error');
const firstNameError = document.getElementById('first_name_error');
const middleNameError = document.getElementById('middle_name_error');
const emailError = document.getElementById('email_error');

function validateForm(event) {
    let isValid = true;

    // Last Name validation
    if (lastNameInput.value.length > 20 || !lastNameInput.value.match(/^[a-zA-Z]+$/)) {
        lastNameError.textContent = 'Last name can only contain alphabetic characters and should not exceed 20 characters.';
        lastNameError.style.color = 'red';
        isValid = false;
    } else {
        lastNameError.textContent = '';
    }

    // First Name validation
    if (firstNameInput.value.length > 20 || !firstNameInput.value.match(/^[a-zA-Z]+$/)) {
        firstNameError.textContent = 'First name can only contain alphabetic characters and should not exceed 20 characters.';
        firstNameError.style.color = 'red';
        isValid = false;
    } else {
        firstNameError.textContent = '';
    }

    // Middle Name validation
    if (middleNameInput.value.length > 20 || !middleNameInput.value.match(/^[a-zA-Z]+$/)) {
        middleNameError.textContent = 'Middle name can only contain alphabetic characters and should not exceed 20 characters.';
        middleNameError.style.color = 'red';
        isValid = false;
    } else {
        middleNameError.textContent = '';
    }

    // Email validation
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailInput.value.match(emailPattern)) {
        emailError.textContent = 'Please enter a valid email address.';
        emailError.style.color = 'red';
        isValid = false;
    } else {
        emailError.textContent = '';
    }

    if (!isValid) {
        event.preventDefault();
    }
}

form.addEventListener('submit', validateForm);