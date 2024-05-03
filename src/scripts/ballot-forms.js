
function validateForm(event) {
    var voteForm = document.getElementById('voteForm');
    var reminders = voteForm.querySelectorAll('.reminder');
    var isValid = true;
    var scrollToReminder = null;
    var selectedCandidateHTML = '';

    var pairCounter = 0;
    reminders.forEach(function(reminder) {
        var radioButtons = reminder.querySelectorAll('input[type="radio"]');
        var radioButtonChecked = false;

        radioButtons.forEach(function(radioButton) {
            if (radioButton.checked) {
                radioButtonChecked = true;
                // get the selected position and candidates
                var positionTitle = reminder.getAttribute('data-position-title');
                var candidateName = 'ABSTAINED';

                if (radioButton.value !== '') {
                    candidateName = radioButton.parentNode.querySelector('div.ps-4 > div.font-weight2').textContent.trim(); // Get only the full name
                }

                
                var candidateHTML = candidateName ? '<div>' + candidateName + '</div>' : ''; // Check if candidate name is not empty
                var imageSrc = reminder.querySelector('img').getAttribute('src'); // Get candidate image source

                // Wrap each pair in a row
                if (pairCounter % 2 === 0) {
                    selectedCandidateHTML += '<div class="row ms-4">';
                }

                // Add the image and pair to the row
                selectedCandidateHTML += '<div class="col-lg-6 pb-sm-3"><img src="' + imageSrc + '" width="80px" height="80px" style="display: inline-block; vertical-align: middle;border-radius: 10px; border: 2px solid #ccc;">' +
                    '<div class="ps-4" style="display: inline-block; vertical-align: middle; "><b><div class="main-color">' + candidateHTML + '</div></b><div style="font-size:12px"><b>' +
                    positionTitle.toUpperCase() + '</b></div></div>' + '</div>';

                pairCounter++;


        // Close the row after every second pair
        if (pairCounter % 2 === 0) {
            selectedCandidateHTML += '</div><br>'; // Close the row
        }
    }
});

var reminderError = reminder.querySelector('.text-danger');

        if (!radioButtonChecked) {
            if (!reminderError) {
                var requiredText = document.createElement('div');
                requiredText.classList.add('text-danger', 'mt-4', 'ps-4');
                requiredText.innerHTML = "<i>This field is required. Please select one (1) candidate or click ABSTAIN.</i>";
                reminder.insertBefore(requiredText, reminder.firstChild);
            }

            reminder.classList.add('border', 'border-danger');
            isValid = false;
            if (!scrollToReminder) {
                scrollToReminder = reminder;
            }
        } else {
            if (reminderError) {
                reminder.removeChild(reminderError);
            }
            reminder.classList.remove('border', 'border-danger');
        }
    });

    if (!isValid && scrollToReminder) {
        event.preventDefault(); // Prevent form submission
        scrollToReminder.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        // If all fields are valid, display the modal with form preview
        event.preventDefault(); // Prevent form submission

        $('#confirmationModal').modal('show');

        /*document.getElementById('confirmationModal').classList.add('show');
        document.getElementById('confirmationModal').style.display = 'block';
        document.body.classList.add('modal-open');
        document.getElementById('confirmationModal').setAttribute('aria-modal', true);
        document.getElementById('confirmationModal').setAttribute('aria-hidden', false);
        document.getElementById('confirmationModal').setAttribute('role', 'dialog'); */

        document.getElementById('selectedCandidate').innerHTML = selectedCandidateHTML;
    }
}

document.getElementById('voteForm').addEventListener('submit', validateForm);

// Dynamically remove the error message if a radio button is once selected
var radioButtons = document.querySelectorAll('input[type="radio"]');
radioButtons.forEach(function(radioButton) {
    radioButton.addEventListener('change', function() {
        var reminder = this.closest('.reminder');
        var reminderError = reminder.querySelector('.text-danger');
        if (reminderError) {
            reminder.removeChild(reminderError);
            reminder.classList.remove('border', 'border-danger');
        }
    });
});

// Submit the form when the "Submit Vote" button is clicked
document.getElementById('submitModalButton').addEventListener('click', function() {
    document.getElementById('voteForm').submit();

});






function resetForm() {
document.querySelectorAll('input[type="radio"]').forEach((radio) => {
  radio.checked = false;
});
document.querySelectorAll('input[type="text"]').forEach((textInput) => {
  textInput.value = '';
});
}


// Function to show the vote submitted modal
function showVoteSubmittedModal() {
    // First, create the backdrop element
var backdrop = document.createElement('div');
backdrop.classList.add('modal-backdrop');
document.body.appendChild(backdrop);

// Then, add classes and attributes to the modal
var modal = document.getElementById('voteSubmittedModal');
modal.classList.add('show');
modal.style.display = 'block';
document.body.classList.add('modal-open');
modal.setAttribute('aria-modal', true);
modal.setAttribute('aria-hidden', false);
modal.setAttribute('role', 'dialog');
//$('#voteSubmittedModal').modal('show');
}

// Close modals when clicking on the close button
var closeButtons = document.getElementsByClassName('btn-close');
for (var i = 0; i < closeButtons.length; i++) {
closeButtons[i].addEventListener('click', function() {
    var modal = this.closest('.modal');
    $(modal).modal('hide');
});
}

// Show the vote submitted modal when the page loads (if redirected with success parameter)
window.onload = function() {
var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    showVoteSubmittedModal();
} else {
    $(document).ready(() => {
        $('#greetModal').modal('show');
    }); 
}
};
