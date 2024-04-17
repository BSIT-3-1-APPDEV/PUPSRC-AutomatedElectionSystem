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
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" aria-expanded="false">
                                        Position
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">President</a></li>
                                    <li><a class="dropdown-item" href="#">Vice President</a></li>
                                    <li><a class="dropdown-item" href="#">Secretary</a></li>
                                    </ul>
                                </div>
                                    <div class="dropdown mt-auto">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" aria-expanded="false">
                                        School Year
                                    </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
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
                                        <button id="generate-report-btn" class="btn-generate mt-3">Generate Report</button>
                                    </div>
                    </div>
                </div>
        </div>            
                                    <!-- Chart.js -->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                                    <script>
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
                                    </script>      
        </div>


	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>

</body>
</html>