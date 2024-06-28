$(document).ready(function () {
  // Initial call to set the background color on page load
  updateBackgroundColor();

  // Event listener for when the select value changes
  $('#dropdown').on('change', updateBackgroundColor);

  // Function to update the background color of the select element
  function updateBackgroundColor() {
    var selectedValue = $('#dropdown').val();
    var color = selectedValue === 'admin' ? 'green' : (selectedValue === 'head_admin' ? 'blue' : '');

    $('#dropdown').css({
      'background-color': color,
      'color': '#fff' 
    });
  }
});

function changeButtonText(option) {
  const dropdownButton = document.getElementById('changeRole');
  dropdownButton.innerText = option.innerText;
  dropdownButton.click(); // Close the dropdown menu after selecting an option
};


function changeButtonText(elem, role, color) {
  var button = document.getElementById('changeRole');
  button.style.backgroundColor = color;
  button.style.color = 'white';
  button.textContent = elem.textContent;
};

// Move To Trashbin Modal Submit
$(document).ready(function () {
  $("#confirm-move").click(function (event) {
    event.preventDefault();
    var voter_id = $("#voter_id").val();

    $.ajax({
      url: "submission_handlers/move-to-trashbin.php",
      type: "POST",
      data: { voter_id: voter_id },
      success: function (response) {
        console.log("AJAX call successful");
        $("#rejectModal").modal("hide");
        $("#trashbinMoveDone").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed:", error);
        console.error(xhr.responseText);
      },
    });
  });
});

//Show & Hide Modal Functions
$(document).ready(function () {
  $("#reject-btn").click(function (event) {
    $("#rejectModal").modal("show");
  });
});

function cancelForm(event) {
  event.preventDefault();
}

function closeModal() {
  $("#rejectModal").modal("hide");
}

$(document).ready(function() {
  // Store the initial role
  var currentRole = $('#changeRole').text().trim().toLowerCase();

  $('.dropdown-item').click(function(e) {
    e.preventDefault();
    var newRole = $(this).data('role');
    var voterId = $('#voter_id').val();

    // Check if the new role is different from the current role
    if (newRole !== currentRole) {
      $.ajax({
        url: 'submission_handlers/update-role.php',
        type: 'POST',
        data: {
          voter_id: voterId,
          new_role: newRole
        },
        success: function(response) {
          console.log('Server response:', response);
          if (response.trim() === 'success') {
            // Update the button text and color
            changeButtonText(e.target, newRole, getRoleColor(newRole));
            // Show success modal
            $('#changeSuccessModal').modal('show');
            // Update the current role
            currentRole = newRole;
          } else {
            console.error('Error updating role:', response);
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('AJAX Error:', textStatus, errorThrown);
          console.log('Response Text:', jqXHR.responseText);
          console.log('Status Code:', jqXHR.status);
          console.log('Status Text:', jqXHR.statusText);
        }
      });
    } else {
      // If the same role is selected, do nothing (silently ignore)
      console.log('Same role selected, no action taken');
    }
  });
});

function changeButtonText(elem, role, color) {
  var button = document.getElementById('changeRole');
  button.style.backgroundColor = color;
  button.style.color = 'white';
  button.textContent = elem.textContent;
}

function getRoleColor(role) {
  switch(role) {
    case 'admin':
      return '#03C04A';
    case 'head_admin':
      return 'blue';
    default:
      return '#6c757d';
  }
}

function closeModal() {
  console.log('closeModal function called');
  $('#changeSuccessModal').modal('hide');
  // No need to reload the page as we're updating the button in real-time
}

function redirectToPage(url) {
  window.location.href = url;
}

function closeModal() {
  console.log('closeModal function called');
  window.location.reload();
}


