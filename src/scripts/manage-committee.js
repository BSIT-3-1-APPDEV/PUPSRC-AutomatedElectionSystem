$(document).ready(function () {
  // Check the stored visibility state on page load
  var isDeleteModeActive = localStorage.getItem('isDeleteModeActive') === 'true';
  if (isDeleteModeActive) {
    $(".voterCheckbox, .delete-actions").show();
  } else {
    $(".voterCheckbox, .delete-actions").hide();
  }

  $("#deleteBtn").click(function () {
    $(".voterCheckbox, .delete-actions").toggle();
    $("#selectAllCheckbox").prop("checked", false);
    updateDeleteSelectedButtonState();

    // Store the visibility state in localStorage
    var isVisible = $(".voterCheckbox").is(":visible");
    localStorage.setItem('isDeleteModeActive', isVisible);
  });

  $("#selectAllCheckbox").click(function () {
    $(".voterCheckbox").prop("checked", this.checked);
    updateDeleteSelectedButtonState();
  });

  $(".voterCheckbox").change(function () {
    updateDeleteSelectedButtonState();
  });

  function updateDeleteSelectedButtonState() {
    var checkedCount = $(".voterCheckbox:checked").length;
    $("#deleteSelectedBtn").prop("disabled", checkedCount === 0);
    $("#cancelBtn").prop("disabled", checkedCount === 0);
  }

  // Open the delete modal when the "Delete Selected" button is clicked
  $("#deleteSelectedBtn").click(function () {
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
$('#rejectModal').on('show.bs.modal', function () {
  overlay.style.display = 'block';
});

// Hide the overlay when the delete modal is hidden
$('#rejectModal').on('hidden.bs.modal', function () {
  overlay.style.display = 'none';
});

// Close the delete modal and overlay when clicked outside or on the "Cancel" button
window.addEventListener('click', function (event) {
  if (event.target === deleteModal || event.target === overlay) {
    $('#rejectModal').modal('hide');
  }
});

cancelBtn.addEventListener('click', function () {
  $('#rejectModal').modal('hide');
});

// Enable/disable the "Delete Selected" button based on checkbox selections
voterCheckboxes.forEach(function (checkbox) {
  checkbox.addEventListener('change', function () {
    const checkedCheckboxes = Array.from(voterCheckboxes).filter(function (checkbox) {
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


//------------SEARCH AND PAGINATION-------------//

function formatRoleString(roleString) {
  const words = roleString.split('_');
  const capitalizedWords = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
  return capitalizedWords.join(' ');
}

function loadPage(tableId, paginationId, ajaxUrl, page, searchTerm = "", sortBy = "", sortOrder = "") {
  console.log(`Loading page ${page} for ${tableId} with search term: "${searchTerm}", sortBy: "${sortBy}", sortOrder: "${sortOrder}"`);
  $.ajax({
    url: searchTerm ? "submission_handlers/search-committee.php" : ajaxUrl,
    type: "GET",
    data: { page: page, search: searchTerm, sort_by: sortBy, sort_order: sortOrder },
    success: function (response) {
      console.log(`Response received for page ${page}:`, response);  // Log the response
      const data = JSON.parse(response);
      const voters = data.voters;
      const totalRows = data.totalRows;
      const totalPages = Math.ceil(totalRows / 5); // Assuming the limit is 5

      console.log(`Total rows: ${totalRows}, Total pages: ${totalPages}`);

      const tbody = $(`#${tableId} tbody`);
      tbody.empty();

      if (voters.length === 0) {
        tbody.append(`
          <tr>
            <td colspan="4">
              <div class="pt-4 col-md-12 no-registration text-center">
                <img src="images/resc/not-found.png" class="not-found-illus">
                <p class="fw-bold spacing-6 black">No records found</p>
                <p class="spacing-3 pt-1 black">Maybe try a different keyword?</p>
              </div>
            </td>
          </tr>
        `);
      } else {
        voters.forEach((voter) => {
          const date = new Date(voter.acc_created);
          const formattedDate = date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          });

          const row = `
            <tr>
               <td class="col-md-1 text-center">
                
              </td>
              <td class="col-md-3 text-center"><a href="account-details.php?voter_id=${voter.voter_id}">${voter.first_name} ${voter.middle_name} ${voter.last_name} ${voter.suffix}</a></td>
              <td class="col-md-3 text-center">
                <span class="role-background ${voter.role.toLowerCase()} ${voter.role === 'head_admin' ? 'head-admin' : ''}">${formatRoleString(voter.role)}</span>
              </td>
              <td class="col-md-4 text-center">
                <span>${formattedDate}</span>
              </td>
            </tr>
          `;

          tbody.append(row);
        });
      }

      // Initial generation of pagination (when page is open/reloads)
      generatePagination(paginationId, totalPages, page, tableId, ajaxUrl, searchTerm);
    },
    error: function (error) {
      console.error(`Error loading page ${page} for ${tableId} with search term: "${searchTerm}", sortBy: "${sortBy}", sortOrder: "${sortOrder}"`, error);
    },
  });
}

$(document).ready(function () {
  // Load initial page without sorting
  loadPage('voterTable', 'pagination', 'submission_handlers/fetch-committee.php', 1);

  // Event listeners for sort links
  $('.sort-link').on('click', function (e) {
    e.preventDefault();
    const sortBy = $(this).data('sort');
    const sortOrder = $(this).data('order');
    // Load page with sorting parameters
    loadPage('voterTable', 'pagination', 'submission_handlers/fetch-committee.php', 1, $('#searchInput').val(), sortBy, sortOrder);
  });
});


//---------- PAGINATION------------ //
function generatePagination(paginationId, totalPages, currentPage, tableId, ajaxUrl, searchTerm = "") {
  console.log(`Generating pagination for ${tableId} (Page ${currentPage} of ${totalPages})`); // Debug

  // Sort By dropdown items
  $(`#${paginationId} .dropdown-menu a`).on('click', function (e) {
    e.preventDefault();
    const sortColumn = $(this).data('sort');
    const sortOrder = $(this).data('order');

    // Call the loadPage function with the new sort parameters
    loadPage(tableId, paginationId, ajaxUrl, currentPage, searchTerm, sortColumn, sortOrder);
  });

  // Removes the pagination if records are empty
  if (totalPages === 0) {
    $(`#${paginationId}`).empty(); // Clear the pagination
    return; // Exit the function
  }

  const pagination = $(`#${paginationId}`);
  pagination.empty();

  // Previous arrow
  const prevArrowClass = currentPage === 1 ? "disabled-arrow" : "";
  const pointerPrevNone = currentPage === 1 ? "pe-none" : "";
  const prevPage = currentPage > 1 ? currentPage - 1 : 1;
  const prevItem = `
        <li class="page-item ${pointerPrevNone}" id="left-arrow-page">
            <a href="#" class="page-link" data-page="${prevPage}">
                <span class="fas fa-chevron-left ${prevArrowClass}"></span>
            </a>
        </li>`;
  pagination.append(prevItem);

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    const activeClass = i === currentPage ? "active" : "";
    const pageItem = `
            <li class="page-item ${activeClass}">
                <a href="#" class="page-link" data-page="${i}">${i}</a>
            </li>`;
    pagination.append(pageItem);
  }

  // Next arrow
  const nextArrowClass = currentPage === totalPages ? "disabled-arrow" : "";
  const pointerNextNone = currentPage === totalPages ? "pe-none" : "";
  const nextPage = currentPage < totalPages ? currentPage + 1 : totalPages;
  const nextItem = `
        <li class="page-item ${pointerNextNone}" id="right-arrow-page">
            <a href="#" class="page-link" data-page="${nextPage}">
                <span class="fas fa-chevron-right ${nextArrowClass}"></span>
            </a>
        </li>`;
  pagination.append(nextItem);

  // Attach click events
  $(`#${paginationId} .page-link`)
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      const page = $(this).data("page");
      console.log(`Page link clicked: ${page}`);
      loadPage(tableId, paginationId, ajaxUrl, page, searchTerm);
    });
}

// -- RENDERING OF THE PAGES
$(document).ready(function () {
  const currentPage = 1;

  // Load initial data
  loadPage(
    "voterTable",
    "committeePagination",
    "submission_handlers/fetch-committee.php",
    currentPage,
    "" // Initial search term (empty)
  );

  // Search event listener
  $("#searchInput").on("input", function () {
    const searchTerm = $(this).val();
    if (searchTerm !== "") {
      loadPage(
        "voterTable",
        "committeePagination",
        "submission_handlers/search-committee.php",
        currentPage,
        searchTerm
      );
    } else {
      loadPage(
        "voterTable",
        "committeePagination",
        "submission_handlers/fetch-committee.php",
        currentPage,
        ""
      );
    }
  });
});


