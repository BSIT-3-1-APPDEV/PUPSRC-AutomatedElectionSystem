document.addEventListener("DOMContentLoaded", () => {
  fetch("includes/misc/general-faqs.json")
    .then((response) => response.json())
    .then((data) => {
      const faqs = data.faqs;
      const accordion = document.getElementById("accordion");

      faqs.forEach((faq, index) => {
        const faqRadius = document.createElement("div");
        faqRadius.className = "radius-5";

        const faqItem = document.createElement("div");
        faqItem.className = "accordion-item border border-0 my-4 shadow-sm";

        const faqHeader = document.createElement("h2");
        faqHeader.className = "accordion-header";

        const faqButton = document.createElement("button");
        faqButton.className = "accordion-button collapsed";
        faqButton.type = "button";
        faqButton.setAttribute("data-bs-toggle", "collapse");
        faqButton.setAttribute("data-bs-target", `#faq${index}`);
        faqButton.setAttribute("aria-expanded", "false");
        faqButton.setAttribute("aria-controls", `faq${index}`);

        const faqQuestion = document.createElement("div");
        faqQuestion.className = "px-3 fw-semibold faq-question";
        faqQuestion.innerText = faq.question;

        const plusIcon = document.createElement("span");
        plusIcon.className = "accordion-button-icon plus-icon me-2";
        plusIcon.innerHTML =
          '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/></svg>';

        const minusIcon = document.createElement("span");
        minusIcon.className = "accordion-button-icon minus-icon d-none me-2";
        minusIcon.innerHTML =
          '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1z"/></svg>';

        faqButton.appendChild(faqQuestion);
        faqButton.appendChild(plusIcon);
        faqButton.appendChild(minusIcon);

        faqHeader.appendChild(faqButton);
        faqItem.appendChild(faqHeader);

        const faqCollapse = document.createElement("div");
        faqCollapse.id = `faq${index}`;
        faqCollapse.className = "accordion-collapse collapse";
        faqCollapse.setAttribute("data-bs-parent", "#accordion");

        const faqBody = document.createElement("div");
        faqBody.className = "accordion-body faq-answer ms-3";
        faqBody.innerText = faq.answer;

        faqCollapse.appendChild(faqBody);
        faqItem.appendChild(faqCollapse);

        faqRadius.appendChild(faqItem);
        accordion.appendChild(faqRadius);
      });

      addFaqsElements();
    })
    .catch((error) => console.error("Error fetching FAQ data:", error));
});

function addFaqsElements() {
  $(".accordion-button").on("click", function () {
    const isCollapsed = $(this).hasClass("collapsed");
    $(this).find(".plus-icon").toggleClass("d-none", !isCollapsed);
    $(this).find(".minus-icon").toggleClass("d-none", isCollapsed);

    $(".accordion-button")
      .not(this)
      .each(function () {
        const otherIsCollapsed = $(this).hasClass("collapsed");
        $(this).find(".plus-icon").toggleClass("d-none", !otherIsCollapsed);
        $(this).find(".minus-icon").toggleClass("d-none", otherIsCollapsed);
      });
  });

  // Pagination
  const itemsPerPage = 4;
  let currentPage = 1;

  function paginateFAQs(items, pageNumber) {
    const startIndex = (pageNumber - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    items.each(function (index) {
      if (index >= startIndex && index < endIndex) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    currentPage = pageNumber;
    updatePaginationControls(items.length);
  }

  function updatePaginationControls(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationElement = $("#pagination");
    paginationElement.empty();

    // Previous arrow
    const previousLi = $("<li>").addClass("page-item");
    const previousLink = $("<a>")
      .addClass("page-link")
      .attr("href", "#")
      .html('<span aria-hidden="true" class="fas fa-chevron-left"></span>');
    if (currentPage === 1) {
      previousLi.addClass("disabled");
    } else {
      previousLink.on("click", function (event) {
        event.preventDefault();
        paginateFAQs($(".accordion-item"), currentPage - 1);
      });
    }
    previousLi.append(previousLink);
    paginationElement.append(previousLi);

    const visiblePages = 4;
    let startPage = currentPage - Math.floor(visiblePages / 2);
    startPage = Math.max(startPage, 1);
    let endPage = startPage + visiblePages - 1;
    if (endPage > totalPages) {
      endPage = totalPages;
      startPage = Math.max(endPage - visiblePages + 1, 1);
    }

    if (startPage > 1) {
      const firstLi = $("<li>").addClass("page-item");
      const firstLink = $("<a>")
        .addClass("page-link")
        .attr("href", "#")
        .text("1");
      firstLink.on("click", function (event) {
        event.preventDefault();
        paginateFAQs($(".accordion-item"), 1);
      });
      firstLi.append(firstLink);
      paginationElement.append(firstLi);

      if (startPage > 2) {
        const ellipsisLi = $("<li>")
          .addClass("page-item disabled")
          .append(
            "<span class='page-link' style='border: none; background: transparent; color: #000;'>...</span>"
          );
        paginationElement.append(ellipsisLi);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const liClass = i === currentPage ? "page-item active" : "page-item";
      const linkClass = "page-link";
      const liElement = $("<li>").addClass(liClass);
      const linkElement = $("<a>")
        .addClass(linkClass)
        .attr("href", "#")
        .text(i);

      linkElement.on("click", function (event) {
        event.preventDefault();
        paginateFAQs($(".accordion-item"), i);
      });

      liElement.append(linkElement);
      paginationElement.append(liElement);
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const ellipsisLi = $("<li>")
          .addClass("page-item disabled")
          .append(
            "<span class='page-link' style='border: none; background: transparent; color: #000;'>...</span>"
          );
        paginationElement.append(ellipsisLi);
      }

      const lastLi = $("<li>").addClass("page-item");
      const lastLink = $("<a>")
        .addClass("page-link")
        .attr("href", "#")
        .text(totalPages);
      lastLink.on("click", function (event) {
        event.preventDefault();
        paginateFAQs($(".accordion-item"), totalPages);
      });
      lastLi.append(lastLink);
      paginationElement.append(lastLi);
    }

    // Next arrow
    const nextLi = $("<li>").addClass("page-item");
    const nextLink = $("<a>")
      .addClass("page-link")
      .attr("href", "#")
      .html('<span aria-hidden="true" class="fas fa-chevron-right"></span>');
    if (currentPage === totalPages) {
      nextLi.addClass("disabled");
    } else {
      nextLink.on("click", function (event) {
        event.preventDefault();
        paginateFAQs($(".accordion-item"), currentPage + 1);
      });
    }
    nextLi.append(nextLink);
    paginationElement.append(nextLi);
  }

  const faqItems = $(".accordion-item");
  paginateFAQs(faqItems, currentPage);
}
