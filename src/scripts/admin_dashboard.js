let originalCandidatesData; // Variable to store the original candidates data
let candidatesData;
let isDataReset = false; // Flag to track whether data is reset

document.getElementById('reset-button').addEventListener('click', () => {
    resetCandidatesData(candidatesData);
});


  
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
    canvas.style.height = '30rem'; // Set the new height
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

    // Check if the element is in fullscreen mode
    if (screenfull.isFullscreen) {
        // Add your desired class to the element when in fullscreen mode
        ELEMENT.classList.add('centered');
        ELEMENT2.classList.add('centered');
        ELEMENT3.classList.add('centered');
        
        resizeChart();
    } else {
        // Remove the class when exiting fullscreen mode
        ELEMENT.classList.remove('centered');
        ELEMENT2.classList.remove('centered');
        ELEMENT3.classList.remove('centered');
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

function fetchCandidates(selectedPosition) {
  const XHR = new XMLHttpRequest();
  XHR.open('POST', 'includes/fetch-candidates.php', true);
  XHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  XHR.onreadystatechange = function() {
      if (XHR.readyState === XMLHttpRequest.DONE) {
          if (XHR.status === 200) {
               candidatesData = JSON.parse(XHR.responseText);
              console.log('Candidates Data:', candidatesData);
              updateChart(candidatesData);
          } else {
              console.error('Error fetching candidates:', XHR.status);
          }
      }
  };

  XHR.send(`position=${selectedPosition}`);
}
function fetchCandidatesFullScreen() {
    const XHR = new XMLHttpRequest();
    XHR.open('GET', 'includes/fetch-all-candidates.php', true); // Modify URL as needed
    XHR.setRequestHeader('Content-Type', 'application/json');
  
    XHR.onreadystatechange = function() {
        if (XHR.readyState === XMLHttpRequest.DONE) {
            if (XHR.status === 200) {
                candidatesData = JSON.parse(XHR.responseText);
                console.log('Candidates Data (Full Screen):', candidatesData);
                updateChart(candidatesData);
            } else {
                console.error('Error fetching candidates:', XHR.status);
            }
        }
    };
  
    XHR.send();
  }
  
  function resetCandidatesData() {
    const PLACEHOLDER_IMAGE_URL = 'placeholder.png';
    const POSITION_COUNT = {};
    if (!isDataReset) {
        // Backup current data before resetting
        originalCandidatesData = JSON.parse(JSON.stringify(candidatesData));
        
        // Replace candidate names with their positions
        for (let i = 0; i < candidatesData.length; i++) {
            const position = candidatesData[i].positionTitle;
            
            // Increment the count for this position
            POSITION_COUNT[position] = (POSITION_COUNT[position] || 0) + 1;
        
            // Set the first name to the position title
            candidatesData[i].firstName = position;
        
            // Set the last name to the count for this position
            candidatesData[i].lastName = `#${POSITION_COUNT[position]}`;
        
            // Set the photo URL to the placeholder image
            candidatesData[i].photoUrl = PLACEHOLDER_IMAGE_URL;
        }
        
        isDataReset = true; // Update flag
    } else {
        // Restore original data
        candidatesData = JSON.parse(JSON.stringify(originalCandidatesData));
        isDataReset = false; // Update flag
    }

    // Re-render the chart with updated data
    updateChart(candidatesData);
}


function updateChart(candidatesData) {
    const IMG_URLS = candidatesData.map(candidate => `images/candidate-profile/${candidate.photoUrl}`);
    const DATA_POINTS = candidatesData.map(candidate => candidate.votesCount);
    const LABELS = candidatesData.map(candidate => `${candidate.firstName} ${candidate.lastName}`);
  
  
    const CONFIG = {
        type: 'bar',
        data: {
            labels: LABELS,
            datasets: [{
                label: '',
                data: DATA_POINTS,
                backgroundColor: LIGHTER_SHADES,
                barThickness: 'flex',
              
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false, // Ensure the chart is not confined to a fixed aspect ratio
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
                    grace: '200%%',
                    grid: {
                        display: false
                    }
                }
            }
        },
      plugins: [{
          id: 'multiBarLogo',
          afterDatasetDraw(chart, args, options) {
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
                      ctx.strokeStyle = '#F45B9b';
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
                      ctx.fillText(candidatesData[i].firstName + ' ' + candidatesData[i].lastName, TEXT_X, TEXT_Y);

                      const SMALL_TEXT_Y = args.meta.data[i].y + FONT_SIZE - 1;
                      ctx.font = `bold ${FONT_SIZE - 5.3}px Montserrat`;
                      ctx.fillText('Votes: ' + candidatesData[i].votesCount, TEXT_X, SMALL_TEXT_Y);
                  };

                  IMG.onerror = function() {
                      console.error('Failed to load image:', IMAGE_URL);
                  };
              }
          }
      }]
  };

  if (myChart) {
      myChart.destroy();
  }

  myChart = new Chart(document.getElementById('myChart'), CONFIG);
   if (screenfull.isFullscreen) {

        resizeChart();
    } else {
        // Apply normal size adjustments
        restoreChart();
    }
}
// Add an event listener to the select element
document.getElementById('positions').addEventListener('change', function() {
    const SELECTED_POSITION = this.value; // Get the selected position
    if (!screenfull.isFullscreen) {
        fetchCandidates(SELECTED_POSITION); // Fetch candidates based on selected position
    }
});

// Add event listener for changes in fullscreen mode
screenfull.on('change', () => {
    // Get a reference to the full-screen element
    
    const CANDIDATES_DROPDOWN_BUTTON = document.getElementById('positions');
    const HIDE_CANDIDATES_BUTTON = document.getElementById('reset-button');
    const HIDE_FULL_SCREEN = document.getElementById('fullscreen-button');
    // Check if the element is in fullscreen mode
    if (screenfull.isFullscreen) {
        fetchCandidatesFullScreen(); // Fetch all candidates when entering full-screen mode
        CANDIDATES_DROPDOWN_BUTTON.classList.add('d-none');
        HIDE_CANDIDATES_BUTTON.classList.remove('d-none');
        HIDE_FULL_SCREEN.classList.add('d-none');
        resizeChart();
    } else {
        // If exiting full-screen mode, fetch candidates based on the selected position
        const SELECTED_POSITION = document.getElementById('positions').value;
        HIDE_CANDIDATES_BUTTON.classList.add('d-none');
        fetchCandidates(SELECTED_POSITION);
        CANDIDATES_DROPDOWN_BUTTON.classList.remove('d-none');
        HIDE_FULL_SCREEN.classList.remove('d-none');
        restoreChart();
    }
});

window.addEventListener('resize', () => {
    if (myChart) {
        myChart.resize();
    }
});

