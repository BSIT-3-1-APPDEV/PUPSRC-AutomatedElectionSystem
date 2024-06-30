document.addEventListener("DOMContentLoaded", function () {
  var initialYear = "2023-2024";
  var initialPage = 1;

  document.getElementById("selectedYear").innerHTML =
    "Current Academic Year: <strong>" + initialYear + "</strong>";
  document.getElementById("dropdownButtonText").innerHTML =
    "A.Y. " +
    initialYear +
    ' <i data-feather="chevron-down" class="white im-cust feather-1xs"></i>';

  window.selectedYear = initialYear;
  window.currentPage = initialPage;

  selectYearAndPage(initialYear, initialPage);
});

function showLoading() {
  document.getElementById("election-results").innerHTML =
    '<div class="loading">Loading...</div>';
}

function selectYear(year) {
  selectYearAndPage(year, 1);
}

function selectYearAndPage(year, page) {
  showLoading();
  const xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      document.getElementById("election-results").innerHTML = this.responseText;
      document.getElementById("selectedYear").innerHTML =
        "Current Academic Year: <strong>" + year + "</strong>";
      window.selectedYear = year;
      window.currentPage = page;
      document.getElementById("dropdownButtonText").innerHTML =
        "A.Y. " +
        year +
        ' <i data-feather="chevron-down" class="white im-cust feather-1xs"></i>';
      // Trigger position select change to update the chart with the new year
      document
        .getElementById("positionSelect")
        .dispatchEvent(new Event("change"));
      feather.replace();
    }
  };
  xhttp.open(
    "GET",
    "includes/fetch-election-data.php?election_year=" + year + "&page=" + page,
    true
  );
  xhttp.send();
}

document.getElementById("btn-previous").addEventListener("click", function () {
  if (window.currentPage > 1) {
    selectYearAndPage(window.selectedYear, window.currentPage - 1);
  }
});

document.getElementById("btn-next").addEventListener("click", function () {
  selectYearAndPage(window.selectedYear, window.currentPage + 1);
});

// Initialize global selected year
window.selectedYear = "<?php echo $selected_year; ?>";
var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: [], // Initially empty
    datasets: [
      {
        label: "# of Votes",
        data: [], // Initially empty
        backgroundColor: [], // Initially empty
        borderColor: "rgba(0, 0, 0, 0)", // Transparent border color
        borderWidth: 1,
        barThickness: 40,
      },
    ],
  },
  options: {
    plugins: {
      legend: {
        display: false,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          display: true, // Hide grid lines on y-axis (vertical grid lines)
        },
      },
      x: {
        grid: {
          display: false, // Show grid lines on x-axis (horizontal grid lines)
        },
      },
    },
  },
});

// Function to generate lighter shades of a base color
function generateLighterShades(baseColor, steps) {
  const RGB_COLOR = baseColor.match(/\d+/g); // Extract RGB values from the base color

  const R_STEP = (255 - RGB_COLOR[0]) / steps;
  const G_STEP = (255 - RGB_COLOR[1]) / steps;
  const B_STEP = (255 - RGB_COLOR[2]) / steps;

  const LIGHTER_SHADES = [];
  for (let i = 0; i < steps; i++) {
    const R = Math.round(parseInt(RGB_COLOR[0]) + i * R_STEP);
    const G = Math.round(parseInt(RGB_COLOR[1]) + i * G_STEP);
    const B = Math.round(parseInt(RGB_COLOR[2]) + i * B_STEP);
    LIGHTER_SHADES.push(`rgba(${R},${G},${B},0.7)`); // Adjust alpha value as needed
  }

  return LIGHTER_SHADES;
}

// Update chart function using computed color for shades
function updateChart(labels, votes) {
  // Get the computed background color of .main-bg-color
  const COMPUTED_COLOR = getComputedStyle(
    document.querySelector(".main-bg-color")
  ).backgroundColor;

  // Generate lighter shades based on the computed color
  const LIGHTER_SHADES = generateLighterShades(COMPUTED_COLOR, 3); // Change 5 to the number of shades you want

  // Set different colors for the candidates and abstained bar
  var backgroundColors = labels.map((label, index) =>
    label === "Abstained"
      ? LIGHTER_SHADES[0]
      : LIGHTER_SHADES[index % LIGHTER_SHADES.length]
  );

  myChart.data.labels = labels;
  myChart.data.datasets[0].data = votes;
  myChart.data.datasets[0].backgroundColor = backgroundColors;
  myChart.update();
}

document
  .getElementById("positionSelect")
  .addEventListener("change", function () {
    var selectedPosition = this.value;
    var electionYear = window.selectedYear;

    fetch(
      `includes/result-candidates.php?position_id=${selectedPosition}&election_year=${electionYear}`
    )
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        var labels = data.candidates.map((candidate) => candidate.name);
        var votes = data.candidates.map((candidate) => candidate.vote_count);
        updateChart(labels, votes);
      })
      .catch((error) => console.error("Error fetching data:", error));
  });
//dropdown
window.onclick = function (event) {
  if (!event.target.matches(".dropdown-button2")) {
    var dropdowns = document.getElementsByClassName("dropdown-content2");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.style.display === "block") {
        openDropdown.style.display = "none";
      }
    }
  }
};

//download pdf
function downloadPDF() {
  const electionYear = window.selectedYear; // Get the global selected year

  fetch(`includes/result-pdf.php?election_year=${electionYear}`)
    .then((response) => response.text())
    .then((phpString) => {
      const options = {
        margin: 10,
        filename: "generated_pdf.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };

      html2pdf().from(phpString).set(options).save();
    })
    .catch((error) => {
      console.error("Error fetching PHP content:", error);
    });
}

//download pdf
function downloadAllPDF() {
  const electionYear = window.selectedYear; // Get the global selected year

  fetch(`includes/results-all-pdf.php?election_year=${electionYear}`)
    .then((response) => response.text())
    .then((phpString) => {
      const options = {
        margin: 10,
        filename: "generated_pdf.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };

      html2pdf().from(phpString).set(options).save();
    })
    .catch((error) => {
      console.error("Error fetching PHP content:", error);
    });
}

//excel download
function downloadExcel() {
  const electionYear = window.selectedYear; // Get the global selected year
  fetch(`includes/result-xls.php?election_year=${electionYear}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      // Create a new workbook and a worksheet
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.json_to_sheet(data);

      // Append the worksheet to the workbook
      XLSX.utils.book_append_sheet(wb, ws, "Election Winners");

      // Generate Excel file and trigger download
      XLSX.writeFile(wb, `election_winners_${electionYear}.xlsx`);
    })
    .catch((error) => console.error("Error fetching data:", error));
}

//pagination
document.addEventListener("DOMContentLoaded", function () {
  const sortButtons = document.querySelectorAll(".dropdown-item");
  sortButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const sort = this.getAttribute("data-sort");
      const order = this.getAttribute("data-order");
      fetchFeedbackData(sort, order);
    });
  });

  function fetchFeedbackData(sort, order, page = 1) {
    const data = new FormData();
    data.append("sort", sort);
    data.append("order", order);
    data.append("page", page);

    fetch("includes/fetch-feedback.php", {
      method: "POST",
      body: data,
    })
      .then((response) => response.json())
      .then((data) => {
        updateTable(data.feedback_data);
        updatePagination(data.total_pages, data.current_page, sort, order);
      })
      .catch((error) => console.error("Error:", error));
  }

  function updateTable(feedback_data) {
    const tableBody = document.querySelector("table tbody");
    tableBody.innerHTML = "";

    feedback_data.forEach((row) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
                <td class="col-md-7 text-center">${row.feedback}</td>
                <td class="col-md-2 text-center">${new Date(
                  row.timestamp
                ).toLocaleDateString()}</td>
            `;
      tableBody.appendChild(tr);
    });
  }

  function updatePagination(total_pages, current_page, sort, order) {
    const pagination = document.querySelector(".pagination");
    pagination.innerHTML = "";

    if (current_page > 1) {
      const prev = document.createElement("li");
      prev.className = "page-item";
      prev.innerHTML = `<a href="#" class="page-link" data-page="${
        current_page - 1
      }">Previous</a>`;
      pagination.appendChild(prev);
    }

    for (let i = 1; i <= total_pages; i++) {
      const page = document.createElement("li");
      page.className = `page-item ${i === current_page ? "active" : ""}`;
      page.innerHTML = `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
      pagination.appendChild(page);
    }

    if (current_page < total_pages) {
      const next = document.createElement("li");
      next.className = "page-item";
      next.innerHTML = `<a href="#" class="page-link" data-page="${
        current_page + 1
      }">Next</a>`;
      pagination.appendChild(next);
    }

    const pageLinks = document.querySelectorAll(".page-link");
    pageLinks.forEach((link) => {
      link.addEventListener("click", function (event) {
        event.preventDefault();
        const page = this.getAttribute("data-page");
        fetchFeedbackData(sort, order, page);
      });
    });
  }

  // Initial load
  fetchFeedbackData("timestamp", "desc");
});

$(document).ready(function () {
  $(".view-more").click(function () {
    var feedbackData = $(this).data("feedback");
    var timestamp = new Date(feedbackData.timestamp); // Convert timestamp to Date object

    // Format time and date separately
    var formattedTime = timestamp.toLocaleString("en-US", {
      hour: "numeric",
      minute: "numeric",
      hour12: true,
    });
    var formattedDate = timestamp.toLocaleString("en-US", {
      month: "long",
      day: "numeric",
      year: "numeric",
    });

    // Set text in modal elements
    $("#modal-feedback").text(feedbackData.feedback);
    $("#modal-date").text(formattedTime + " | " + formattedDate);
    $("#modal-date").addClass("text-end"); // Center align the text
    $("#successEmailModal").modal("show");
  });
});
