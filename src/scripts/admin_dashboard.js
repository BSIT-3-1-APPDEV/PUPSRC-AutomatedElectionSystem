let originalCandidatesData; // Variable to store the original candidates data
let candidatesData;
let previousCandidatesData = null;
let shouldSwapNames = false;

function fetchCandidates() {
console.log('Fetching candidates...'); // Add this line
const SELECTED_POSITION = document.getElementById('positions').value; // Get the selected position

const XHR = new XMLHttpRequest();
XHR.open('POST', 'includes/fetch-candidates.php', true);
XHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
XHR.onreadystatechange = function() {
    if (XHR.readyState === XMLHttpRequest.DONE) {
        if (XHR.status === 200) {
            const fetchedCandidatesData = JSON.parse(XHR.responseText);
            console.log('Candidates Data:', fetchedCandidatesData);
            if (shouldSwapNames == true) {
                fetchedCandidatesData.forEach(candidate => {
                    candidate.firstName = candidate.positionTitle;
                    
                });
                fetchedCandidatesData.forEach((candidate, index) => {
                    candidate.lastName = '#' + (index + 1); // Adding '#' before the number
                });
                
                fetchedCandidatesData.forEach((candidate, index) => {
                    candidate.photoUrl = 'placeholder.png'; // Adding 1 to start numbering from 1
                });
            }
            if (!isEqual(previousCandidatesData, fetchedCandidatesData)) {
                // Update the chart only if data has changed
              

                updateChart(fetchedCandidatesData);
                candidatesData = fetchedCandidatesData;
                previousCandidatesData = fetchedCandidatesData;
            }
        } else {
            console.error('Error fetching candidates:', XHR.status);
        }
    }
};

XHR.send(`position=${SELECTED_POSITION}`);
}

const toggleSwitch = document.getElementById('switch');
toggleSwitch.addEventListener('change', function() {
    console.log("Toggle Pressed");
    console.log(shouldSwapNames);
    toggleNameSwap();
});
function toggleNameSwap() {
    shouldSwapNames = !shouldSwapNames;
}
// Utility function to compare two objects for equality
function isEqual(obj1, obj2) {
return JSON.stringify(obj1) === JSON.stringify(obj2);
}

// Call fetchCandidates initially

fetchCandidates('<?php echo $firstPosition; ?>');

// Schedule periodic fetch
setInterval(fetchCandidates, 3000);

// Function to toggle the switch button


document.getElementById('fullscreen-button').addEventListener('click', () => {
    // Get a reference to the full-screen element
    const ELEMENT = document.querySelector('.full-screen');
    
    // Check if full-screen mode is supported
    if (screenfull.isEnabled) {
        // Toggle full-screen mode for the element
        screenfull.request(ELEMENT);
    } else {
        // If full-screen mode is not supported, handle it accordingly
        alert("Full screen mode is not supported.");
    }
});

function resizeChart() {
    var canvas = document.getElementById('myChart');
    canvas.style.width = '58rem'; // Set the new width
    canvas.style.height = '20rem'; // Set the new height
    myChart.resize(); // Resize the chart
}

// Function to restore the chart to its original size
function restoreChart() {
    var canvas = document.getElementById('myChart');
    canvas.style.width = '58rem'; // Set the new width
    canvas.style.height = '18rem'; // Set the new height
    myChart.resize(); // Resize the chart
}
// Add event listener for changes in fullscreen mode
screenfull.on('change', () => {
    // Get a reference to the full-screen element
    const ELEMENT = document.querySelector('.full-screen');
    const ELEMENT2= document.querySelector('.full-screen-content');
    const ELEMENT3= document.querySelector('.chart-container');
    const ELEMENT4= document.querySelector('.switch');
    const HIDE_FULL_SCREEN = document.getElementById('fullscreen-button');

    // Check if the element is in fullscreen mode
    if (screenfull.isFullscreen) {
        // Add your desired class to the element when in fullscreen mode
        ELEMENT.classList.add('centered');
        ELEMENT2.classList.add('centered');
        ELEMENT3.classList.add('centered');
        ELEMENT4.classList.remove('d-none');
        HIDE_FULL_SCREEN.classList.add('d-none');
        resizeChart();
    } else {
        // Remove the class when exiting fullscreen mode
        ELEMENT.classList.remove('centered');
        ELEMENT2.classList.remove('centered');
        ELEMENT3.classList.remove('centered');
        ELEMENT4.classList.add('d-none');
        HIDE_FULL_SCREEN.classList.remove('d-none');
        restoreChart();
        
    }
});

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

// Get the computed background color of an element with the .main-bg-color class
const COMPUTED_COLOR = getComputedStyle(document.querySelector('.main-bg-color')).backgroundColor;

// Generate lighter shades based on the computed color
const LIGHTER_SHADES = generateLighterShades(COMPUTED_COLOR, 5); // Change 5 to the number of shades you want


let myChart;

function updateChart(candidatesData) {
    candidatesData.sort((a, b) => b.votesCount - a.votesCount);
    const IMG_URLS = candidatesData.map(candidate => `images/candidate-profile/${candidate.photoUrl}`);
    const DATA_POINTS = candidatesData.map(candidate => candidate.votesCount);
    const LABELS = candidatesData.map(candidate => [candidate.firstName, candidate.lastName]);
 
    // Check if the chart exists
    if (!myChart) {
        // If the chart does not exist, create a new one
        createChart(LABELS, DATA_POINTS, IMG_URLS);
        return;
    }

    // Update chart data and labels
    myChart.data.labels = LABELS;
    myChart.data.datasets[0].data = DATA_POINTS;

    // Update image URLs in the plugin
    myChart.config.plugins[0].afterDatasetDraw = function(chart, args, options) {
        const { ctx } = chart;
        for (let i = 0; i < DATA_POINTS.length; i++) {
            const IMAGE_URL = `${IMG_URLS[i % IMG_URLS.length]}`;
            const IMG = new Image();
            IMG.src = IMAGE_URL;

            IMG.onload = function() {
                const FONT_SIZE = Math.min(args.meta.data[i].height * 0.5, 14);
                const ASPECT_RATIO = IMG.height / IMG.width;
                const IMG_WIDTH = args.meta.data[i].height / ASPECT_RATIO;

                ctx.beginPath();
                ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
                ctx.strokeStyle = LIGHTER_SHADES;
                ctx.lineWidth = 1;
                ctx.stroke();
                ctx.closePath();

                ctx.save();
                ctx.beginPath();
                ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();

                ctx.drawImage(IMG, args.meta.data[i].x + (IMG_WIDTH / 1.7) - (IMG_WIDTH / 2), args.meta.data[i].y - (args.meta.data[i].height / 2), IMG_WIDTH, args.meta.data[i].height);

                ctx.restore();

                const TEXT_X = args.meta.data[i].x + IMG_WIDTH + (0.05 * IMG_WIDTH);
                const TEXT_Y = args.meta.data[i].y;

                ctx.textAlign = 'start';
                ctx.fillStyle = 'black';
                ctx.font = `bold ${FONT_SIZE}px Montserrat`;
                if (shouldSwapNames) {
                ctx.fillText(candidatesData[i].firstName.toUpperCase() + ' ' + candidatesData[i].lastName.toUpperCase(), TEXT_X, TEXT_Y);
            } else {
                ctx.fillText(candidatesData[i].firstName.toUpperCase() + ', ' + candidatesData[i].lastName.toUpperCase(), TEXT_X, TEXT_Y);
            }
                ctx.fillStyle = LIGHTER_SHADES;
                const SMALL_TEXT_Y = args.meta.data[i].y + FONT_SIZE - 1;
                ctx.font = `bold ${FONT_SIZE - 5.3}px Montserrat`;
                ctx.fillText(candidatesData[i].votesCount + ' votes ' , TEXT_X, SMALL_TEXT_Y);
            };

            IMG.onerror = function() {
                console.error('Failed to load image:', IMAGE_URL);
            };
        }
    };

    // Update the chart
    myChart.update();
}
function createChart(labels, dataPoints, imgUrls) {
    const CONFIG = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '',
                data: dataPoints,
                backgroundColor: LIGHTER_SHADES,
                barThickness: 'flex',
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            maxBarThickness: 34,
            plugins: {
                tooltip: {},
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    
                    display: false,
                    grace: '200%',
                    
                    grid: {
                        display: false
                    }
                }
            }
        },
        plugins: [{
            id: 'multiBarLogo',
            afterDatasetDraw: function(chart, args, options) {
                const { ctx } = chart;
                for (let i = 0; i < dataPoints.length; i++) {
                    const IMAGE_URL = `${imgUrls[i % imgUrls.length]}`;
                    const IMG = new Image();
                    IMG.src = IMAGE_URL;

                    IMG.onload = function() {
                        const FONT_SIZE = Math.min(args.meta.data[i].height * 0.5, 14);
                        const ASPECT_RATIO = IMG.height / IMG.width;
                        const IMG_WIDTH = args.meta.data[i].height / ASPECT_RATIO;

                        ctx.beginPath();
                        ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
                        ctx.strokeStyle = LIGHTER_SHADES;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                        ctx.closePath();

                        ctx.save();
                        ctx.beginPath();
                        ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
                        ctx.closePath();
                        ctx.clip();

                        ctx.drawImage(IMG, args.meta.data[i].x + (IMG_WIDTH / 1.7) - (IMG_WIDTH / 2), args.meta.data[i].y - (args.meta.data[i].height / 2), IMG_WIDTH, args.meta.data[i].height);

                        ctx.restore();

                        const TEXT_X = args.meta.data[i].x + IMG_WIDTH + (0.05 * IMG_WIDTH);
                        const TEXT_Y = args.meta.data[i].y;

                        ctx.textAlign = 'start';
                        ctx.fillStyle = 'black';
                        ctx.font = `bold ${FONT_SIZE}px Montserrat`;
                         if (shouldSwapNames) {
                    ctx.fillText(labels[i][0].toUpperCase() + ' ' + labels[i][1].toUpperCase(), TEXT_X, TEXT_Y);
                } else {
                    ctx.fillText(labels[i][0].toUpperCase() + ', ' + labels[i][1].toUpperCase(), TEXT_X, TEXT_Y);
                }


                        ctx.fillStyle = LIGHTER_SHADES;
                        const SMALL_TEXT_Y = args.meta.data[i].y + FONT_SIZE - 1;
                        ctx.font = `bold ${FONT_SIZE - 5.3}px Montserrat`;
                        ctx.fillText( dataPoints[i] + ' votes' , TEXT_X, SMALL_TEXT_Y);
                    };

                    IMG.onerror = function() {
                        console.error('Failed to load image:', IMAGE_URL);
                    };
                }
            }
        }]
    };

    // Create the chart
    myChart = new Chart(document.getElementById('myChart'), CONFIG);
}


    if (screenfull.isFullscreen) {
        resizeChart();
    } else {
        // Apply normal size adjustments
        restoreChart();
    }


// Add an event listener to the select element
document.getElementById('positions').addEventListener('change', fetchCandidates);




// Function to fetch candidates when in fullscreen mode


// Window resize event listener to resize the chart
window.addEventListener('resize', () => {
    if (myChart) {
        myChart.resize();
    }
});
