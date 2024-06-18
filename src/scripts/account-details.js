$(document).ready(function() {
    // Initial call to set the background color on page load
    updateBackgroundColor();
  
    // Event listener for when the select value changes
    $('#dropdown').on('change', updateBackgroundColor);
  
    // Function to update the background color of the select element
    function updateBackgroundColor() {
      var selectedValue = $('#dropdown').val();
      var color = selectedValue === 'admin' ? 'green' : (selectedValue === 'head_admin' ? 'blue' : '');
      
      $('#dropdown').css({
        'background-color': color,
        'color': '#fff' // Set text color to white
      });
    }
});

function changeButtonText(option) {
  const dropdownButton = document.getElementById('dropdownMenuButton1');
  dropdownButton.innerText = option.innerText;
  dropdownButton.click(); // Close the dropdown menu after selecting an option
};


function changeButtonText(elem, role, color) {
  var button = document.getElementById('dropdownMenuButton1');
  button.style.backgroundColor = color;
  button.style.color = 'white';
  button.textContent = elem.textContent;
};

