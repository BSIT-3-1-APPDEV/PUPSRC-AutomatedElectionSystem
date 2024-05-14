import { initializeConfigurationJS as ConfigJS, shortFnv1a as hashName } from './configuration.js';

/**
 * The ConfigPage object holds variables classes and function of the current page.
 * If ConfigPage is already defined, it retains its current value; otherwise, it is initialized as an empty object.
 * It will be reset to empty when another configuration script is added and executed.
 * @type {object}
 */
var ConfigPage = ConfigPage || {};

/**
 * Removes all event listeners stored in ConfigPage.eventListeners Map, if any.
 * It iterates over the Map and removes each event listener using removeEventListener(),
 * and then clears the Map.
 * @function
 * @name ConfigPage.removeEventListeners
 * @memberof ConfigPage
 */
ConfigPage.removeEventListeners = function () {
    if (ConfigPage.eventListeners && ConfigPage.eventListeners instanceof Map && ConfigPage.eventListeners.size > 0) {
        ConfigPage.eventListeners.forEach((listener, element) => {
            element.removeEventListener(listener.event, listener.handler);
        });

        ConfigPage.eventListeners.clear();
    }
};

ConfigPage.removeEventListeners();

var ConfigPage = {};

const NOW = new Date();

/**
 * A Map that stores event listeners associated with elements.
 * This used to avoid duplicate event listeners.
 * @type {Map<Element, { event: string, handler: function }>}
 */
ConfigPage.eventListeners = new Map();

/**
 * Adds an event listener to the specified element and stores it in the ConfigPage.eventListeners Map.
 * @function
 * @name ConfigPage.addEventListenerAndStore
 * @memberof ConfigPage
 * @param {Element} element - The DOM element to which the event listener is added.
 * @param {string} event - The name of the event to listen for.
 * @param {function} handler - The function to be executed when the event is triggered.
 */
ConfigPage.addEventListenerAndStore = function (element, event, handler) {
    element.addEventListener(event, handler);
    ConfigPage.eventListeners.set(element, { event, handler });
}

/**
 * Removes the event listener associated with the specified element and deletes its entry from the ConfigPage.eventListeners Map.
 * @function
 * @name ConfigPage.delEventListener
 * @memberof ConfigPage
 * @param {Element} element - The DOM element from which the event listener is removed.
 */
ConfigPage.delEventListener = function (element) {
    // Check if the element has an associated event listener stored in the map
    if (ConfigPage.eventListeners.has(element)) {
        // Retrieve the event listener information from the map
        const listener = ConfigPage.eventListeners.get(element);

        // Remove
        element.removeEventListener(listener.event, listener.handler);

        // Delete 
        ConfigPage.eventListeners.delete(element);
    }
}


ConfigPage = {
    configJs: function () {
        ConfigJS();
    },
    fetchData: function () {
        var url = 'src/includes/classes/config-election-sched-controller.php';

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                console.log('GET request successful:', data);
                const TABLE_DATA = ConfigPage.processData(data);
                // ConfigPage.displayCurrentYear(ConfigPage.electionYears.currentYear);
                // ConfigPage.startDatePicker();
                // ConfigPage.initPage();
                ConfigPage.insertData(TABLE_DATA, ConfigPage.table);
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },

    electionYears: { currentYear: '', previousYears: [] },
    processData: function (DATA) {
        let tableData = [];
        DATA.forEach(element => {
            const tableItem = {
                0: element.data_id,
                1: {
                    courseName: 'ACAP',
                    year: element.year_level,
                    section: element.section
                },
                2: element.schedule
            };

            tableData.push(tableItem);
        });

        return tableData;
    },
    insertData: function (TABLE_DATA, TABLE) {
        TABLE.clear();
        TABLE.rows.add(TABLE_DATA).draw(true);
    }
}

ConfigPage.fetchData();

ConfigPage.dtbleObjects = class dtblObjects {


    static addCheckboxLabel() {
        const CHECKBOXES = document.querySelectorAll('input.dt-select-checkbox');

        CHECKBOXES.forEach((checkbox) => {
            let existingLabel = checkbox.nextElementSibling;

            if (!existingLabel || existingLabel.tagName !== 'LABEL') {
                let newLabel = document.createElement('label');

                checkbox.insertAdjacentElement('afterend', newLabel);
            }
        });
    }
}

ConfigPage.objs = class Objects {


    static getModalId() {
        return `_063c020e${hashName(NOW)}`;
    }
}

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
            render: function (data) {
                const courseName = (data.courseName && data.courseName.trim()) ? data.courseName : 'Section';
                return `${courseName} ${data.year}-${data.section}`;
            }
        },
        {
            targets: 2, className: `text-center col-6`,
            render: DataTable.render.datetime('MMMM d, yyyy')
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

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj);

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="edit-2"></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj);

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="trash-2" ></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj);

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
            ConfigPage.toggleStickyToolbar();
        }, 0);


    }
});


ConfigPage.AddTableListener = function () {
    ConfigPage.table.on('draw', function () {
        if (ConfigPage.table.data().any()) {
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


ConfigPage.toggleStickyToolbar = function () {
    ConfigPage.toolbarContainer = document.querySelector('.row.mt-2:has(.dt-buttons)');

    var notStuck = new IntersectionObserver(
        entries => {
            // setTimeout(() => {
            entries.forEach(entry => {
                entry.target.classList.toggle('stuck', !entry.isIntersecting);

                if (!entry.isIntersecting) {
                    // setTimeout(() => {
                    notStuck.unobserve(entry.target);
                    stuck.observe(entry.target);
                    // }, 0);
                }
            });
            // }, 500);

        },
        {
            threshold: 1,
        }
    );

    var stuck = new IntersectionObserver(
        entries => {
            // setTimeout(() => {
            entries.forEach(entry => {
                entry.target.classList.toggle('stuck', entry.isIntersecting);

                if (entry.isIntersecting) {
                    // setTimeout(() => {
                    stuck.unobserve(entry.target);
                    notStuck.observe(entry.target);
                    // }, 0);
                }
            });
            // }, 500);

        },
        {
            threshold: 1,
            rootMargin: '-63px',
        }
    );


    // Start observing the target element
    notStuck.observe(ConfigPage.toolbarContainer);

    // Revert changes when scrolling to the top
    window.addEventListener('scroll', function () {
        if (window.scrollY === 0) {
            notStuck.observe(ConfigPage.toolbarContainer);
        }
    });
}

ConfigPage.AddSectionOverflowListener = function () {
    const text = document.querySelector('.modal-body .course-section');
    const container = text.parentNode.clientWidth - parseFloat(getComputedStyle(text.parentNode).paddingLeft) - parseFloat(getComputedStyle(text.parentNode).paddingRight);
    const textLength = text.getBoundingClientRect().width;

    text.style.maxWidth = '';
    text.style.overflow = '';
    text.style.whiteSpace = '';
    text.style.textOverflow = '';

    if (textLength > container) {
        // If the text overflows, set the font size using clamp with a calculated value
        text.style.fontSize = `clamp(0.8rem, calc(1.325rem + 0.9vw - ${(textLength - container) * 0.1}px), 4rem)`;
    } else {
        text.style.fontSize = '';
    }

    setTimeout(() => {
        const container = text.parentNode.clientWidth - parseFloat(getComputedStyle(text.parentNode).paddingLeft) - parseFloat(getComputedStyle(text.parentNode).paddingRight);
        const textLength = text.getBoundingClientRect().width;
        console.log(textLength + ` text length after ` + container + " container length");
        if (textLength > container) {
            text.style.maxWidth = `${container}px`;
            text.style.overflow = 'hidden';
            text.style.whiteSpace = 'nowrap';
            text.style.textOverflow = 'ellipsis';
        } else {
            text.style.maxWidth = '';
            text.style.overflow = '';
            text.style.whiteSpace = '';
            text.style.textOverflow = '';
        }
    }, 0);
}


ConfigPage.ActionModal = class ActionModal {
    static create(modalId, modalClass, modalDialogClass, modalParts) {
        // Get the modal element
        let body = document.getElementsByTagName('body')[0];

        // Create modal dialog div
        let modal = document.createElement('div');
        modal.className = modalClass;

        // Create modal dialog div
        let modalDialog = document.createElement('div');
        modalDialog.className = modalDialogClass;

        // Create modal content div
        let modalContent = document.createElement('div');
        modalContent.className = 'modal-content';

        // Create and append modal parts (header, body, footer)
        ['header', 'body', 'footer'].forEach(function (part) {
            if (modalParts[part]) {
                let modalPart = document.createElement('div');
                modalPart.className = 'modal-' + part + ' ' + modalParts[part].className;
                modalPart.innerHTML = modalParts[part].innerHTML;
                modalContent.appendChild(modalPart);
            }
        });

        // Append all elements
        modalDialog.appendChild(modalContent);
        modal.appendChild(modalDialog);

        modal.id = modalId;
        body.appendChild(modal);

        let modalElement = document.getElementById(modalId);
        let modalInst = new bootstrap.Modal(modalElement);
        ConfigPage.actionModalE = document.getElementById(ConfigPage.objs.getModalId());

        return modalInst;

    }

    static createContent() {
        let title = document.createElement('h2');
        title.classList = 'course-section';
        title.textContent = ' BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1';

        let dateInput = document.createElement('input')
        dateInput.setAttribute('type', 'text');
        dateInput.classList = 'course-section col-10 col-md-5 col-xl-4';
        dateInput.setAttribute('id', 'positionInput');
        dateInput.setAttribute('placeholder', 'dd/mm/yy');
        dateInput.setAttribute('pattern', '');
        dateInput.setAttribute('required', '');

        let saveButtonLabel = document.createElement('label')
        saveButtonLabel.setAttribute("for", "save-button");
        saveButtonLabel.setAttribute("id", "save-button-label");
        saveButtonLabel.setAttribute("data-bs-toggle", "tooltip");
        saveButtonLabel.setAttribute("data-bs-title", "No changes made.");
        saveButtonLabel.setAttribute("data-bs-placement", "right");

        let saveButton = document.createElement('button')
        saveButton.setAttribute("type", "button");
        saveButton.setAttribute("id", "save-button");
        saveButton.setAttribute("class", "btn btn-success mx-auto");
        saveButton.setAttribute("disabled", true);
        saveButton.textContent = "Save Changes";

        let tempContainer = document.createElement('div');
        tempContainer.appendChild(title);
        tempContainer.appendChild(dateInput);
        tempContainer.appendChild(saveButtonLabel);
        tempContainer.appendChild(saveButton);

        return tempContainer.innerHTML;
    }

    static updateContent(DATA, isAdd = false) {

        let actionModal = document.getElementById(ACTION_MODAL_ID);
        let modalInput = actionModal.querySelector('.modal-body input[type="text"]');
        let modalTitle = actionModal.querySelector('.modal-body .course-section');

        if (modalTitle) {
            modalTitle.value = DATA[0].value ?? '';
        }

        if (modalInput) {
            modalInput.setAttribute('data-data-id', DATA[0].data_id);
            modalInput.setAttribute('data-target-input', DATA[0].input_id);
            modalInput.value = DATA[0].value ?? '';
        }


    }

    static show(ACTION_MODAL) {
        if (ACTION_MODAL) {
            ACTION_MODAL.show();

            ConfigPage.delEventListener(ConfigPage.actionModalE);
            ConfigPage.addEventListenerAndStore(ConfigPage.actionModalE, 'shown.bs.modal', ConfigPage.AddSectionOverflowListener);

        }
    }
}

ConfigPage.actionModalObj = ConfigPage.ActionModal.create(ConfigPage.objs.getModalId(),
    'modal fade',
    'modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down',
    {
        header: { className: '', innerHTML: '<button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x-circle" ></i></button>' },
        body: { className: '', innerHTML: ConfigPage.ActionModal.createContent() },
    });


ConfigPage.AddTableListener();



const chipsInitialNew = document.querySelector('#chips');

const newChipsNew = new mdb.ChipsInput(chipsInitialNew, {
    initialValues: [
        {
            tag: 'MDBReact',
        },
        {
            tag: 'MDBAngular',
        },
        {
            tag: 'MDBVue',
        },
        {
            tag: 'MDB5',
        },
        {
            tag: 'MDB',
        },
    ],
});

// const basicAutocomplete = document.querySelector('#chips');
// const data = ['One', 'Two', 'Three', 'Four', 'Five'];
// const dataFilter = (value) => {
//     return data.filter((item) => {
//         return item.toLowerCase().startsWith(value.toLowerCase());
//     });
// };

// new mdb.Autocomplete(basicAutocomplete, {
//     filter: dataFilter
// });