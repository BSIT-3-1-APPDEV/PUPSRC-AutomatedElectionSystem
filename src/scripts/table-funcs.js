const limit = 5; // Number of accounts per page

// Global arrays to store selected IDs & flagging of status
let selectedPendingIds = [];
let selectedVerifiedIds = [];
let deletePendingState = false;
let deleteVerifiedState = false;

// -- FUNCTION: Loading of the table (shows the data)
function loadPage(
  tableId,
  paginationId,
  ajaxUrl,
  page,
  searchTerm = "",
  sort = ""
) {
  console.log(
    `Loading page: ${page}, Table ID: ${tableId}, Sort Order: ${sort}`
  );
  $.ajax({
    url: ajaxUrl,
    type: "GET",
    data: { page: page, search: searchTerm, sort: sort },
    success: function (response) {
      console.log(`Response received for ${tableId}:`, response);
      const data = JSON.parse(response);
      const voters = data.voters;
      const totalRows = data.totalRows;
      const totalPages = Math.ceil(totalRows / limit);

      const tbody = $(`#${tableId} tbody`);
      tbody.empty();

      if (data.hasOwnProperty("isEmpty") && data.isEmpty) {
        console.log("Hereee");
        $(".pagination").hide();
        tbody.append(`
                 <td colspan="3" class="pt-5">
                   <div class="pt-4 col-md-12 no-registration text-center">
                     <img src="images/resc/not-found.png" class="not-found-illus">
                     <p class="fw-bold spacing-6 black">No records found</p>
                     <p class="spacing-3 pt-1 black">Maybe try a different keyword?</p>
                   </div>
                 </td>
             `);
      } else if (voters.length === 0) {
        displayEmptyState(
          tableId,
          tableId === "pendingTable" ? "pending" : "verified"
        );
      } else {
        $(".pagination").show();
        voters.forEach((voter) => {
          const date = new Date(voter.acc_created);
          const formattedDate = date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          });

          let row = "";
          if (tableId === "pendingTable") {
            const isChecked = selectedPendingIds.includes(voter.voter_id)
              ? "checked"
              : "";
            row = `
                <tr>
                  <td class="col-md-1 text-center checkbox-delete-pending ${
                    deletePendingState ? "" : "d-none"
                  }">
                    <input type="checkbox" class="pendingCheckbox" data-id="${
                      voter.voter_id
                    }" ${isChecked}>
                  </td>
                  <td class="col-md-5 text-center text-truncate">
                    <a href="validate-voter.php?voter_id=${voter.voter_id}">${
              voter.email
            }</a>
                  </td>
                  <td class="col-md-6 text-center">${formattedDate}</td>
                </tr>`;
          } else if (tableId === "verifiedTable") {
            const isChecked = selectedVerifiedIds.includes(voter.voter_id)
              ? "checked"
              : "";
            const updated_date = new Date(voter.status_updated);
            const formattedUpdatedDate = updated_date.toLocaleDateString(
              "en-US",
              {
                year: "numeric",
                month: "long",
                day: "numeric",
              }
            );
            row = `
                <tr>
                  <td class="col-md-1 text-center checkbox-delete-verified ${
                    deleteVerifiedState ? "" : "d-none"
                  }">
                    <input type="checkbox" class="verifiedCheckbox" data-id="${
                      voter.voter_id
                    }" ${isChecked}>
                  </td>
                  <td class="col-md-3 text-center text-truncate">
                    <a href="voter-details.php?voter_id=${voter.voter_id}">${
              voter.email
            }</a>
                  </td>
                  <td class="col-md-3 text-center">
                    <span class="status-background active-status">${
                      voter.account_status.charAt(0).toUpperCase() +
                      voter.account_status.slice(1)
                    }</span>
                  </td>
                  <td class="col-md-3 text-center">${formattedUpdatedDate}</td>
                </tr>`;
          }
          tbody.append(row);
        });
      }

      // Re-check checkboxes based on stored selected IDs
      if (tableId === "pendingTable") {
        selectedPendingIds.forEach((id) => {
          $(`.pendingCheckbox[data-id="${id}"]`).prop("checked", true);
        });
      } else if (tableId === "verifiedTable") {
        selectedVerifiedIds.forEach((id) => {
          $(`.verifiedCheckbox[data-id="${id}"]`).prop("checked", true);
        });
      }

      generatePagination(
        paginationId,
        totalPages,
        page,
        tableId,
        ajaxUrl,
        searchTerm,
        sort
      );
    },
    error: function (error) {
      console.error(
        `Error loading page ${page} for ${tableId} with search term: "${searchTerm}"`,
        error
      );
    },
  });
}
// -- FUNCTION: Loading of the table (shows the data)



// ++ FUNCTION: Rechecking of Checkboxes
function recheckCheckboxes(tableId) {
  console.log("Rechecking checkboxes for table:", tableId);

  if (tableId === "pendingTable") {
    console.log("Selected pending IDs:", selectedPendingIds);
    selectedPendingIds.forEach((id) => {
      console.log("Checking checkbox for pending ID:", id);
      $(`.pendingCheckbox[data-id="${id}"]`).prop("checked", true);
    });
  } else if (tableId === "verifiedTable") {
    console.log("Selected verified IDs:", selectedVerifiedIds);
    selectedVerifiedIds.forEach((id) => {
      console.log("Checking checkbox for verified ID:", id);
      $(`.verifiedCheckbox[data-id="${id}"]`).prop("checked", true);
    });
  }
}
// -- FUNCTION: Rechecking of Checkboxes



// ++ FUNCTION: To generate the pagination
function generatePagination(
  paginationId,
  totalPages,
  currentPage,
  tableId,
  ajaxUrl,
  searchTerm = "",
  sort = ""
) {
  console.log(
    `Generating pagination for ${tableId} (Page ${currentPage} of ${totalPages})`
  ); // Debug

  // Removes the pagination if records are empty
  if (totalPages == 0) {
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
                  <span class="fas fa-chevron-left left-arrow-icon ${prevArrowClass}"></span>
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

      loadPage(tableId, paginationId, ajaxUrl, page, searchTerm, sort);
      recheckCheckboxes(tableId);
    });
}
// -- FUNCTION: To generate the pagination



// ++ RENDERING/SORTING OF THE PAGES
$(document).ready(function () {
  const currentPage = 1;
  let currentSortPending = "newest";
  let currentSortVerified = "newest";

  // Load initial data
  loadPage(
    "pendingTable",
    "pagination",
    "submission_handlers/fetch-pending.php",
    currentPage,
    "",
    currentSortPending
  );
  loadPage(
    "verifiedTable",
    "verified-pagination",
    "submission_handlers/fetch-verified.php",
    currentPage,
    "",
    currentSortVerified
  );

  // Search event listeners
  $("#searchPending").on("input", function () {
    const searchPendingTerm = $(this).val();
    if (searchPendingTerm != "") {
      loadPage(
        "pendingTable",
        "pagination",
        "submission_handlers/search-pending.php",
        currentPage,
        searchPendingTerm,
        currentSortPending
      );
    } else {
      loadPage(
        "pendingTable",
        "pagination",
        "submission_handlers/fetch-pending.php",
        currentPage,
        "",
        currentSortPending
      );
    }
  });

  $("#searchVerified").on("input", function () {
    const searchTerm = $(this).val();
    if (searchTerm != "") {
      loadPage(
        "verifiedTable",
        "verified-pagination",
        "submission_handlers/search-verified.php",
        currentPage,
        searchTerm,
        currentSortVerified
      );
    } else {
      loadPage(
        "verifiedTable",
        "verified-pagination",
        "submission_handlers/fetch-verified.php",
        currentPage,
        "",
        currentSortVerified
      );
    }
  });

  // Sort By
  $(".sort-by .dropdown-item").click(function () {
    const sort = $(this).data("sort");
    console.log("Sort value selected:", sort);

    let tableId, paginationId, ajaxUrl, sortValue;

    if (
      $(this).closest(".sort-by").find("button").attr("id") ===
      "dropdownMenuButtonPending"
    ) {
      tableId = "pendingTable";
      paginationId = "pagination";
      ajaxUrl = "submission_handlers/fetch-pending.php";
      sortValue = currentSortPending;
      currentSortPending = sort;
      console.log("Pending sort applied:", sort);
    } else {
      tableId = "verifiedTable";
      paginationId = "verified-pagination";
      ajaxUrl = "submission_handlers/fetch-verified.php";
      sortValue = currentSortVerified;
      currentSortVerified = sort;
      console.log("Verified sort applied:", sort);
    }

    // Load page with current page, search term (if any), and new sort value
    loadPage(tableId, paginationId, ajaxUrl, currentPage, "", sort);
  });
  // -- RENDERING/SORTING OF THE PAGES



  // ++ DELETE TOGGLE
  $(document).ready(function () {

    // Pending Table Delete Toggle
    $(".pending-delete-btn").click(function () {
      // Toggle visibility and classes for pending table headers
      deletePendingState = true;
      checkPendingCheckboxes();
      $(".checkbox-delete-pending").removeClass("d-none");
      $(".checkbox-all-pending").removeClass("d-none");
      $("#pendingTable th.checkbox-pending")
        .removeClass("d-none")
        .addClass("tl-left");
      $("#pendingTable th.del-center").removeClass("tl-left");
      $(".final-delete-btn-pending").toggleClass("d-none");
      $(".cancel-pending").toggleClass("d-none");

      // Disable the pending delete button and enable the cancel button
      $(this).prop("disabled", true).addClass("light-gray");
      $(".cancel-pending").prop("disabled", false);
    });

    $(".cancel-pending").click(function () {
      // Toggle visibility and classes for pending table headers back
      deletePendingState = false;
      $(".checkbox-delete-pending").addClass("d-none");
      $(".checkbox-all-pending").addClass("d-none");
      $("#pendingTable th.checkbox-pending").addClass("d-none");
      $("#pendingTable th.del-center").addClass("tl-left");
      $(".final-delete-btn-pending").toggleClass("d-none");
      $(".cancel-pending").toggleClass("d-none");

      // Disable the cancel button and enable the pending delete button
      $(this).prop("disabled", true);
      $(".pending-delete-btn")
        .prop("disabled", false)
        .removeClass("light-gray");

      // Uncheck all checkboxes
      $("#pendingTable .pendingCheckbox").prop("checked", false);
      // Uncheck the "Select All" checkbox
      $("#selectAllPending").prop("checked", false);

      selectedPendingIds = []; // Clear selected IDs

      loadPage(
        "verifiedTable",
        "verified-pagination",
        "submission_handlers/fetch-verified.php",
        currentPage
      );

      checkPendingCheckboxes();
    });

    // -- Verified Table Delete Toggle
    $(".verified-delete-btn").click(function () {
      // Toggle visibility and classes for verified table headers
      deleteVerifiedState = true;
      checkVerifiedCheckboxes();
      $(".checkbox-delete-verified").removeClass("d-none");
      $(".checkbox-all-verified").removeClass("d-none");
      $("#verifiedTable th.checkbox-verified")
        .removeClass("d-none")
        .addClass("tl-left");
      $("#verifiedTable th.del-center").removeClass("tl-left");
      $(".final-delete-btn-verified").toggleClass("d-none");
      $(".cancel-verified").toggleClass("d-none");

      // Disable the verified delete button and enable the cancel button
      $(this).prop("disabled", true).addClass("light-gray");
      $(".cancel-verified").prop("disabled", false);
    });

    $(".cancel-verified").click(function () {
      // Toggle visibility and classes for verified table headers back
      deleteVerifiedState = false;
      $(".checkbox-delete-verified").addClass("d-none");
      $(".checkbox-all-verified").addClass("d-none");
      $("#verifiedTable th.checkbox-verified").addClass("d-none");
      $("#verifiedTable th.del-center").addClass("tl-left");
      $(".final-delete-btn-verified").toggleClass("d-none");
      $(".cancel-verified").toggleClass("d-none");

      // Disable the cancel button and enable the verified delete button
      $(this).prop("disabled", true);
      $(".verified-delete-btn")
        .prop("disabled", false)
        .removeClass("light-gray");

      // Uncheck all checkboxes
      $("#verifiedTable .verifiedCheckbox").prop("checked", false);
      // Uncheck the "Select All" checkbox
      $("#selectAllVerified").prop("checked", false);

      selectedVerifiedIds = []; // Clear selected IDs

      loadPage(
        "pendingTable",
        "pending-pagination",
        "submission_handlers/fetch-pending.php",
        currentPage
      );
      checkVerifiedCheckboxes();
    });

    // Initially, disable cancel buttons
    $(".cancel-pending").prop("disabled", true);
    $(".cancel-verified").prop("disabled", true);
  });
  // -- DELETE TOGGLE



  // ++ SELECTING CHECKBOXES
  $("#selectAllPending").click(function () {
    $(".pendingCheckbox").prop("checked", this.checked);
    checkPendingCheckboxes();
  });

  $("#selectAllVerified").click(function () {
    $(".verifiedCheckbox").prop("checked", this.checked);
    checkVerifiedCheckboxes();
  });

  $("#pendingTable").on("change", ".pendingCheckbox", function () {
    checkPendingCheckboxes();
  });

  $("#verifiedTable").on("change", ".verifiedCheckbox", function () {
    checkVerifiedCheckboxes();
  });
  // -- END OF SELECTING CHECKBOXES



  // ++ CHECKBOX EMPTY OR NOT CHECKING
  function checkPendingCheckboxes() {
    const isAnyChecked = $(".pendingCheckbox:checked").length > 0;
    const areAnyAvailable = $(".pendingCheckbox").length > 0;
    console.log("Pending checkboxes checked:", isAnyChecked);
    console.log("Pending checkboxes available:", areAnyAvailable);
    $("#deleteSelectedPending").prop(
      "disabled",
      !isAnyChecked || !areAnyAvailable
    );

    // Update the selectedPendingIds array
    selectedPendingIds = $(".pendingCheckbox:checked")
      .map(function () {
        return $(this).data("id");
      })
      .get();
  }

  function checkVerifiedCheckboxes() {
    const isAnyChecked = $(".verifiedCheckbox:checked").length > 0;
    const areAnyAvailable = $(".verifiedCheckbox").length > 0;
    console.log("Verified checkboxes checked:", isAnyChecked);
    console.log("Verified checkboxes available:", areAnyAvailable);
    $("#deleteSelectedVerified").prop(
      "disabled",
      !isAnyChecked || !areAnyAvailable
    );

    // Update the selectedVerifiedIds array
    selectedVerifiedIds = $(".verifiedCheckbox:checked")
      .map(function () {
        return $(this).data("id");
      })
      .get();
  }
  // -- CHECKBOX EMPTY OR NOT CHECKING



  // ++ DELETION/MOVING TO TRRASHBIN
  $("#deleteSelectedPending, #deleteSelectedVerified").click(function () {
    const selectedIds = [];
    const tableId =
      $(this).attr("id") === "deleteSelectedPending"
        ? "pendingTable"
        : "verifiedTable";

    $(`#${tableId} input[type=checkbox]:checked`).each(function () {
      selectedIds.push($(this).data("id"));
    });

    if (selectedIds.length > 0) {
      $("#rejectModal").modal("show");
      $("#confirm-move")
        .off("click")
        .on("click", function (e) {
          e.preventDefault();
          $.ajax({
            url: "submission_handlers/move-trashbin-accs.php",
            type: "POST",
            data: { ids: selectedIds },
            success: function (response) {
              const result = JSON.parse(response);
              if (result.success) {
                $("#rejectModal").modal("hide");
                $("#trashbinMoveDone").modal("show");

                if (tableId === "pendingTable") {
                  deletePendingState = false;
                  $(".cancel-pending").addClass("d-none");
                  $(".checkbox-delete-pending").addClass("d-none");
                  $(".final-delete-btn-pending").toggleClass("d-none");
                  $("#pendingTable th.del-center").addClass("tl-left");
                  $("#selectAllPending").closest("th").addClass("d-none");
                  $(".pending-delete-btn")
                    .prop("disabled", false)
                    .removeClass("light-gray");
                } else {
                  deleteVerifiedState = false;
                  $(".cancel-verified").addClass("d-none");
                  $(".checkbox-delete-verified").addClass("d-none");
                  $(".final-delete-btn-verified").toggleClass("d-none");
                  $("#verifiedTable th.del-center").addClass("tl-left");
                  $("#selectAllVerified").closest("th").addClass("d-none");
                  $(".verified-delete-btn")
                    .prop("disabled", false)
                    .removeClass("light-gray");
                }

                loadPage(
                  tableId,
                  tableId === "pendingTable"
                    ? "pagination"
                    : "verified-pagination",
                  `submission_handlers/fetch-${
                    tableId === "pendingTable" ? "pending" : "verified"
                  }.php`,
                  currentPage
                );

                if (voters.length === 0) {
                  displayEmptyState(
                    tableId,
                    tableId === "pendingTable" ? "pending" : "verified"
                  );
                }
              } else {
                alert(result.message);
              }
            },
            error: function (error) {
              console.error("Error deleting records:", error);
            },
          });
        });
    } else {
      alert("Please select at least one record to delete.");
    }

    selectedIds = [];
  });
});
// -- DELETION/MOVING TO TRRASHBIN



// ++ MISC FUNCTIONS

function closeModal(modal_name) {
  $("#" + modal_name).modal("hide");
}

function displayEmptyState(tableId, type) {
  const tbody = $(`#${tableId} tbody`);
  tbody.empty();
  let emptyStateHTML = "";
  if (type === "pending") {
    $(".pending-accs-table").addClass("d-none");
    emptyStateHTML = `
         <div class="table-title">
						<div class="row">
								<!-- HEADER -->
								  <div class="col-sm-12">
									  <p class="fs-3 main-color fw-bold ls-10 spacing-6">Pending
										  Registrations</p>
								  </div>
							  </div>
							  <div class="col-md-12 no-registration text-center">
								  <img src="images/resc/folder-empty.png" class="illus">
								  <p class="fw-bold spacing-6 black">No registrations yet</p>
								  <p class="spacing-3 pt-1 black">You’ll find account registrations
									  right
									  here!
								  </p>
							  </div>`;
  } else if (type === "verified") {
    $(".verified-accs-table").addClass("d-none");
    emptyStateHTML = `
          <div class="table-title">
							<div class="row">
								<!-- HEADER -->
								<div class="col-sm-12">
									<p class="fs-3 main-color fw-bold ls-10 spacing-6">Voters'
										Account</p>
								</div>
							</div>
							<div class="col-md-12 no-registration text-center">
								<img src="images/resc/folder-empty.png" class="illus">
								<p class="fw-bold spacing-6 black">No accounts yet</p>
								<p class="spacing-3 pt-1 black fw-medium">Why not verify the
									pending
									registrations above?
								</p>
							</div>`;
  }
  tbody.append(emptyStateHTML);
}

// -- MISC FUNCTIONS
