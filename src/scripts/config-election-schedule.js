import { initializeConfigurationJS as ConfigJS, shortFnv1a as hashName } from './configuration.js';
import InputValidator from './input-validator.js';

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

ConfigPage = null;
ConfigPage = {};

const NOW = JS_DATE_TZ();

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
                ConfigPage.insertData(TABLE_DATA, ConfigPage.table);
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },

    electionYears: { currentYear: '', previousYears: [] },
    processData: function (DATA) {
        let tableData = [];

        if (!Array.isArray(DATA)) {
            DATA = [DATA];
        }
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
                    const id = parseInt(item.data_id);
                    const text = `ACAP ${item.year_level}-${item.section}`;
                    data.push({ id, text });
                });

                ConfigPage.yearSectionList = data;
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },
    startDatePicker: function (datepickerId) {
        ConfigPage.datePickerInst = $(`#${datepickerId}`).datepicker({
            classes: 'col-10 col-md-5 col-xl-4',
            position: 'bottom center',
            language: "en",
            minDate: NOW,
            maxDate: ConfigPage.fiveYearsAhead,
            clearButton: true,
            autoClose: false,
            dateFormat: "dd/mm/yyyy",
            onSelect: function (formattedDate, date, inst) {

                inst.el.dispatchEvent(new Event('input', { bubbles: true }));
                ConfigPage.selectedDate = date.toISOString();
            },
            onShow: function (inst, animationComplete) {
                let thisPicker = document.getElementsByClassName('datepicker');
                let input = document.getElementById(ConfigPage.datepickerId);
                let inputWidth = input.offsetWidth;
                let inputVal = input.value;
                if (!animationComplete) {
                    var iFits = false;
                    // Loop through a few possible position and see which one fits
                    $.each(['bottom center', 'right center', 'right bottom', 'right top', 'top center'], function (i, pos) {
                        if (!iFits) {
                            inst.update('position', pos);
                            var fits = ConfigPage.isElementInViewport(inst.$datepicker[0]);
                            if (fits.all) {
                                iFits = true;
                            }
                        }
                    });

                    input.value = inputVal;
                }

                for (let i = 0; i < thisPicker.length; i++) {
                    let element = thisPicker[i];
                    if (inputWidth > 250) {
                        element.style.width = inputWidth + 'px';
                    } else {
                        element.style.width = 250 + 'px';
                    }

                }
            },
        });
    }
}

ConfigPage.fiveYearsAhead = new Date();
ConfigPage.fiveYearsAhead.setFullYear(ConfigPage.fiveYearsAhead.getFullYear() + 5);
ConfigPage.fiveYearsAhead.setMonth(ConfigPage.fiveYearsAhead.getMonth() + 1, 0);
ConfigPage.fiveYearsAhead.setHours(23, 59, 59, 999);

(function () {
    ConfigPage.datepickerId = 'scheduleInput';
    ConfigPage.addSectionInputId = 'add-section-schedule';


    let currentYear = NOW.getFullYear();
    let yearLength = currentYear.toString().length;
    let datePattern = new RegExp(`^[0-3]?[0-9]/[01]?[0-9]/[0-9]{${yearLength}}$`);
    let patternString = datePattern.toString().slice(1, -1);

    ConfigPage.dateValidation = {
        clear_invalid: false,
        attributes: {
            type: 'text',
            pattern: patternString,
            required: true,
            max_length: 6 + yearLength,
            min_length: 10,
        }
    };

    ConfigPage.dateValidate = new InputValidator(ConfigPage.dateValidation);
})();

ConfigPage.isElementInViewport = function (el) {
    var rect = el.getBoundingClientRect();
    var fitsLeft = (rect.left >= 0 && rect.left <= $(window).width());
    var fitsTop = (rect.top >= 0 && rect.top <= $(window).height());
    var fitsRight = (rect.right >= 0 && rect.right <= $(window).width());
    var fitsBottom = (rect.bottom >= 0 && rect.bottom <= $(window).height());
    return {
        bottom: fitsBottom,
        top: fitsTop,
        left: fitsLeft,
        right: fitsRight,
        all: (fitsLeft && fitsTop && fitsRight && fitsBottom)
    };
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

// Disable Initial Error of datatble when no data fetch yet
$.fn.dataTable.ext.errMode = 'none';

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

        },
        {
            targets: 1,
            className: `text-left col-3`,
            render: function (data) {

                try {
                    let courseName;
                    if (!data.courseName && data.courseName.trim() !== '') {
                        courseName = 'Section';
                    } else {
                        courseName = data.courseName;
                    }

                    return `${courseName} ${data.year}-${data.section}`;
                } catch (error) {

                }

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
                        // ConfigPage.table.rows({ selected: true }).deselect();
                        ConfigPage.ActionModal.show(ConfigPage.actionModalObj, ConfigPage.objs.getModalId(), 'add');

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
            setTimeout(() => {
                ConfigPage.dtbleObjects.addCheckboxLabel();
                ConfigPage.table.select();
            }, 0);
            $('table#example').show();
            $(this).parent().show();
        } else {
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
    console.log('container');
    console.log(container);

    if (textLength > container) {
        // If the text overflows, set the font size using clamp with a calculated value
        text.style.fontSize = `clamp(0.8rem, calc(1.325rem + 0.9vw - ${(textLength - container) * 0.02}px), 4rem)`;
        setTimeout(() => {
            let paddingX = parseFloat(getComputedStyle(text.parentNode).paddingLeft) + parseFloat(getComputedStyle(text.parentNode).paddingRight);
            const container = text.parentNode.clientWidth - paddingX;
            const textLength = text.getBoundingClientRect().width;
            console.log('paddingX');
            console.log(paddingX);
            console.log('container');
            console.log(container);

            if (textLength > container) {
                text.style.maxWidth = `calc(95% - ${paddingX}px)`;
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
        // saveButtonLabel.setAttribute("data-bs-toggle", "tooltip");
        // saveButtonLabel.setAttribute("data-bs-title", "No changes made.");
        // saveButtonLabel.setAttribute("data-bs-placement", "right");

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

    static createDatePicker(datepickerId) {
        let dateInput = document.createElement('input')
        dateInput.setAttribute('type', 'text');
        dateInput.classList = 'modal-temp col-10 col-md-5 col-xl-4';
        dateInput.setAttribute('id', datepickerId);
        dateInput.setAttribute('placeholder', 'dd/mm/yy');
        dateInput.setAttribute('pattern', '');
        dateInput.setAttribute('required', '');
        dateInput.setAttribute('data-bs-toggle', 'tooltip');
        dateInput.setAttribute('data-bs-title', 'No changes made.');
        dateInput.setAttribute('data-bs-placement', 'right');

        return dateInput;
    }

    static clearModalBody(modal) {
        while (modal.firstChild) {
            modal.removeChild(modal.firstChild);
        }
    }

    static setAddContent(modalId) {

        if (modalId) {
            ConfigPage.submitMode = 'insert';
            let modalBody = document.querySelector(`#${modalId} .modal-body`);
            this.clearModalBody(modalBody);

            let selectElem = document.createElement("select");
            selectElem.setAttribute("id", "add-section-schedule");

            let dateInput = this.createDatePicker(ConfigPage.datepickerId);

            modalBody.insertBefore(dateInput, modalBody.firstChild);
            modalBody.insertBefore(selectElem, modalBody.firstChild);

            $(`#${ConfigPage.addSectionInputId}`).select2({
                data: ConfigPage.yearSectionList,
                multiple: true,
                placeholder: 'Add a ... to apply schedule',
            });


            $(`#${ConfigPage.addSectionInputId}`).off('select2:opening');
            $(`#${ConfigPage.addSectionInputId}`).on('select2:opening',);

            $(`#${ConfigPage.addSectionInputId}`).off('select2:close');
            $(`#${ConfigPage.addSectionInputId}`).on('select2:close', ConfigPage.handleYearSections);


            ConfigPage.startDatePicker(ConfigPage.datepickerId);

            ConfigPage.datepickerElem = document.getElementById(ConfigPage.datepickerId);

            ConfigPage.delEventListener(ConfigPage.datepickerElem);
            ConfigPage.addEventListenerAndStore(ConfigPage.datepickerElem, 'input', ConfigPage.handleDatepickerChange);

            const SAVE_BUTTON_LABEL = document.getElementById("save-button-label");
            const SAVE_BUTTON = document.getElementById("save-button");

            if (!SAVE_BUTTON_LABEL && !SAVE_BUTTON) {
                const { saveButtonLabel, saveButton } = this.createSaveButton();
                modalBody.appendChild(saveButtonLabel);
                modalBody.appendChild(saveButton);
                ConfigPage.configJs();
            }
        }

    }

    static setUpdateContent(modalId, data) {
        if (modalId) {
            let modalBody = document.querySelector(`#${modalId} .modal-body`);
            this.clearModalBody(modalBody);

            let title = document.createElement('h2');
            title.classList = 'course-section modal-temp';
            title.textContent = 'BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1, BSIT 3-1';

            let dateInput = this.createDatePicker('scheduleInput');

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

ConfigPage.handleYearSections = function (event, isCheckDatepicker = true) {
    let datepickerEvent = new Event('input', {
        bubbles: true, // Allow the event to bubble up the DOM
        cancelable: true // Allow the event to be canceled
    });

    // Dispatch the event on the element
    ConfigPage.datepickerElem.dispatchEvent(datepickerEvent);

    let isValidDate;
    if (!isCheckDatepicker) {
        isValidDate = ConfigPage.handleDatepickerChange(datepickerEvent, false);
    }


    let isValidYearSection = false;

    let selectedRawData = $(`#${ConfigPage.addSectionInputId}`).select2('data');
    if (selectedRawData.length > 0) {
        selectedRawData.forEach(selectedItem => {
            let exists = ConfigPage.yearSectionList.some(configItem => configItem.text === selectedItem.text);
            if (exists) {
                isValidYearSection = true;

            } else {
                console.log(`${selectedItem.text} does not exist in yearSectionList`);
                isValidYearSection = false;
            }
        });
    }

    if (!isCheckDatepicker) {
        ConfigPage.toggleSave(isValidDate && isValidYearSection);
    }

    return isValidYearSection;
}

ConfigPage.submitMode;

ConfigPage.typingTimeout;

ConfigPage.handleDatepickerChange = function (event, ischeckYearSection = true) {

    clearTimeout(ConfigPage.typingTimeout);
    ConfigPage.typingTimeout = setTimeout(() => {
        let scheduleValue = event.target.value.trim();
        let scheduleDateParts = scheduleValue.split('/');
        let scheduleYear = parseInt(scheduleDateParts[2], 10);
        let scheduleMonth = parseInt(scheduleDateParts[1], 10) - 1; // Months are zero-based
        let scheduleDay = parseInt(scheduleDateParts[0], 10);
        let scheduleDate = new Date(scheduleYear, scheduleMonth, scheduleDay);

        scheduleDate.setHours(0, 0, 0, 0);
        let today = new Date(NOW.getTime());
        today.setHours(0, 0, 0, 0);

        let dateIsBlank = scheduleValue === '' || scheduleValue === undefined;

        let sameDate = scheduleDate.getTime() === today.getTime();

        let tooltip = bootstrap.Tooltip.getInstance(`#${ConfigPage.datepickerId}`);

        let isValidDate = false;

        if (event) {
            isValidDate = ConfigPage.dateValidate.validate(event.target);

            switch (true) {
                case dateIsBlank:
                    tooltip._config.title = 'Schedule is blank.';
                    event.target.classList.toggle('input-invalid', true);
                    break;

                case !isValidDate:
                    tooltip._config.title = 'Invalid Year';
                    event.target.classList.toggle('input-invalid', true);
                    break;

                case sameDate:
                    isValidDate = true;
                    event.target.classList.toggle('input-invalid', false);
                    tooltip._config.title = 'Today? Are you sure?';
                    break;

                default:
                    isValidDate = true;
                    tooltip._config.title = '';
                    event.target.classList.toggle('input-invalid', false);
                    break;
            }

            tooltip.update();
            if (ischeckYearSection) {
                let isValidYearSection = ConfigPage.handleYearSections('', false);
                ConfigPage.toggleSave(isValidDate && isValidYearSection);
            }

            return isValidDate;
        }

    }, 400);
}

ConfigPage.toggleSave = function (isValid) {
    const SAVE_BUTTON = document.getElementById("save-button");
    SAVE_BUTTON.disabled = !isValid;

    if (isValid) {
        if (ConfigPage.submitMode === 'insert') {
            ConfigPage.addEventListenerAndStore(SAVE_BUTTON, 'click', ConfigPage.submitAddSchedule);
        }
    } else {
        ConfigPage.delEventListener(SAVE_BUTTON);
    }

};

ConfigPage.submitAddSchedule = function () {
    let selectedRawData = $(`#${ConfigPage.addSectionInputId}`).select2('data');
    let scheduleDate = ConfigPage.selectedDate;
    let scheduleData = []
    if (selectedRawData.length > 0) {
        selectedRawData.forEach(selectedItem => {
            let yearSection = {
                year: selectedItem.id[0],
                section: selectedItem.id[1],
            }
            scheduleData.push(yearSection);
        });
    }
    let data = {
        schedule_input_id: ConfigPage.datepickerId,
        schedule: scheduleDate,
        yearSection_input_id: ConfigPage.addSectionInputId,
        yearSection_data: scheduleData,
    }

    let url = 'src/includes/classes/config-election-sched-controller.php';
    let method = 'PUT';
    let json_data = JSON.stringify(data);

    return fetch(url, {
        method: method,
        body: json_data,
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(function (response) {
            if (!response.ok) {
                ConfigPage.updateTableData({ data, success: false });
                throw new Error('Network response was not ok');
            } else {
                return response.json();
            }

        })
        .then(function (data) {
            console.log('POST request successful:', data);
            ConfigPage.updateTableData({ data, success: true });
        })
        .catch(function (error) {
            console.error('POST request error:', error);
            return { error, success: false };
        });

}

ConfigPage.submitUpdateSchedule = function () {

}

ConfigPage.submitDelSchedule = function () {

}

ConfigPage.handleUpdateResponse = function (RESPONSE) {
    if (!RESPONSE.success) {
        // alert the error
        console.log(RESPONSE.data.message);
    } else {
        ConfigPage.actionModalObj.hide();
    }
}

ConfigPage.updateTableData = function (RESPONSE, draw = true) {

    if (RESPONSE && RESPONSE.data.data && Array.isArray(RESPONSE.data.data)) {

        ConfigPage.handleUpdateResponse(RESPONSE);

        RESPONSE.data.data.forEach(item => {

            if (draw) {
                let tableData = ConfigPage.processData(item);

                if (ConfigPage.submitMode === 'insert') {
                    tableData.forEach(tableItem => {
                        ConfigPage.table.row.add(tableItem).draw(true);
                    });
                }
            }
        });
    } else {
        // console.error('Invalid or missing DATA structure.');
    }

}

