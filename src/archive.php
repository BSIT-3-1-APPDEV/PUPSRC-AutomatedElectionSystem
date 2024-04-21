<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/x-icon" href="../../src/images/resc/ivote-favicon.png">
	<title>Archive</title>

	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<link rel="stylesheet" href="../src/styles/style.css" />
    <link rel="stylesheet" href="../src/styles/archive.css" />
	<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

</head>

<body>

	<!---------- SIDEBAR + HEADER START ------------>
	<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>
	<!---------- SIDEBAR + HEADER END ------------>


	<div class="main">
         <div class="container">
            <h5>ARCHIVE PER SCHOOL YEAR</h5>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 mx-auto">
                        <div class="card mb-5">
                            <div class="card-body d-flex flex-column justify-content-between align-items-end">
                                <div class="dropdown">
                                    <button class="btn btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Position
                                        <i class="fas fa-chevron-down" id="dropdownIcon"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">President</a></li>
                                    <li><a class="dropdown-item" href="#">Vice President</a></li>
                                    <li><a class="dropdown-item" href="#">Secretary</a></li>
                                    </ul>
                                </div>
                                    <div class="dropdown mt-auto">
                                    <button class="btn btn-primary" type="button" id="yeardropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        School Year
                                        <i class="fas fa-chevron-down" id="dropdownIcon2"></i>
                                    </button>
                                        <ul class="dropdown-menu" aria-labelledby="year-dropdown">
                                        <li><a class="dropdown-item" href="#">2022-2024</a></li>
                                        <li><a class="dropdown-item" href="#">2021-2022</a></li>
                                        <li><a class="dropdown-item" href="#">2020-2021</a></li>
                                    </ul>
                                    </div>
                            </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-11">
                                            <canvas id="myBarChart"></canvas>
                                        </div>
                                    </div>
                                    <h3>Name and Number of Votes for this School Year</h3>
                                       
                        </div>
                            <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button class="btn btn-primary" type="button" id="generate-report-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Generate Report
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="generate-report-dropdown">
                                    <li><a class="dropdown-item" href="#" id="generate-pdf">Export as PDF</a></li>
                                    <li><a class="dropdown-item" href="#" id="generate-docx">Export as Word File</a></li>
                                    <li><a class="dropdown-item" href="#" id="generate-excel">Export as Excel File</a></li>
                                </ul>
                            </div>
                            </div>
                    </div>
                </div>
        </div>
    </div>            
                                    <!-- Chart.js -->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                                    <script>
                                        // Function to adjust bar thickness based on screen width
                                        function adjustBarThickness(chartInstance) {
                                            var screenWidth = window.innerWidth;
                                            var barThickness = screenWidth < 768 ? 20 : 50; // Define the desired thickness for smaller screens

                                            chartInstance.options.scales.xAxes[0].barThickness = barThickness;
                                            chartInstance.update(); // Update the chart with new options
                                        }
                                        // Changing of toggle icon of submenus for position
                                        document.addEventListener('DOMContentLoaded', function () {
                                        var dropdownMenuButton1 = document.getElementById('dropdownMenuButton1');
                                        var dropdownIcon = document.getElementById('dropdownIcon');

                                        dropdownMenuButton1.addEventListener('click', function () {
                                            if (dropdownIcon.classList.contains('fa-chevron-down')) {
                                                dropdownIcon.classList.remove('fa-chevron-down');
                                                dropdownIcon.classList.add('fa-chevron-up');
                                            } else {
                                                dropdownIcon.classList.remove('fa-chevron-up');
                                                dropdownIcon.classList.add('fa-chevron-down');
                                            }
                                            dropdownIcon.style.transition = 'transform 0.5s ease';
                                        });
                                        });
                                        // Changing of toggle icon of submenus for year
                                        document.addEventListener('DOMContentLoaded', function () {
                                        var yeardropdown = document.getElementById('yeardropdown');
                                        var dropdownIcon2 = document.getElementById('dropdownIcon2');

                                        yeardropdown.addEventListener('click', function () {
                                            if (dropdownIcon2.classList.contains('fa-chevron-down')) {
                                                dropdownIcon2.classList.remove('fa-chevron-down');
                                                dropdownIcon2.classList.add('fa-chevron-up');
                                            } else {
                                                dropdownIcon2.classList.remove('fa-chevron-up');
                                                dropdownIcon2.classList.add('fa-chevron-down');
                                            }
                                            dropdownIcon2.style.transition = 'transform 0.5s ease';
                                        });
                                        });
                                    // Define a plugin to draw a horizontal line below the candidate names and vertical lines for each candidate
                                    Chart.plugins.register({
                                        afterDraw: function(chart) {
                                        var ctx = chart.chart.ctx;
                                        var xAxis = chart.scales['x-axis-0'];
                                        var yAxis = chart.scales['y-axis-0'];
                                        var bottomY = chart.chartArea.bottom;

                                        ctx.save();

                                        // Draw horizontal line below candidate names
                                        ctx.beginPath();
                                        ctx.moveTo(xAxis.left, bottomY);
                                        ctx.lineTo(xAxis.right, bottomY);
                                        ctx.lineWidth = 2; // Adjust line width as needed
                                        ctx.strokeStyle = 'rgba(0, 0, 0, 0.1)'; // Adjust line color as needed
                                        ctx.stroke();

                                        chart.data.labels.forEach(function(label, index) {
                                        var image = new Image();
                                        image.src = '../../src/images/resc/mingkyu.jpg'; // Replace with the correct image source
                                        image.onload = function() {
                                            var bar = chart.getDatasetMeta(0).data[index];
                                            var img = new Image();
                                            img.src = image.src;
                                            img.className = 'candidate-image'; // Add a class to the img element
                                            img.onload = function() {
                                                var screenWidth = window.innerWidth;
                                                var imageSize = screenWidth < 768? 20 : 50; // Define the desired image size for smaller screens
                                                ctx.save(); // Save the current context state
                                                ctx.beginPath();
                                                ctx.arc(bar._model.x, bar._model.y - 29, imageSize / 2, 0, 2 * Math.PI); // Create a circular clipping path with a slightly higher position
                                                ctx.closePath();
                                                ctx.clip(); // Clip the region
                                                ctx.drawImage(img, bar._model.x - imageSize / 2, bar._model.y - imageSize - (imageSize < 50? 20 : 0), imageSize, imageSize); // Adjust image position and size as needed
                                                ctx.strokeStyle = '#F45B9B'; // Pink border color
                                                ctx.lineWidth = imageSize / 10; // Border width
                                                ctx.stroke(); // Draw the border
                                                ctx.restore(); // Restore the context state
                                            };
                                        };
                                    });

                                        ctx.restore();
                                        }
                                    });

                                    // Initialize Chart.js
                                    var ctx = document.getElementById('myBarChart').getContext('2d');
                                    var myBarChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                        labels: ['Kim, Mingyu', 'Edan, Ivan Angelo', 'Kim, Jisoo','Park Jihoon','Taylor Batumbakal','Kim Minjeong','Choi Soobin'], // Modify these labels
                                        datasets: [{
                                            // label: 'Votes',
                                            data: [360, 80, 10, 100, 30, 270, 73], // Modify these data points
                                            backgroundColor: ['#F45B9B', '#F45B9B', '#F45B9B', '#F45B9B', '#F45B9B', '#F45B9B', '#F45B9B'], // Customize colors
                                        }]
                                        },
                                        options: {
                                        responsive: true,
                                        maintainAspectRatio: false,

                                        legend: {
                                            display: false
                                                },
                                        

                                        scales: {
                                            yAxes: [{
                                            ticks: {
                                                beginAtZero: true,
                                                callback: function(value) {
                                                if (value % 1 === 0) {
                                                    return value;
                                                }
                                                }
                                            },
                                            scaleLabel: {
                                                display: true,
                                            },
                                            gridLines: {
                                                display: true,
                                                drawOnChartArea: false
                                            }
                                            }],
                                            xAxes: [{
                                            gridLines: {
                                                display: false
                                            },
                                            barThickness: 50,
                                            categoryPercentage: 0.8,
                                            barPercentage: 0.9
                                            }]
                                        }
                                        }
                                    });
                                    
                                     // Call the function initially
                                        adjustBarThickness(myBarChart);

                                    // Call the function whenever the window is resized
                                    window.addEventListener('resize', function() {
                                        adjustBarThickness(myBarChart);
                                    });
                                    
                                    </script>      

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>

</body>
</html>