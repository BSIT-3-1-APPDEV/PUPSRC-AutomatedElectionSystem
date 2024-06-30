      
                $(document).ready(function() {
                    function isAnyCheckboxChecked() {
    return $('.select-checkbox:checked').length > 0;
}
// Event listener for the Close button in the delete success modal
$('#refreshPageBtn').on('click', function() {
    // Refresh the page
    location.reload();
});
$('#refreshPageBtn2').on('click', function() {
    // Refresh the page
    location.reload();
});
});
$(document).ready(function() {
$('.email-link').on('click', function(event) {
    event.preventDefault();

    var voterId = $(this).data('voter-id');

    $.ajax({
        type: 'POST',
        url: 'submission_handlers/recycle-bin-admin-modal.php',
        data: { voter_id: voterId },
        dataType: 'json',
        success: function(response) {
            if (response.cor) {
                var formattedCorLink = 'user_data/<?php echo $org_name; ?>/cor/' + response.cor;
                $('#modal-voter-id').text(voterId);
                $('#pdfViewer').attr('src', formattedCorLink); // Set src attribute of pdfViewer
                $('#modal-download-link').attr('href', formattedCorLink); // Set href attribute of modal-download-link
                $('#modal-email').text(response.email);
                
                // Format status updated date
                var statusUpdatedDate = new Date(response.status_updated);
                var formattedStatusUpdated = 'Deleted at: ' + statusUpdatedDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' | ' + statusUpdatedDate.toLocaleDateString([], { month: 'short', day: '2-digit', year: 'numeric' });
                $('#modal-status-updated').text(formattedStatusUpdated);
                
                // Display role and name details
                var name = (response.last_name + ', ' + response.first_name + ' ' + response.middle_name + ' ' + response.suffix).toUpperCase();


                $('#modal-name').text(name);
                var formattedRole;
                if (response.role == 'head_admin') {
                    formattedRole = 'Head Admin';
                } else if (response.role == 'admin') {
                    formattedRole = 'Admin';
                } else {
                    formattedRole = response.role; // Handle other roles if needed
                }
                $('#modal-role').text(formattedRole);
                
                $('#modal-acc-created').text(response.acc_created);
                $('#voterDetailsModal').modal('show');
            } else {
                alert(response.error || 'An error occurred');
            }
        },
        error: function() {
            alert('An error occurred while fetching the data');
        }
    });
});
});




       
$(document).ready(function () {
var deleteModeActive = false;
var restoreModeActive = false;

function toggleCheckboxesVisibility(show) {
    var checkboxes = $('.select-checkbox');
    var selectAllCheckbox = $('#selectAllCheckbox');
    if (show) {
        checkboxes.show();
    } else {
        checkboxes.hide();
        checkboxes.prop('checked', false); // Uncheck all checkboxes when hiding
        selectAllCheckbox.prop('checked', false); // Uncheck the "Select All" checkbox
    }
}

function hideDeleteElements() {
    $('#cancelDelete').hide();
    $('#deleteSelectedbtn').hide();
    $('#deleteBtn').removeClass('btn-gray');
    $('.select-checkbox').removeClass('checkbox-red');
    $('#selectAllCheckbox').removeClass('checkbox-red');
    $('#deleteSelectedbtn').prop('disabled', true);
}

function hideRestoreElements() {
    $('#cancelRestore').hide();
    $('#restoreSelectedbtn').hide();
    $('#restoreBtn').removeClass('btn-gray');
    $('.select-checkbox').removeClass('checkbox-blue');
    $('#selectAllCheckbox').removeClass('checkbox-blue');
    $('#restoreSelectedbtn').prop('disabled', true);
}

function showDeleteElements() {
    $('#cancelDelete').show();
    $('#deleteSelectedbtn').show();
    $('#deleteBtn').addClass('btn-gray');
    $('.select-checkbox').addClass('checkbox-red');
    $('#selectAllCheckbox').addClass('checkbox-red');
}

function showRestoreElements() {
    $('#cancelRestore').show();
    $('#restoreSelectedbtn').show();
    $('#restoreBtn').addClass('btn-gray');
    $('.select-checkbox').addClass('checkbox-blue');
    $('#selectAllCheckbox').addClass('checkbox-blue');
}

function handleCancelActions() {
    deleteModeActive = false;
    restoreModeActive = false;
    hideDeleteElements();
    hideRestoreElements();
    toggleCheckboxesVisibility(false);
    $('.status-dropdown').prop('disabled', false);
    $('#selectAllCheckbox').hide();
}

function showWarningModal() {
    $('#warningModal').modal('show');
}

function isAnyCheckboxChecked() {
    return $('.select-checkbox:checked').length > 0;
}

function updateButtonVisibility() {
    if (isAnyCheckboxChecked()) {
        if (deleteModeActive) {
            $('#deleteSelectedbtn').prop('disabled', false);
        }
        if (restoreModeActive) {
            $('#restoreSelectedbtn').prop('disabled', false);
        }
    } else {
        $('#deleteSelectedbtn').prop('disabled', true);
        $('#restoreSelectedbtn').prop('disabled', true);
    }
}

$('.select-checkbox').on('change', function () {
    updateButtonVisibility();
});

$('#deleteBtn').on('click', function () {
    if (restoreModeActive) {
        showWarningModal();
        return;
    }

    // Toggle delete mode
    deleteModeActive = !deleteModeActive;

    if (deleteModeActive) {
        showDeleteElements();
        hideRestoreElements();
    } else {
        hideDeleteElements();
    }

    toggleCheckboxesVisibility(deleteModeActive || restoreModeActive);
    $('#selectAllCheckbox').toggle(deleteModeActive || restoreModeActive);
    $('#selectAllCheckbox').data('checked', false);
    $('.status-dropdown').prop('disabled', deleteModeActive || restoreModeActive);

    if (!deleteModeActive && !restoreModeActive) {
        $('#selectAllCheckbox').hide();
    }
});

$('#restoreBtn').on('click', function () {
    if (deleteModeActive) {
        showWarningModal();
        return;
    }

    // Toggle restore mode
    restoreModeActive = !restoreModeActive;

    if (restoreModeActive) {
        showRestoreElements();
        hideDeleteElements();
    } else {
        hideRestoreElements();
    }

    toggleCheckboxesVisibility(deleteModeActive || restoreModeActive);
    $('#selectAllCheckbox').toggle(deleteModeActive || restoreModeActive);
    $('#selectAllCheckbox').data('checked', false);
    $('.status-dropdown').prop('disabled', deleteModeActive || restoreModeActive);

    if (!deleteModeActive && !restoreModeActive) {
        $('#selectAllCheckbox').hide();
    }
});

$('#cancelDelete').on('click', function () {
    handleCancelActions();
});

$('#cancelRestore').on('click', function () {
    handleCancelActions();
});

$('#selectAllCheckbox').on('change', function () {
    $('.select-checkbox').prop('checked', $(this).prop('checked'));
    updateButtonVisibility(); // Update button visibility when "Select All" checkbox changes
});

// Ensure buttons are hidden initially
$('#deleteSelectedbtn, #cancelDelete, #restoreSelectedbtn, #cancelRestore').hide();
$('#deleteSelectedbtn, #restoreSelectedbtn').prop('disabled', true);
$('#selectAllCheckbox').hide();
});

$(document).ready(function() {
var rowsPerPage = 5;  // Number of rows to show per page
var paginationControls = $('#pagination-controls');

function updatePagination(rows) {
    var rowsCount = rows.length;
    var pageCount = Math.ceil(rowsCount / rowsPerPage);
    paginationControls.empty();
    for (var i = 1; i <= pageCount; i++) {
        paginationControls.append('<li class="page-item"><a href="#" class="page-link">' + i + '</a></li>');
    }

    paginationControls.find('li').eq(0).addClass('active');

    paginationControls.find('a.page-link').on('click', function(e) {
        e.preventDefault();
        var pageNum = $(this).text();
        if (!$(this).parent().hasClass('disabled')) {
            displayPage(parseInt(pageNum), rows);
        }
    });

    $('#previous-page').on('click', function(e) {
        e.preventDefault();
        var currentPage = paginationControls.find('li.active').index() + 1;
        if (currentPage > 1) {
            displayPage(currentPage - 1, rows);
        }
    });

    $('#next-page').on('click', function(e) {
        e.preventDefault();
        var currentPage = paginationControls.find('li.active').index() + 1;
        var pageCount = Math.ceil(rows.length / rowsPerPage);
        if (currentPage < pageCount) {
            displayPage(currentPage + 1, rows);
        }
    });

    displayPage(1, rows);  // Display the first page initially
}

function displayPage(pageNumber, rows) {
    var start = (pageNumber - 1) * rowsPerPage;
    var end = start + rowsPerPage;
    rows.hide();
    rows.slice(start, end).show();
    paginationControls.find('li').removeClass('active');
    paginationControls.find('li').eq(pageNumber - 1).addClass('active');

    updateButtonsState(pageNumber, rows.length);
}

function updateButtonsState(currentPage, rowsCount) {
    var pageCount = Math.ceil(rowsCount / rowsPerPage);
    if (currentPage <= 1) {
        $('#previous-page').addClass('disabled');
    } else {
        $('#previous-page').removeClass('disabled');
    }
    if (currentPage >= pageCount) {
        $('#next-page').addClass('disabled');
    } else {
        $('#next-page').removeClass('disabled');
    }
}
function filterRows() {
    var searchValue = $('#searchInput').val().toLowerCase();
    var allRows = $('.table tbody tr');
    var filteredRows = allRows.filter(function() {
        return $(this).text().toLowerCase().indexOf(searchValue) > -1;
    });

    allRows.hide(); // Hide all rows by default

    if (filteredRows.length === 0) {
        // Create a placeholder element for "No records found" message
        var noRecordsFoundMessage = $('<tr class="no-records-found text-center"><td colspan="4">No records found</td></tr>');
        $('.table tbody').append(noRecordsFoundMessage);
    } else {
        // Remove any existing "No records found" message
        $('.no-records-found').remove();

        // Show only the filtered rows
        filteredRows.show();
        displayPage(1, filteredRows);
        updatePagination(filteredRows);
    }
}

$('#searchInput').on('keyup', function() {
    filterRows();
});




$('.dropdown-item').on('click', function() {
    var sortOption = $(this).text().trim(); // Get the text of the clicked option
    sortTable(sortOption);
    filterRows(); // Apply search filter after sorting
});

function sortTable(sortOption) {
    var $table = $('.table');
    var rows = $table.find('tbody tr').get();

    rows.sort(function(a, b) {
        var keyA, keyB;

        switch (sortOption) {
            case 'Most to Fewest Days':
                keyA = parseInt($(a).children('td').eq(2).text().split(' ')[0]); // Extract the number of days from the text
                keyB = parseInt($(b).children('td').eq(2).text().split(' ')[0]);
                return keyB - keyA;
            case 'Fewest to Most Days':
                keyA = parseInt($(a).children('td').eq(2).text().split(' ')[0]); // Extract the number of days from the text
                keyB = parseInt($(b).children('td').eq(2).text().split(' ')[0]);
                return keyA - keyB;
            case 'A to Z (Ascending)':
                keyA = $(a).children('td').eq(1).text().toUpperCase();
                keyB = $(b).children('td').eq(1).text().toUpperCase();
                return keyA.localeCompare(keyB);
            case 'Z to A (Descending)':
                keyA = $(a).children('td').eq(1).text().toUpperCase();
                keyB = $(b).children('td').eq(1).text().toUpperCase();
                return keyB.localeCompare(keyA);
            default:
                return 0;
        }
    });

    $.each(rows, function(index, row) {
        $table.children('tbody').append(row);
    });
}

// Initialize pagination on page load
var allRows = $('.table tbody tr');
displayPage(1, allRows);
updatePagination(allRows);
});






$('#deleteSelectedbtn').on('click', function () {
            $('#deleteConfirmationModal').modal('show');
        });
        $('#restoreSelectedbtn').on('click', function () {
            $('#restoreConfirmationModal').modal('show');
        });


      

        // Handle delete confirmation button click
        $('#confirmDeleteButton').on('click', function () {
            // Perform the delete action here
            console.log('Items deleted');

            // Close the modal
            $('#deleteConfirmationModal').modal('hide');
        });
  
        $('#confirmRestoreBtn').on('click', function () {
    // Handle the restore action
    // You can add the actual restore functionality here
    
    $('#restoreConfirmationModal').modal('hide'); // Hide the modal after confirming
});

$(document).ready(function() {
var isHovered = false; // Flag to track if the delete button is hovered over

// Function to update the delete button state
function updateDeleteButtonState() {
    var inputValue = $('#confirmDeleteInput').val().trim();

    // Check if the input value is 'Confirm Delete'
    if (inputValue === 'Confirm Delete') {
        enableDeleteButton();
    } else {
        disableDeleteButton();
    }
}

// Function to enable the delete button
function enableDeleteButton() {
    $('#confirmDeleteButton').prop('disabled', false);
}

// Function to disable the delete button
function disableDeleteButton() {
    $('#confirmDeleteButton').prop('disabled', true);
}

// Call the function initially
updateDeleteButtonState();

// Hover functionality for Delete button
$('#confirmDeleteButton-container').hover(function() {
    isHovered = true;
    updateDeleteButtonState();
}, function() {
    isHovered = false;
    updateDeleteButtonState();
});

// Event listener for input changes
$('#confirmDeleteInput').on('input', function() {
    if (!isHovered) {
        updateDeleteButtonState();
    }
});

// Function to show the validation message and input error
function showValidationMessage() {
    $('#confirmDeleteInput').addClass('input-error');
    $('.validation-message').show();
}

// Function to hide the validation message and input error
function hideValidationMessage() {
    $('#confirmDeleteInput').removeClass('input-error');
    $('.validation-message').hide();
}

// Check validation on hover
$('#confirmDeleteButton-container').hover(function() {

    var inputValue = $('#confirmDeleteInput').val().trim();
    if (inputValue !== 'Confirm Delete') {
        showValidationMessage();
    }
}, function() {
    if (!isHovered) {
        hideValidationMessage();
    }
});
});




$(document).ready(function() {
    $('#showDeleteSuccessModal').on('click', function() {
        $('#warningModal').modal('show');
    });
});
