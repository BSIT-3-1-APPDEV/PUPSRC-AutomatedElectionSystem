
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


let MY_CHART;

function fetchCandidates(selectedPosition) {
  const XHR = new XMLHttpRequest();
  XHR.open('POST', 'includes/fetch-candidates.php', true);
  XHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  XHR.onreadystatechange = function() {
      if (XHR.readyState === XMLHttpRequest.DONE) {
          if (XHR.status === 200) {
              const CANDIDATES_DATA = JSON.parse(XHR.responseText);
              console.log('Candidates Data:', CANDIDATES_DATA);
              updateChart(CANDIDATES_DATA);
          } else {
              console.error('Error fetching candidates:', XHR.status);
          }
      }
  };

  XHR.send(`position=${selectedPosition}`);
}

function updateChart(CANDIDATES_DATA) {
  const IMG_URLS = CANDIDATES_DATA.map(candidate => `images/candidate-profile/${candidate.photoUrl}`);
  const DATA_POINTS = CANDIDATES_DATA.map(candidate => candidate.votesCount);
  const LABELS = CANDIDATES_DATA.map(candidate => `${candidate.firstName} ${candidate.lastName}`);

 

  
  

 
  const CONFIG = {
      type: 'bar',
      data: {
          labels: LABELS,
          datasets: [{
              label: '',
              data: DATA_POINTS,
              backgroundColor: LIGHTER_SHADES, 
          }]
      },
      options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          maxBarThickness: 50,
          
          plugins: {
              tooltip: {
                 
              },
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
                  grace: '150%',
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
                      ctx.fillText(CANDIDATES_DATA[i].firstName + ' ' + CANDIDATES_DATA[i].lastName, TEXT_X, TEXT_Y);

                      const SMALL_TEXT_Y = args.meta.data[i].y + FONT_SIZE - 1;
                      ctx.font = `bold ${FONT_SIZE - 5.3}px Montserrat`;
                      ctx.fillText('Votes: ' + CANDIDATES_DATA[i].votesCount, TEXT_X, SMALL_TEXT_Y);
                  };

                  IMG.onerror = function() {
                      console.error('Failed to load image:', IMAGE_URL);
                  };
              }
          }
      }]
  };

  if (MY_CHART) {
      MY_CHART.destroy();
  }

  MY_CHART = new Chart(document.getElementById('myChart'), CONFIG);
}

// Trigger fetchCandidates function with a selected position
fetchCandidates('President');