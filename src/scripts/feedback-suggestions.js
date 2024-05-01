 
 // Get references to the rating and feedback elements
 const ratingInput = document.getElementById('rating');
 const feedbackInput = document.getElementById('feedback');
 const submitBtn = document.getElementById('submitFeedbackBtn');

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
 if (entry.classList.contains('active')) {
     entry.classList.remove('active');
 } else {
     document.querySelector('.feedback li.active')?.classList.remove('active');
     entry.classList.add('active');
 }
 e.preventDefault();
}));

// Add value of the rating, corresponding with the emoji selected
document.addEventListener("DOMContentLoaded", function() {
   var feedbackOptions = document.querySelectorAll(".feedback li");

   feedbackOptions.forEach(function(option) {
       option.addEventListener("click", function() {
           var value = this.getAttribute("data-value");
           document.getElementById("rating").value = value;
       });
   });
});
