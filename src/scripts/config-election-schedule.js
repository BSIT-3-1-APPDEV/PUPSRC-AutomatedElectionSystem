import { initializeConfigurationJS as ConfigJS } from './configuration.js';

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
            // orderable: false,

        },
        {
            targets: 1,
            className: `text-left col-3`,
            // orderable: false,
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
        setTimeout(function () {
            addCheckboxLabel();
        }, 0);


    }
});

function addCheckboxLabel() {
    const CHECKBOXES = document.querySelectorAll('input.dt-select-checkbox');

    CHECKBOXES.forEach((checkbox) => {
        let existingLabel = checkbox.nextElementSibling;

        if (!existingLabel || existingLabel.tagName !== 'LABEL') {
            // Create a new label element
            let newLabel = document.createElement('label');

            checkbox.insertAdjacentElement('afterend', newLabel);
        }
    });
}

table.on('draw', function () {
    if (table.data().any()) {
        // $('div.toolbar').show();
        addCheckboxLabel();
        $('table#example').show();
        $(this).parent().show();
    } else {
        // $('div.toolbar').hide();
        $('table#example').hide();
        $(this).parent().hide();
    }
});

$('#example').on('click', 'tbody tr', function () {
    if (table.row(this, { selected: true }).any()) {
        table.row(this).deselect();
    }
    else {
        table.row(this).select();
    }
});