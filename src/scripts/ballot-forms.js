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

document.addEventListener('DOMContentLoaded', function() {
    // Function to limit checkbox selection based on max_votes
    function limitCheckboxSelection() {
        // Select all candidate checkboxes for voting
        var candidateCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="position["][value]');
        // Select all abstain checkboxes
        var abstainCheckboxes = document.querySelectorAll('.abstain-checkbox');

        candidateCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var positionId = this.name.match(/\[(.*?)\]/)[1]; // Extract position_id from checkbox name
                var maxVotes = parseInt(this.getAttribute('data-max-votes')); // Get max_votes for the position
                var checkedCandidateCheckboxes = document.querySelectorAll('input[type="checkbox"][name="position[' + positionId + '][]"]:checked'); // Checked candidate checkboxes for this position
                var abstainCheckbox = document.querySelector('#abstain_' + positionId); // Abstain checkbox for this position

                // If a candidate checkbox is checked, uncheck the abstain checkbox
                if (abstainCheckbox && this.checked) {
                    abstainCheckbox.checked = false;
                }

                // Check if the number of checked checkboxes exceeds max_votes
                if (checkedCandidateCheckboxes.length > maxVotes) {
                    this.checked = false; // Uncheck the current checkbox
                }
            });
        });

        abstainCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                var positionId = this.getAttribute('data-position-id'); // Extract position_id from data attribute
                var candidateCheckboxes = document.querySelectorAll('input[type="checkbox"][name="position[' + positionId + '][]"][value]'); // Candidate checkboxes for this position

                // Uncheck all candidate checkboxes if abstain is checked
                if (this.checked) {
                    candidateCheckboxes.forEach(function(candidateCheckbox) {
                        candidateCheckbox.checked = false;
                    });
                }
            });
        });
    }

    // Execute the function to limit checkbox selection once the document is loaded
    limitCheckboxSelection();
});

// Add event listeners to checkboxes and "ABSTAIN" radio button to update error messages
var reminders = document.querySelectorAll('.reminder');
reminders.forEach(function(reminder) {
    var checkboxes = reminder.querySelectorAll('input[type="checkbox"]');
    var abstainRadio = reminder.querySelector('input[type="radio"].abstain-checkbox');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateErrorState(reminder);
        });
    });

    if (abstainRadio) {
        abstainRadio.addEventListener('change', function() {
            updateErrorState(reminder);
        });
    }
});

function updateErrorState(reminder) {
    var checkboxes = reminder.querySelectorAll('input[type="checkbox"]');
    var abstainRadio = reminder.querySelector('input[type="radio"].abstain-checkbox');
    var isChecked = false;

    checkboxes.forEach(function(cb) {
        if (cb.checked) {
            isChecked = true;
        }
    });

    if (isChecked || (abstainRadio && abstainRadio.checked)) {
        var reminderError = reminder.querySelector('.text-danger');
        if (reminderError) {
            reminder.removeChild(reminderError);
        }
        reminder.classList.remove('border', 'border-danger');
    } else {
        var reminderError = reminder.querySelector('.text-danger');
        if (!reminderError) {
            var errorText = document.createElement('div');
            errorText.classList.add('text-danger', 'mt-4', 'ps-lg-4', 'ps-sm-2', 'ps-2', 'ms-2', 'ms-lg-4', 'ms-sm-2', 'me-4');
            errorText.innerHTML = "<span><i>This field is required. Please select one (1) candidate or click ABSTAIN.</i></span>";
            reminder.insertBefore(errorText, reminder.firstChild);
        }
        reminder.classList.add('border', 'border-danger');
    }
}

// Function to validate form
function validateForm(event) {
    event.preventDefault();
    var voteForm = document.getElementById('voteForm');
    var reminders = voteForm.querySelectorAll('.reminder');
    var isValid = true;
    var scrollToReminder = null;
    var selectedCandidateHTML = '';
    var pairCounter = 0;

    // Validate each position
    reminders.forEach(function(reminder) {
        updateErrorState(reminder);

        var checkboxes = reminder.querySelectorAll('input[type="checkbox"]');
        var abstainRadio = reminder.querySelector('input[type="radio"].abstain-checkbox');
        var isChecked = false;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
                var positionTitle = reminder.getAttribute('data-position-title');
                var candidateName = checkbox.parentNode.querySelector('div.ps-4 > div.font-weight2').textContent.trim();
                var candidateHTML = '<div>' + candidateName + '</div>';
                var imageSrc = checkbox.getAttribute('data-img-src');

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

        // Handle ABSTAIN option
        if (abstainRadio && abstainRadio.checked) {
            isChecked = true;
            var positionTitle = reminder.getAttribute('data-position-title');
            var candidateName = 'ABSTAINED';
            var candidateHTML = '<div>' + candidateName + '</div>';
            var imageSrc = 'images/resc/Abstained.png';

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

        // Handle no selection (neither checkboxes nor abstain radio)
        if (!isChecked) {
            isValid = false;
            if (!scrollToReminder) {
                scrollToReminder = reminder;
            }
        }
    });

    // Handle form submission
    if (!isValid) {
        if (scrollToReminder) {
            scrollToReminder.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else {
        $('#confirmationModal').modal('show');
        document.getElementById('selectedCandidate').innerHTML = selectedCandidateHTML;
    }
}

// Helper function to display input errors
function displayInputError(inputElement, errorId, errorMessage) {
    inputElement.classList.add('border', 'border-danger');
    if (!document.getElementById(errorId)) {
        var errorText = document.createElement('div');
        errorText.id = errorId;
        errorText.classList.add('text-danger', 'mt-2', 'ps-2');
        errorText.innerHTML = "<i>" + errorMessage + "</i>";
        inputElement.parentNode.appendChild(errorText);
    }
}

// Function to remove error message and border when input is corrected
function removeErrorAndBorder(inputElement) {
    inputElement.classList.remove('border', 'border-danger');
    var errorElement = inputElement.nextElementSibling;
    if (errorElement && errorElement.classList.contains('text-danger')) {
        errorElement.remove();
    }
}


// Add submit event listener to the form
document.getElementById('voteForm').addEventListener('submit', validateForm);


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

