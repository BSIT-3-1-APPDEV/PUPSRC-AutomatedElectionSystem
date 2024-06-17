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
