
class datatableObjects {

}
// Create the toolbar div element
var toolbarDiv = document.createElement("div");
toolbarDiv.className = "toolbar";

// Create the first button element
var button1 = document.createElement("button");
button1.type = "button";
button1.className = "btn btn-primary";
button1.innerHTML = `<i data-feather="user-plus" width="calc(1rem + 0.25vw)" height="calc(1rem + 0.25vw)"></i>`;

// Create the second button element
var button2 = document.createElement("button");
button2.type = "button";
button2.className = "btn btn-primary";
button2.innerHTML = `<i data-feather="edit-2" width="calc(1rem + 0.25vw)" height="calc(1rem + 0.25vw)"></i>`;

// Create the third button element
var button3 = document.createElement("button");
button3.type = "button";
button3.className = "btn btn-primary";
button3.innerHTML = `<i data-feather="trash-2" width="calc(1rem + 0.25vw)" height="calc(1rem + 0.25vw)"></i>`;

// Append the buttons to the toolbar div
toolbarDiv.appendChild(button1);
toolbarDiv.appendChild(button2);
toolbarDiv.appendChild(button3);



let table = new DataTable('#example', {
    rowReorder: true,
    paging: false,
    select: {
        style: 'multi',
        selector: 'row',
        className: 'row-selected'
    },
    orderable: true,
    columnDefs: [
        {
            targets: 0,
            className: 'custom-checkbox col-3',
            render: DataTable.render.select(),

        },
        {
            targets: 1, className: `text-left col-3`,
            className: ``,
            // render: function (data) {
            //     return `Section`;
            // }
        },
        {
            targets: 2, className: `text-center col-6`,
            // render: function (data) {
            //     return `Date`;
            // }
        },
    ],
    layout: {
        bottomStart: null,
        bottomEnd: null,
        topStart: function () {

            toolbar = toolbarDiv;

            return toolbar;
        },
        topEnd: {

            search: {
                placeholder: 'Search'
            }
        },

    },
    language: {
        "search": `<i data-feather="search" width="calc(0.75rem + 0.25vw)" height="calc(0.75rem + 0.25vw)"></i>`,
    },
    initComplete: function (settings, json) {
        // let searchContainer = document.querySelector('col-md-auto ms-auto');
        // searchContainer.classList.add('col-12', 'col-md-10');
        // let searchInput = document.querySelector('div.dt-search input');
        // searchInput.classList.add('w-100');

    }
});

// Select all input elements with the class "dt-select-checkbox"
const checkboxes = document.querySelectorAll('input.dt-select-checkbox');

// Iterate over each checkbox
checkboxes.forEach((checkbox) => {
    // Create a new label element
    const label = document.createElement('label');

    // Insert the label after the checkbox
    checkbox.insertAdjacentElement('afterend', label);
});
