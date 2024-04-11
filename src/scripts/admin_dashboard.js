const IMG_URLS = ['placeholder.png']; // Array to store image URLs from fetched data
console.log(IMG_URLS);
const DATA_POINTS = [0];
const BACKGROUND_COLOR = DATA_POINTS.map((data, index) => {
  const RED = 255; // Maximum red component
  const GREEN = 80 + index * 10; // Adjust green component for variation
  const BLUE = 160 + index * 5; // Adjust blue component for variation
  return `rgba(${RED},${GREEN},${BLUE}, 1)`; // Varying shades of pink
});
const DATA = {
  labels: DATA_POINTS.map(point => `${point} Votes`),
  datasets: [{
    label: '',
    data: DATA_POINTS,
    backgroundColor: BACKGROUND_COLOR
  }]
};
const MULTI_BAR_LOGO = {
  id: 'multiBarLogo',
  afterDatasetDraw(chart, args, options) {
    const { ctx } = chart;
    for (let i = 0; i < DATA_POINTS.length; i++) {
      const IMG = new Image();
      IMG.src = `images/candidate-profile/${IMG_URLS[i % IMG_URLS.length]}`; // fetch image

      // Check if args.meta.data[i] is defined before accessing its properties
      if (args.meta.data[i]) {
        // Calculate dynamic font size based on the height of the bar
        const FONT_SIZE = Math.min(args.meta.data[i].height * 0.5, 14); // Adjust the multiplier as needed

        // Calculate the width of the image to maintain aspect ratio
        const ASPECT_RATIO = IMG.height / IMG.width;
        const IMG_WIDTH = args.meta.data[i].height / ASPECT_RATIO;

        ctx.beginPath();
        ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
        ctx.strokeStyle = '#F45B9b'; // Border color
        ctx.lineWidth = 1; // Border width
        ctx.stroke();
        ctx.closePath();

        // Clip the image to the circular shape
        ctx.save(); // Save the current context state
        ctx.beginPath();
        ctx.arc(args.meta.data[i].x + (IMG_WIDTH / 1.7), args.meta.data[i].y, args.meta.data[i].height / 2, 0, Math.PI * 2);
        ctx.closePath();
        ctx.clip();

        // Draw the image clipped to the circular path
        ctx.drawImage(IMG, args.meta.data[i].x + (IMG_WIDTH / 1.7) - (IMG_WIDTH / 2), args.meta.data[i].y - (args.meta.data[i].height / 2), IMG_WIDTH, args.meta.data[i].height);
        
        // Restore the previous context state
        ctx.restore();


        // Calculate the position for the text
        const TEXT_X = args.meta.data[i].x + IMG_WIDTH + (0.03 * IMG_WIDTH); // Adjusted position based on image width
        const TEXT_Y = args.meta.data[i].y; // Y position remains the same as image

        // Add text to the right of the image
        ctx.textAlign = 'start'; // Align text to the start (left)
        ctx.fillStyle = 'black'; // Set fillStyle
        ctx.font = `bold ${FONT_SIZE}px Montserrat`; // Set dynamic font size
        ctx.fillText('Text', TEXT_X, TEXT_Y); // Adjust x and y coordinate

        // Calculate the y-coordinate for the smaller text
        const SMALL_TEXT_Y = args.meta.data[i].y + FONT_SIZE - 1; // Adjust the distance between texts here

        // Add smaller text below the image
        ctx.font = `bold ${FONT_SIZE - 5.3}px Montserrat`; // Set smaller font size for the small text
        ctx.fillText('Small Text', TEXT_X, SMALL_TEXT_Y); // Adjust y coordinate
      }
    }
  }
};




// Chart configuration
const CONFIG = {
  type: 'bar',
  data: DATA,
  options: {
    indexAxis: 'y',
    responsive: true, // Make the chart responsive
    maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
    plugins: {
      tooltip: {
        yAlign: 'bottom'
      },
      legend: {
        display: false // Remove the legend
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        
        grid: {
          display: false // Remove grid lines for y-axis
        }
      },
      x: {
        display: false,
        grace: '60%',
        grid: {
          display: false // Remove grid lines for y-axis
        }
      }
    }
  },
  plugins: [MULTI_BAR_LOGO]
};

// Render the chart
const MY_CHART = new Chart(document.getElementById('myChart'), CONFIG);

// Function to update chart size
function updateChartSize() {
  MY_CHART.resize();
}

// Event listener for window resize
window.addEventListener('resize', updateChartSize);


// Populate the dropdown with fetched positions
fetch('includes/fetch-positions.php')
    .then(response => response.json())
    .then(data => {
        const POSITION_DROPDOWN = document.getElementById('positions');
        data.forEach(position => {
            const OPTION = document.createElement('option');
            OPTION.value = position;
            OPTION.textContent = position;
            POSITION_DROPDOWN.appendChild(OPTION);
        });

        // Trigger the change event manually to fetch data for the first position
        POSITION_DROPDOWN.dispatchEvent(new Event('change'));
    })
    .catch(error => {
        console.error('Error fetching positions:', error);
    });

    
    document.getElementById("positions").addEventListener("change", function() {
      const SELECTED_POSITION = this.value;
  
      // Fetch data for the selected position from the PHP script
      fetch('includes/fetch-data.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ position: SELECTED_POSITION })
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          // Process the fetched data
          console.log('Fetched data:', data);
          updateChartData(data);
  
          // Update the chart configuration with the fetched data
          const NEW_DATA = {
              labels: labels,
              datasets: [{
                  label: '',
                  data: DATA_POINTS,
                  backgroundColor: BACKGROUND_COLOR
              }]
          };
  
          // Update the chart with the new data
          MY_CHART.data = NEW_DATA;
          
          MY_CHART.update();
          
      })
      .catch(error => {
          console.error('Error fetching data:', error);
      });
  });
  

  function updateChartData(data) {
    const DATA_POINTS = [];
    const labels = [];
    const BACKGROUND_COLOR = [];
  
    data.forEach((item, index) => {
      // Process data as before (extract datapoints, labels, backgroundColors)
      DATA_POINTS.push(item.vote_count);
      labels.push(`${item.vote_count} Votes`);
      const RED = 255;
      const GREEN = 80 + index * 10;
      const BLUE = 160 + index * 5;
      BACKGROUND_COLOR.push(`rgba(${RED},${GREEN},${BLUE}, 1)`);
      const IMAGE_URL = `images/candidate-profile/${item.photo_url}`;
      IMG_URLS.push(IMAGE_URL); // Push image URLs here
      console.log(DATA_POINTS)
    });
  
    // Update chart configuration with fetched data
    const NEW_DATA = {
      labels: labels,
      datasets: [{
        label: '',
        data: DATA_POINTS,
        backgroundColor: BACKGROUND_COLOR
      }]
    };
    MY_CHART.data = NEW_DATA;
    MY_CHART.update();
  }