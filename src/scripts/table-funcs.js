const limit = 5; // Number of accounts per page

// -- FUNCTION: Loading of the table (shows the data)
function loadPage(tableId, paginationId, ajaxUrl, page, searchTerm = "") {
  console.log(
    `Loading page ${page} for ${tableId} with search term: "${searchTerm}"`
  );
  $.ajax({
    url: ajaxUrl,
    type: "GET",
    data: { page: page, search: searchTerm },
    success: function (response) {
      console.log(`Response received for page ${page}:`, response);
      const data = JSON.parse(response);
      const voters = data.voters;
      const totalRows = data.totalRows;
      const totalPages = Math.ceil(totalRows / limit);

      console.log(`Total rows: ${totalRows}, Total pages: ${totalPages}`);

      const tbody = $(`#${tableId} tbody`);
      tbody.empty();

      if (voters.length === 0) {
        // If the search returns no records
        tbody.append(`
                      <td colspan="3">
                          <div class="pt-4 col-md-12 no-registration text-center">
                              <img src="images/resc/not-found.png" class="not-found-illus">
                              <p class="fw-bold spacing-6 black">No records found</p>
                              <p class="spacing-3 pt-1 black">Maybe try a different keyword?</p>
                          </div>
                      </td>
              `);
      } else {
        voters.forEach((voter) => {
          const date = new Date(voter.acc_created);

          const formattedDate = date.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          });

          let row = "";

          // PENDING TABLE
          if (tableId === "pendingTable") {
            console.log("Adding row to pendingTable"); // Debug
            row = `
                   <tr>
                        <td class="col-md-6 text-center text-truncate">
                          <a href="validate-voter.php?voter_id=${voter.voter_id}">${voter.email}</a>
                        </td>

                        <td class="col-md-6 text-center">
                        ${formattedDate}
                        </td>
                    </tr>`;
          // VERIFIED TABLE
          } else if (tableId === "verifiedTable") {
            console.log("Adding row to verifiedTable"); // Debug
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
                      <td class="col-md-3 text-center text-truncate">
                          <a href="voter-details.php?voter_id=${voter.voter_id}">${voter.email}</a>
                      </td>

                      <td class="col-md-3 text-center">
                          <span class="status-background active-status">${
                            voter.account_status.charAt(0).toUpperCase() +
                            voter.account_status.slice(1)
                          }</span>
                      </td>

                      <td class="col-md-3 text-center">
                          <span>${formattedUpdatedDate}</span>
                      </td>
                  </tr>`;
          }
          console.log(`Appending row: ${row}`); // Debug
          tbody.append(row);
        });
      }

      // Initial generatation of pagination (when page is open/reloads)
      generatePagination(
        paginationId,
        totalPages,
        page,
        tableId,
        ajaxUrl,
        searchTerm
      );
    },

    // Error handling/message if AJAX fails
    error: function (error) {
      console.error(
        `Error loading page ${page} for ${tableId} with search term: "${searchTerm}"`,
        error
      );
    },
  });
}


// -- FUNCTION: To generate the pagination
function generatePagination( paginationId, totalPages, currentPage, tableId, ajaxUrl, searchTerm = "") {
  console.log(
    `Generating pagination for ${tableId} (Page ${currentPage} of ${totalPages})` // Debug
  );

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

  // * Load initial data

  // Pending Table
  loadPage(
    "pendingTable",
    "pagination",
    "submission_handlers/fetch-pending.php",
    currentPage
  );

  // Verified Table
  loadPage(
    "verifiedTable",
    "verified-pagination",
    "submission_handlers/fetch-verified.php",
    currentPage
  );


  // * Search event listeners

  // Pending Table
  $("#searchPending").on("input", function () {
    const searchPendingTerm = $(this).val();
    if (searchPendingTerm != "") {
      // If search term is empty, load original data
      loadPage(
        "pendingTable",
        "pagination",
        "submission_handlers/search-pending.php",
        currentPage,
        searchPendingTerm
      );
    } else {  // Balik sa original ng table
      loadPage(
        "pendingTable",
        "pagination",
        "submission_handlers/fetch-pending.php",
        currentPage
      );
    }
  });

  // Verified Table
  $("#searchVerified").on("input", function () {
    const searchTerm = $(this).val();
    if (searchTerm != "") {
      loadPage(
        "verifiedTable",
        "verified-pagination",
        "submission_handlers/search-verified.php",
        currentPage,
        searchTerm
      );
    } else { // Balik sa original state ng table
      loadPage(
        "verifiedTable",
        "verified-pagination",
        "submission_handlers/fetch-verified.php",
        currentPage
      );
    }
  });
});
