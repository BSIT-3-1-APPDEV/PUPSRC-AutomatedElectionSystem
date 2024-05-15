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

ConfigPage = {};

const NOW = new Date();



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
    },
    fetchYearSectionList: function () {
        var url = 'src/includes/classes/config-election-sched-controller.php?getVoterCount=true';

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (responseData) {
                let data = [];
                responseData.forEach(item => {
                    // console.log(item);
                    const id = parseInt(item.data_id);
                    const text = `ACAP ${item.year_level}-${item.section}`;
                    data.push({ id, text });
                });

                ConfigPage.yearSectionList = data;
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    }
}

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

ConfigPage.fetchData();
ConfigPage.fetchYearSectionList();

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

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj, ConfigPage.objs.getModalId(), 'add');

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="edit-2"></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj, ConfigPage.objs.getModalId(), 'update');

                        console.log(count + ' row(s) selected');
                    }
                },
                {
                    text: '<i data-feather="trash-2" ></i>',
                    className: 'btn-primary',
                    action: function () {
                        let count = ConfigPage.table.rows({ selected: true }).count();

                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj, ConfigPage.objs.getModalId(), 'delete');

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

    if (!text) { return; }

    text.style.fontSize = '';
    text.style.maxWidth = '';
    text.style.overflow = '';
    text.style.whiteSpace = '';
    text.style.textOverflow = '';

    const container = text.parentNode.clientWidth - parseFloat(getComputedStyle(text.parentNode).paddingLeft) - parseFloat(getComputedStyle(text.parentNode).paddingRight);
    const textLength = text.getBoundingClientRect().width;

    if (textLength > container) {
        // If the text overflows, set the font size using clamp with a calculated value
        text.style.fontSize = `clamp(0.8rem, calc(1.325rem + 0.9vw - ${(textLength - container) * 0.1}px), 4rem)`;
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
    } else {
        text.style.fontSize = '';
    }

}

ConfigPage.Select2Matcher = function (params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
        return data;
    }

    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
        return null;
    }

    // Extracting individual components from the search term
    const searchTerm = params.term.toLowerCase();
    const searchParts = searchTerm.split(' ');

    // Check if any part of the search term matches any part of the data.text
    const isMatch = searchParts.every(part => data.text.toLowerCase().includes(part));

    if (isMatch) {
        var modifiedData = $.extend({}, data, true);
        modifiedData.text += ' (matched)';

        // You can return modified objects from here
        // This includes matching the `children` how you want in nested data sets
        return modifiedData;
    }

    // Return `null` if the term should not be displayed
    return null;
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

    static createSaveButton() {

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

        return {
            saveButtonLabel: saveButtonLabel,
            saveButton: saveButton
        };
    }

    static clearModalBody(modal) {
        console.log('clearing');
        console.log(modal);
        while (modal.firstChild) {
            modal.removeChild(modal.firstChild);
        }
    }

    static setAddContent(modalId) {

        if (modalId) {
            let modalBody = document.querySelector(`#${modalId} .modal-body`);
            this.clearModalBody(modalBody);

            let selectElem = document.createElement("select");
            selectElem.setAttribute("id", "add-section-schedule");

            let dateInput = document.createElement('input')
            dateInput.setAttribute('type', 'text');
            dateInput.classList = 'course-section col-10 col-md-5 col-xl-4';
            dateInput.setAttribute('id', 'scheduleInput');
            dateInput.setAttribute('placeholder', 'dd/mm/yy');
            dateInput.setAttribute('pattern', '');
            dateInput.setAttribute('required', '');

            modalBody.insertBefore(dateInput, modalBody.firstChild);
            modalBody.insertBefore(selectElem, modalBody.firstChild);

            // if ($('#add-section-schedule').hasClass("select2-hidden-accessible")) {
            //     // Select2 has been initialized
            // } else {


            // if ($('#mySelect2').find("option[value='" + data.id + "']").length) {
            // } else { 
            //     // Create a DOM Option and pre-select by default
            //     var newOption = new Option(data.text, data.id, true, true);

            // } 
            // }

            $("#add-section-schedule").select2({
                data: ConfigPage.yearSectionList,
                multiple: true,
                placeholder: 'Select a Section.',
            });


            const SAVE_BUTTON_LABEL = document.getElementById("save-button-label");
            const SAVE_BUTTON = document.getElementById("save-button");

            if (!SAVE_BUTTON_LABEL && !SAVE_BUTTON) {
                const { saveButtonLabel, saveButton } = this.createSaveButton();
                modalBody.appendChild(saveButtonLabel);
                modalBody.appendChild(saveButton);
            }
        }

    }

    static setUpdateContent(modalId, data) {
        if (modalId) {
            let modalBody = document.querySelector(`#${modalId} .modal-body`);
            this.clearModalBody(modalBody);

            let title = document.createElement('h2');
            title.classList = 'course-section modal-temp';
            title.textContent = ' BSIT 3-1';

            let dateInput = document.createElement('input')
            dateInput.setAttribute('type', 'text');
            dateInput.classList = 'modal-temp col-10 col-md-5 col-xl-4';
            dateInput.setAttribute('id', 'scheduleInput');
            dateInput.setAttribute('placeholder', 'dd/mm/yy');
            dateInput.setAttribute('pattern', '');
            dateInput.setAttribute('required', '');

            modalBody.insertBefore(dateInput, modalBody.firstChild);
            modalBody.insertBefore(title, modalBody.firstChild);

            const SAVE_BUTTON_LABEL = document.getElementById("save-button-label");
            const SAVE_BUTTON = document.getElementById("save-button");

            if (!SAVE_BUTTON_LABEL && !SAVE_BUTTON) {
                const { saveButtonLabel, saveButton } = this.createSaveButton();
                modalBody.appendChild(saveButtonLabel);
                modalBody.appendChild(saveButton);
            }

        }
    }

    static setDeleteContent(modalId) {

        if (modalId) {
            let modalBody = document.querySelector(`#${modalId} .modal-body`);
            this.clearModalBody(modalBody);

            const SAVE_BUTTON_LABEL = document.getElementById("save-button-label");
            const SAVE_BUTTON = document.getElementById("save-button");

            if (!SAVE_BUTTON_LABEL && !SAVE_BUTTON) {
                const { saveButtonLabel, saveButton } = this.createSaveButton();
                modalBody.appendChild(saveButtonLabel);
                modalBody.appendChild(saveButton);
            }

        }
    }


    static updateContent(modalId, mode, data) {


        switch (mode) {
            case 'add':
                this.setAddContent(modalId);
                break;
            case 'update':
                this.setUpdateContent(modalId, data)
                break;
            case 'delete':
                this.setDeleteContent()
                break;
            default:
                throw new Error(`Mode '${mode}' is not supported.`);
        }


    }

    static show(ACTION_MODAL, modalId, mode, data = '') {
        if (ACTION_MODAL) {

            ConfigPage.ActionModal.updateContent(modalId, mode, data);
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
        body: { className: '', innerHTML: '' },
    });


ConfigPage.AddTableListener();



console.log(ConfigPage);