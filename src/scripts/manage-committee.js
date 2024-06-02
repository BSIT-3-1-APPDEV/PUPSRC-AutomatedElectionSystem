$(document).ready(function() {
    $("#deleteBtn").click(function() {
      $(".voterCheckbox, .delete-actions").toggle();
      $("#selectAllCheckbox").prop("checked", false);
      updateDeleteSelectedButtonState();
    });
  
    $("#selectAllCheckbox").click(function() {
      $(".voterCheckbox").prop("checked", this.checked);
      updateDeleteSelectedButtonState();
    });
  
    $(".voterCheckbox").change(function() {
      updateDeleteSelectedButtonState();
    });
  
    function updateDeleteSelectedButtonState() {
      var checkedCount = $(".voterCheckbox:checked").length;
      $("#deleteSelectedBtn").prop("disabled", checkedCount === 0);
      $("#cancelBtn").prop("disabled", checkedCount === 0);
    }
  
    // Open the delete modal when the "Delete Selected" button is clicked
    $("#deleteSelectedBtn").click(function() {
      $("#rejectModal").modal("show");
    });
  });
  
  // Select the necessary elements
  const deleteModal = document.getElementById('rejectModal');
  const cancelBtn = document.getElementById('cancelBtn');
  const voterCheckboxes = document.querySelectorAll('.voterCheckbox');
  const deleteActions = document.querySelector('.delete-actions');
  
  // Create the overlay element
  const overlay = document.createElement('div');
  overlay.classList.add('modal-overlay');
  document.body.appendChild(overlay);
  
  // Show the overlay when the delete modal is shown
  $('#rejectModal').on('show.bs.modal', function() {
    overlay.style.display = 'block';
  });
  
  // Hide the overlay when the delete modal is hidden
  $('#rejectModal').on('hidden.bs.modal', function() {
    overlay.style.display = 'none';
  });
  
  // Close the delete modal and overlay when clicked outside or on the "Cancel" button
  window.addEventListener('click', function(event) {
    if (event.target === deleteModal || event.target === overlay) {
      $('#rejectModal').modal('hide');
    }
  });
  
  cancelBtn.addEventListener('click', function() {
    $('#rejectModal').modal('hide');
  });
  
  // Enable/disable the "Delete Selected" button based on checkbox selections
  voterCheckboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      const checkedCheckboxes = Array.from(voterCheckboxes).filter(function(checkbox) {
        return checkbox.checked;
      });
      if (checkedCheckboxes.length > 0) {
        $("#deleteSelectedBtn").prop("disabled", false);
        deleteActions.style.display = 'block';
      } else {
        $("#deleteSelectedBtn").prop("disabled", true);
        deleteActions.style.display = 'none';
      }
    });
  });
  
  // Function to close the delete modal
  function closeModal() {
    $('#rejectModal').modal('hide');
  }
  
  // Function to redirect to a page
  function redirectToPage(page) {
    window.location.href = page;
  }

  