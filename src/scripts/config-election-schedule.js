import { initializeConfigurationJS as ConfigJS } from './configuration.js';


var ConfigPage = {};

ConfigPage.dtbleObjects = class dtblObjects {
    static toolbarDiv;

    static addToolbar() {
        this.toolbarDiv = document.createElement("div");
        this.toolbarDiv.className = "toolbar";

        // Create the add button element
        let button1 = document.createElement("button");
        button1.type = "button";
        button1.className = "btn btn-primary";
        button1.innerHTML = `<i data-feather="user-plus"></i>`;

        // Create the edit button element
        let button2 = document.createElement("button");
        button2.type = "button";
        button2.className = "btn btn-primary";
        button2.innerHTML = `<i data-feather="edit-2"></i>`;

        // Create the delete button element
        let button3 = document.createElement("button");
        button3.type = "button";
        button3.className = "btn btn-primary";
        button3.innerHTML = `<i data-feather="trash-2" ></i>`;

        this.toolbarDiv.appendChild(button1);
        this.toolbarDiv.appendChild(button2);
        this.toolbarDiv.appendChild(button3);
        console.log('before');
    }

    static getToolbar() {
        return this.toolbarDiv;
    }

    static addCheckboxLabel() {
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
}

ConfigPage.dtbleObjects.addToolbar();

ConfigPage.table = new DataTable('#example', {
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
        // topStart: function () {

        //     toolbar = ConfigPage.dtbleObjects.getToolbar();

        //     return toolbar;
        // },
        topEnd: {

            search: {
                placeholder: 'Search'
            }
        },
        topStart: {
            buttons: [
                {
                    text: '<i data-feather="user-plus"></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();
                        console.log(ConfigPage.table.rows({ selected: true }).data());

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="edit-2"></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="trash-2" ></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        console.log(count + ' row(s) selected');
                    }
                },
            ]
        },

    },
    language: {
        "search": `<i data-feather="search" width="calc(0.75rem + 0.25vw)" height="calc(0.75rem + 0.25vw)"></i>`,
    },
    initComplete: function (settings, json) {
        setTimeout(function () {
            ConfigPage.dtbleObjects.addCheckboxLabel();
        }, 0);


    }
});


ConfigPage.AddTableListener = function () {
    ConfigPage.table.on('draw', function () {
        if (table.data().any()) {
            // $('div.toolbar').show();
            ConfigPage.dtbleObjects.addCheckboxLabel();
            $('table#example').show();
            $(this).parent().show();
        } else {
            // $('div.toolbar').hide();
            $('table#example').hide();
            $(this).parent().hide();
        }
    });

    $('#example').on('click', 'tbody tr', function () {
        if (ConfigPage.table.row(this, { selected: true }).any()) {
            ConfigPage.table.row(this).deselect();
        }
        else {
            ConfigPage.table.row(this).select();
        }
    });
}


ConfigPage.AddTableListener();

// Function to handle the intersection


// Create an Intersection Observer
var notstuck = new IntersectionObserver(
    entries => {
        entries.forEach(entry => {
            entry.target.classList.toggle('stuck', !entry.isIntersecting)
            if (!entry.isIntersecting) notstuck.unobserve(entry.target);
            stuck.observe(targetElement);
        })
    },
    {
        threshold: 1,
    }
);

var stuck = new IntersectionObserver(
    entries => {
        entries.forEach(entry => {
            entry.target.classList.toggle('stuck', entry.isIntersecting)
            if (entry.isIntersecting) stuck.unobserve(entry.target);
        })
    },
    {
        threshold: 1,
        rootMargin: '-62px',
    }
);

// Target the element you want to observe
var targetElement = document.querySelector('.row.mt-2:has(.dt-buttons)');

// Start observing the target element
notstuck.observe(targetElement);
