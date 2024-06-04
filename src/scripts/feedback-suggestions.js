document.addEventListener('DOMContentLoaded', function() {
  const dropdownToggle = document.getElementById('navbarDropdown');
  const chevronIcon = document.getElementById('dropdown-chevron');

  dropdownToggle.addEventListener('click', function() {
    // Check if the dropdown is currently shown
    const isDropdownShown = dropdownToggle.getAttribute('aria-expanded') === 'true';

    if (isDropdownShown) {
      chevronIcon.classList.remove('fa-chevron-down');
      chevronIcon.classList.add('fa-chevron-up');
    } else {
      chevronIcon.classList.remove('fa-chevron-up');
      chevronIcon.classList.add('fa-chevron-down');
    }
  });

  // Handle clicking outside the dropdown to close it and reset the icon
  document.addEventListener('click', function(event) {
    if (!dropdownToggle.contains(event.target) && !document.querySelector('.dropdown-menu').contains(event.target)) {
      chevronIcon.classList.remove('fa-chevron-up');
      chevronIcon.classList.add('fa-chevron-down');
    }
  });
});


// Get references to the rating and feedback elements
var ratingInput = document.getElementById('rating');
var feedbackInput = document.getElementById('feedback');
var submitBtn = document.getElementById('submitFeedbackBtn');

// Function to dynamically change the button text
function updateButtonText() {
    // Check if either rating or feedback has content
    if (ratingInput.value.trim() !== '' || feedbackInput.value.trim() !== '') {
        submitBtn.textContent = "Submit Feedback";
    } else {
        submitBtn.textContent = "Skip Feedback";
    }
}

// Attach event listeners to rating and feedback inputs to update button text
ratingInput.addEventListener('input', updateButtonText);
feedbackInput.addEventListener('input', updateButtonText);

// Selecting emoji, no initial selected emoji
document.querySelectorAll('.feedback li').forEach(entry => entry.addEventListener('click', e => {
    e.preventDefault();
    if (entry.classList.contains('active')) {
        entry.classList.remove('active');
        ratingInput.value = ''; // Clear the rating value if the emoji is deselected
    } else {
        document.querySelector('.feedback li.active')?.classList.remove('active');
        entry.classList.add('active');
        ratingInput.value = entry.getAttribute("data-value"); // Set the rating value to the selected emoji
    }
    updateButtonText(); // Explicitly call updateButtonText after updating the rating value
}));

const textarea = document.getElementById('feedback');
    const charLimitMessage = document.getElementById('charLimitMessage');

    textarea.addEventListener('input', function () {
      if (textarea.value.length >= 500) {
        charLimitMessage.style.display = 'block';
      } else {
        charLimitMessage.style.display = 'none';
      }
    });

// Call updateButtonText on DOMContentLoaded to set the initial state correctly
document.addEventListener("DOMContentLoaded", updateButtonText);
