// toggle button for voting guidelines
document.getElementById("toggleButton").addEventListener("click", function() {
    this.classList.add("clicked");
    document.getElementById("title").classList.add("main-bg-color");
    setTimeout(function() {
        // Remove the 'clicked' class from the button
        document.getElementById("toggleButton").classList.remove("clicked");
        // Remove the 'main-bg-color' class from the title
        document.getElementById("title").classList.remove("main-bg-color");
    }, 1000);
});


function removeErrorAndBorder(inputElement, errorElement) {
    inputElement.addEventListener('input', function() {
        if (inputElement.value.trim() && errorElement) {
            inputElement.classList.remove('border', 'border-danger');
            errorElement.parentNode.removeChild(errorElement);
        }
    });
}
function validateForm(event) {
    var voteForm = document.getElementById('voteForm');
    var reminders = voteForm.querySelectorAll('.reminder');
    var isValid = true;
    var scrollToReminder = null;
    var selectedCandidateHTML = '';
    var pairCounter = 0;

    // Check if the voter name and student number fields exist
    var voterNameInput = document.getElementById('voter_name');
    var studentNumInput = document.getElementById('student_num');

    // Regular expressions
    var studentNumRegex = /^\d{4}-\d{5}-[A-Z]{2}-\d$/;
    var voterNameRegex = /^[A-Za-z.,\-\s]+$/;

    if (voterNameInput !== null) {
        var voterNameError = document.getElementById('voterNameError');
        removeErrorAndBorder(voterNameInput, voterNameError);

        if (!voterNameInput.value.trim()) {
            if (!voterNameError) {
                var errorText = document.createElement('div');
                errorText.id = 'voterNameError';
                errorText.classList.add('text-danger', 'mt-2', 'ps-2');
                errorText.innerHTML = "<i>This field is required.</i>";
                voterNameInput.parentNode.appendChild(errorText);
            }
            voterNameInput.classList.add('border', 'border-danger');
            isValid = false;
            scrollToReminder = document.querySelector('.reminder-student');
        } else if (!voterNameRegex.test(voterNameInput.value.trim())) {
            if (!voterNameError) {
                var errorText = document.createElement('div');
                errorText.id = 'voterNameError';
                errorText.classList.add('text-danger', 'mt-2', 'ps-2');
                errorText.innerHTML = "<i>Name does not exist.</i>";
                voterNameInput.parentNode.appendChild(errorText);
            }
            voterNameInput.classList.add('border', 'border-danger');
            isValid = false;
            scrollToReminder = document.querySelector('.reminder-student');
        } else {
            if (voterNameError) {
                voterNameInput.parentNode.removeChild(voterNameError);
            }
            voterNameInput.classList.remove('border', 'border-danger');
        }
    }

    if (studentNumInput !== null) {
        var studentNumError = document.getElementById('studentNumError');
        removeErrorAndBorder(studentNumInput, studentNumError);

        if (!studentNumInput.value.trim()) {
            if (!studentNumError) {
                var errorText = document.createElement('div');
                errorText.id = 'studentNumError';
                errorText.classList.add('text-danger', 'mt-2', 'ps-2');
                errorText.innerHTML = "<i>This field is required.</i>";
                studentNumInput.parentNode.appendChild(errorText);
            }
            studentNumInput.classList.add('border', 'border-danger');
            isValid = false;
            if (!scrollToReminder) {
                scrollToReminder = document.querySelector('.reminder-student');
            }
        } else if (!studentNumRegex.test(studentNumInput.value.trim())) {
            if (!studentNumError) {
                var errorText = document.createElement('div');
                errorText.id = 'studentNumError';
                errorText.classList.add('text-danger', 'mt-2', 'ps-2');
                errorText.innerHTML = "<i>Student number does not exist.</i>";
                studentNumInput.parentNode.appendChild(errorText);
            }
            studentNumInput.classList.add('border', 'border-danger');
            isValid = false;
            if (!scrollToReminder) {
                scrollToReminder = document.querySelector('.reminder-student');
            }
        } else {
            if (studentNumError) {
                studentNumInput.parentNode.removeChild(studentNumError);
            }
            studentNumInput.classList.remove('border', 'border-danger');
        }
    }

    reminders.forEach(function(reminder) {
        var radioButtons = reminder.querySelectorAll('input[type="radio"]');
        var radioButtonChecked = false;

        radioButtons.forEach(function(radioButton) {
            if (radioButton.checked) {
                radioButtonChecked = true;
                var positionTitle = reminder.getAttribute('data-position-title');
                var candidateName = 'ABSTAINED';

                if (radioButton.value !== '') {
                    candidateName = radioButton.parentNode.querySelector('div.ps-4 > div.font-weight2').textContent.trim();
                }

                var candidateHTML = candidateName ? '<div>' + candidateName + '</div>' : '';
                var imageSrc;

                if (candidateName === 'ABSTAINED') {
                    imageSrc = 'images/candidate-profile/placeholder.png';
                } else {
                    imageSrc = reminder.querySelector('img').getAttribute('src');
                }

                if (pairCounter % 2 === 0) {
                    selectedCandidateHTML += '<div class="row ms-4">';
                }

                selectedCandidateHTML += '<div class="col-lg-6 col-md-12 col-sm-12 pb-lg-3 pb-3"><img src="' + imageSrc + '" width="80px" height="80px" style="display: inline-block; vertical-align: middle;border-radius: 10px; border: 2px solid #ccc;">' +
                    '<div class="ps-4" style="display: inline-block; vertical-align: middle; "><b><div class="main-color">' + candidateHTML + '</div></b><div style="font-size:12px"><b>' +
                    positionTitle.toUpperCase() + '</b></div></div>' + '</div>';

                pairCounter++;

                if (pairCounter % 2 === 0) {
                    selectedCandidateHTML += '</div>';
                }
            }
        });

        var reminderError = reminder.querySelector('.text-danger');

        if (!radioButtonChecked) {
            if (!reminderError) {        
                var requiredText = document.createElement('div');
                requiredText.classList.add('text-danger', 'mt-4', 'ps-4', 'ms-4', 'me-4');
                requiredText.innerHTML = "<span><i>This field is required. Please select one (1) candidate or click ABSTAIN.</i></span>";

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

    if (!isValid) {
        event.preventDefault();
        if (scrollToReminder) {
            scrollToReminder.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else {
        event.preventDefault();
        $('#confirmationModal').modal('show');
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

// Handle the confirmation of the vote
document.getElementById('submitModalButton').addEventListener('click', function() {
    var voteForm = document.getElementById('voteForm');
    var formData = $(voteForm).serialize();

    $.ajax({
        type: 'POST',
        url: '../src/includes/insert-vote.php',
        data: formData,
        success: function(response) {
            // Hide the confirmation modal
            $('#confirmationModal').modal('hide');
            // Show the success modal
            $('#voteSubmittedModal').modal('show');
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error(xhr.responseText);
        }
    });
});

// Add alert when user tries to refresh the page or close the browser
let formChanged = false;

document.getElementById('voteForm').addEventListener('change', function() {
  formChanged = true;
});

window.addEventListener('beforeunload', function (e) {
  if (formChanged) {
    const confirmationMessage = ' ';
    e.returnValue = confirmationMessage;
    return confirmationMessage;
  }
});

// Add an event listener to the "Give Feedback" button to remove the beforeunload event
document.getElementById('giveFeedbackbtn').addEventListener('click', function() {
  formChanged = false; // Reset the flag
  window.removeEventListener('beforeunload', function (e) {
    if (formChanged) {
      const confirmationMessage = ' ';
      e.returnValue = confirmationMessage;
      return confirmationMessage;
    }
  });
});

function resetForm() {
    document.querySelectorAll('input[type="radio"]').forEach((radio) => {
    radio.checked = false;
    });
    document.querySelectorAll('input[type="text"]').forEach((textInput) => {
    textInput.value = '';
    });
}

