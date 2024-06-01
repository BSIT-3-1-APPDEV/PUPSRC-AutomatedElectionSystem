const limit = 5; // Number of data per page

function loadPage(tableId, paginationId, ajaxUrl, page) {
  $.ajax({
    url: ajaxUrl,
    type: "GET",
    data: { page: page },
    success: function (response) {
      const data = JSON.parse(response);
      const voters = data.voters;
      const totalRows = data.totalRows;
      const totalPages = Math.ceil(totalRows / limit);

      const tbody = $(`#${tableId} tbody`);
      tbody.empty();

      voters.forEach((voter) => {
        const date = new Date(voter.acc_created);
        const updated_date = new Date(voter.status_updated);

        const formattedDate = date.toLocaleDateString("en-US", {
          year: "numeric",
          month: "long",
          day: "numeric",
        });

        const formattedUpdatedDate = updated_date.toLocaleDateString("en-US", {
          year: "numeric",
          month: "long",
          day: "numeric",
        });

        let row = "";
        if (tableId === "pendingTable") {
          console.log("I'm in here");
          row = `
                        <tr>
                            <td class="col-md-6 text-center text-truncate">
                                <a href="validate-voter.php?voter_id=${voter.voter_id}">${voter.email}</a>
                            </td>
                            <td class="col-md-6 text-center">${formattedDate}</td>
                        </tr>`;
        } else if (tableId === "verifiedTable") {
          console.log("I'm in");
          row = `?>
                    <tr>
                    <td class="col-md-3 text-center text-truncate">
                        <a href="voter-details.php?voter_id=${voter.voter_id}">${voter.email}</a>
                    </td>
                    <td class="col-md-3 text-center">
                    <span class="status-background active-status">${voter.account_status.charAt(0).toUpperCase() + voter.account_status.slice(1)}</span>

                    </td>
                    <td class="col-md-3 text-center">
                        <span>${formattedUpdatedDate}</span>
                    </td>
                </tr>`;
        }
        tbody.append(row);
      });
      generatePagination(paginationId, totalPages, page, tableId, ajaxUrl);
    },
  });
}

// Generalized function to generate pagination for a specific table
function generatePagination(
  paginationId,
  totalPages,
  currentPage,
  tableId,
  ajaxUrl
) {
  const pagination = $(`#${paginationId}`);
  pagination.empty();

  // Previous arrow
  const prevArrowClass = currentPage === 1 ? "disabled-arrow" : "";
  const prevPage = currentPage > 1 ? currentPage - 1 : 1;
  const prevItem = `
        <li class="page-item id="left-arrow-page">
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
  const nextPage = currentPage < totalPages ? currentPage + 1 : totalPages;
  const nextItem = `
        <li class="page-item id="right-arrow-page"">
            <a href="#" class="page-link" data-page="${nextPage}">
                <span class="fas fa-chevron-right ${nextArrowClass}"></span>
            </a>
        </li>`;
  pagination.append(nextItem);

  // Attach click events
  $(".page-link")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      const page = $(this).data("page");
      loadPage(tableId, paginationId, ajaxUrl, page);
    });
}

$(document).ready(function () {
  const currentPage = 1;
  // Load initial data for pendingTable
  loadPage(
    "pendingTable",
    "pagination",
    "submission_handlers/fetch-pending.php",
    currentPage
  );

  // Load initial data for verifiedTable
  loadPage(
    "verifiedTable",
    "verified-pagination",
    "submission_handlers/fetch-verified.php",
    currentPage
  );
});
