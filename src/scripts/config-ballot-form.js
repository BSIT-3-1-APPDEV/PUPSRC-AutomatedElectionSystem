import { initializeConfigurationJS as ConfigJS, EventListenerUtils as EventUtils } from './configuration.js';
import InputValidator from './input-validator.js';
import Sortable from '../../vendor/node_modules/sortablejs/modular/sortable.complete.esm.js';
// import { MultiDrag, Swap } from '../../vendor/node_modules/sortablejs';

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

ConfigJS(ConfigPage);
ConfigPage.removeEventListeners();
ConfigPage = null;
ConfigPage = {};


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
    const key = `${element}-${event}`;
    ConfigPage.eventListeners.set(key, handler);
}

/**
 * Removes the event listener associated with the specified element and deletes its entry from the ConfigPage.eventListeners Map.
 * @function
 * @name ConfigPage.delEventListener
 * @memberof ConfigPage
 * @param {Element} element - The DOM element from which the event listener is removed.
 */
ConfigPage.delEventListener = function (element, event) {
    const key = `${element}-${event}`;
    if (ConfigPage.eventListeners.has(key)) {
        const handler = ConfigPage.eventListeners.get(key);
        element.removeEventListener(event, handler);
        ConfigPage.eventListeners.delete(key);
    }
}


ConfigPage.isoDateConverter = function (date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

// Make the date time act like constant
Object.defineProperty(ConfigPage, 'NOW', {
    value: JS_DATE_TZ(),
    writable: false,
    enumerable: true,
    configurable: false
});

Object.defineProperty(ConfigPage, 'TODAY', {
    get: function () {
        const today = new Date(ConfigPage.NOW);
        today.setHours(0, 0, 0, 0);
        let isoToday = ConfigPage.isoDateConverter(today);
        return isoToday;
    },
    enumerable: true,
    configurable: false,
});

Object.defineProperty(ConfigPage, 'CSRF_TOKEN', {
    value: setCSRFToken(),
    writable: false,
    enumerable: false,
    configurable: false
});

ConfigPage.fetchSchedule = function (requestData) {
    let url = `src/includes/classes/config-election-sched-controller.php`;
    const queryParams = new URLSearchParams(requestData);
    url = `${url}?${queryParams.toString()}`;

    fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function (data) {
            // process shedule data

        })
        .catch(function (error) {
            console.error('GET request error:', error);
        });
};

ConfigPage.fetchSchedule({ csrf: ConfigPage.CSRF_TOKEN });

ConfigPage.fetchBallotConfig = function (requestData) {
    let url = `src/includes/classes/config-ballot-form-controller.php`;
    const queryParams = new URLSearchParams(requestData);
    url = `${url}?${queryParams.toString()}`;

    fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function (data) {
            // process shedule data
            console.log(data);

            const errorCodes = extractErrorCodes(data);
            console.log(errorCodes);

            const ballotConfigData = removeErrorCodes(data);
            console.log(ballotConfigData);

            ConfigPage.initBallotConfig(ballotConfigData);

        })
        .catch(function (error) {
            console.error('GET request error:', error);
        });
};


function extractErrorCodes(data) {
    if ("error_codes" in data) {
        const errorCodes = data.error_codes;
        return errorCodes;
    }
    return {};
}

function removeErrorCodes(data) {
    const cleanData = Object.assign({}, data);
    delete cleanData.error_codes;
    return cleanData;
}

ConfigPage.lastSequence = 0;
ConfigPage.initialOrder = [];

ConfigPage.fetchBallotConfig({ csrf: ConfigPage.CSRF_TOKEN });

ConfigPage.initBallotFormData;
ConfigPage.ballotFormData;

ConfigPage.sortableForms = document.getElementById('sortableForms');

ConfigPage.setInitialOrder = function (order) {
    order.forEach(id => {
        const element = ConfigPage.sortableForms.querySelector(`[data-id="${id}"]`);
        if (element) {
            ConfigPage.sortableForms.appendChild(element);
        }
    });
}


ConfigPage.sortableObj = new Sortable(ConfigPage.sortableForms, {
    handle: '.handle',
    filter: 'add-item',
    group: 'nested',
    multiDrag: true,
    selectedClass: "sortable-selected", // Class name for selected item
    avoidImplicitDeselect: false,
    fallbackTolerance: 3, // So that we can select items on mobile
    animation: 150,
    onSort: function (/**Event*/evt) {
        console.log(evt);
        const order = ConfigPage.sortableObj.toArray();
        console.log('New order:', order);
    },
    // onEnd: function (evt) {
    //     const order = ConfigPage.sortableObj.toArray();
    //     console.log('New order:', order);
    // },
});

ConfigPage.initBallotConfig = function (data) {
    // Convert the data object to an array of its values
    const items = Object.values(data);

    console.log(items);
    let prevGroupId = 0;
    items.forEach(function (item) {
        let currentGroupId = parseInt(item.group_id);
        console.log(ConfigPage.lastSequence);
        console.log(currentGroupId);

        ConfigPage.lastSequence++;


        if (currentGroupId !== prevGroupId) {
            ConfigPage.initialOrder.push(`b-field-${item.group_id}`);
        }
        console.log(ConfigPage.lastSequence);


        if (item.attributes && typeof item.attributes === 'string') {
            try {
                item.attributes = JSON.parse(item.attributes);
            } catch (e) {
                item.attributes = {};
            }
        } else if (typeof item.attributes !== 'object') {
            item.attributes = {};
        }

        if (item.attributes.default) {
            ConfigPage.initDefaultFields(item);
        } else {

        }

        prevGroupId = currentGroupId;

    });

    console.log(ConfigPage.initialOrder);
    ConfigPage.setInitialOrder(ConfigPage.initialOrder);

    ConfigPage.addBtn = document.querySelector('.list-group-item.add-item button');
    ConfigPage.addEventListenerAndStore(ConfigPage.addBtn, 'click', function () {
        ConfigPage.customField.createField(ConfigPage.lastSequence);
    });

};

ConfigPage.initDefaultFields = function (data) {
    console.log(data);
    let formName = ConfigPage.sortableForms.querySelector(`.form-name.default[value="${data.field_name}"]`);
    if (formName) {
        let toggleSwitch = formName.nextElementSibling.querySelector('.form-check.form-switch input[type="checkbox"]');
        try {
            toggleSwitch.checked = !!data.attributes.active;
        } catch (error) {

        }

        const fieldItem = formName.closest('.field-item');
        try {
            fieldItem.id = data.field_id;
        } catch (error) {

        }

        const parent = formName.closest('.list-group-item');

        try {
            parent.setAttribute('data-id', `b-field-${data.group_id}`);
        } catch (error) {

        }


    }
}


ConfigPage.inputHandler = function () {

}

ConfigPage.defaultFieldInputHandler = function () {

}

ConfigPage.customField = class {
    static fieldTypes = [
        { value: 'short_text', text: 'Text Input' },
        { value: 'multiple_choice', text: 'Multiple Choice' },
    ]


    static formatButtons = [
        { className: 'ql-bold', icon: 'bold' },
        { className: 'ql-italic', icon: 'italic' },
        { className: 'ql-underline', icon: 'underline' },
        { className: 'ql-link', icon: 'link-2' },
        {
            className: 'ql-clean', svg: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" id="format-clear">
                                          <path fill="none" d="M0 0h24v24H0V0z" fill="currentColor"></path>
                                          <path d="M20 8V5H6.39l3 3h1.83l-.55 1.28 2.09 2.1L14.21 8zM3.41 4.86L2 6.27l6.97 6.97L6.5 19h3l1.57-3.66L16.73 21l1.41-1.41z" fill="currentColor"></path>
                                        </svg>` }
    ];

    static setTypeOptions(selectElement) {
        this.fieldTypes.forEach(optionData => {
            let option = document.createElement('option');
            option.value = optionData.value;
            option.text = optionData.text;
            if (optionData.disabled) {
                option.disabled = true;
            }
            selectElement.appendChild(option);
        });
    }


    static createPluginObjects(fieldId) {
        new Quill(`#b-field-${fieldId}-name`, {
            modules: {
                toolbar: `#b-field-${fieldId}-name-toolbar`
            },
            placeholder: 'Question',
        });

        new Quill(`#b-field-${fieldId}-desc`, {
            modules: {
                toolbar: `#b-field-${fieldId}-desc-toolbar`
            },
            placeholder: 'Description.',
        });

        $(`#b-field-${fieldId}-type`).selectpicker();
    }


    static createField(fieldId) {
        let orderableField = document.createElement('div');
        orderableField.className = 'list-group-item';
        orderableField.id = '';
        orderableField.setAttribute('data-id', `b-field-${fieldId}`);

        let orderableHandle = document.createElement('div');
        orderableHandle.className = 'handle';

        let handleIcon = document.createElement('span');
        handleIcon.className = 'fas fa-grip-lines';

        let fieldItem = document.createElement('div');
        fieldItem.className = 'field-item';


        orderableHandle.insertBefore(handleIcon, orderableHandle.firstChild);

        orderableField.insertBefore(orderableHandle, orderableField.firstChild);

        fieldItem.appendChild(this.createFieldHeader(fieldId));
        fieldItem.appendChild(this.createActionButtons());

        orderableField.appendChild(fieldItem);

        ConfigPage.sortableForms.appendChild(orderableField);
        feather.replace();

        this.createPluginObjects(fieldId);

        ConfigPage.lastSequence++;
    }

    static createFieldHeader(fieldId) {
        let fieldHeader = document.createElement('div');
        fieldHeader.className = 'field-item-header';

        fieldHeader.appendChild(this.createQuestion(fieldId));
        fieldHeader.appendChild(this.createTypeSelector(fieldId));
        fieldHeader.appendChild(this.createRequiredToggle(fieldId));
        fieldHeader.appendChild(this.createDescription(fieldId));

        return fieldHeader;
    }

    static createQuestion(fieldId) {
        let fieldNameForm = document.createElement('div');
        fieldNameForm.className = 'field-name-form col-8 col-md-6';

        let fieldGroup = document.createElement('div')

        let fieldName = document.createElement('div');
        fieldName.id = `b-field-${fieldId}-name`;
        fieldName.className = 'ql-container';

        let fieldNameToolbar = document.createElement('div');
        fieldNameToolbar.id = `b-field-${fieldId}-name-toolbar`;
        fieldNameToolbar.className = 'ql-toolbar ql-snow';

        this.createFormatBtn(fieldNameToolbar);

        fieldGroup.appendChild(fieldName);
        fieldGroup.appendChild(fieldNameToolbar);
        fieldNameForm.insertBefore(fieldGroup, fieldNameForm.firstChild);

        return fieldNameForm;
    }

    static createFormatBtn(toolbar) {
        this.formatButtons.forEach(button => {
            let btn = document.createElement('button');
            btn.className = button.className;
            btn.setAttribute('tabindex', 0);
            if (button.icon) {
                let icon = document.createElement('i');
                icon.setAttribute('data-feather', button.icon);
                btn.appendChild(icon);
            } else if (button.svg) {
                btn.innerHTML = button.svg;
            }
            toolbar.appendChild(btn);
        });
    }

    static createTypeSelector(fieldId) {
        let fieldTypeForm = document.createElement('select');
        fieldTypeForm.className = 'field-type-form col-12 col-md-4';
        fieldTypeForm.id = `b-field-${fieldId}-type`;

        this.setTypeOptions(fieldTypeForm);

        return fieldTypeForm;
    }

    static createRequiredToggle() {
        let toggleContainer = document.createElement('div');
        toggleContainer.className = '';

        let toggleParent = document.createElement('div');
        toggleParent.className = 'form-check form-switch';

        let toggle = document.createElement('input');
        toggle.className = 'form-check-input';
        toggle.type = 'checkbox';
        toggle.role = 'switch';
        toggle.id = 'checkbox-candidates';
        toggle.checked = true;

        let toggleLabel = document.createElement('label');
        toggleLabel.className = 'form-check-label';
        toggleLabel.htmlFor = 'checkbox-candidates';

        toggleParent.appendChild(toggle);
        toggleParent.appendChild(toggleLabel);
        toggleContainer.insertBefore(toggleParent, toggleContainer.firstChild);

        return toggleContainer;
    }

    static createDescription(fieldId) {

        let fieldDescContainer = document.createElement('div');
        fieldDescContainer.className = 'field-desc-form col-12';

        // Create the inner div for field description
        let fieldDescForm = document.createElement('div');
        fieldDescForm.className = 'col-12 col-md-6';

        // Create the div for b-field-4-desc
        let fieldDesc = document.createElement('div');
        fieldDesc.id = `b-field-${fieldId}-desc`;
        fieldDesc.className = 'ql-container';

        // Create the toolbar div for b-field-4-desc
        let fieldDescToolbar = document.createElement('div');
        fieldDescToolbar.id = `b-field-${fieldId}-desc-toolbar`;
        fieldDescToolbar.className = 'ql-toolbar ql-snow';

        this.createFormatBtn(fieldDescToolbar);

        fieldDescForm.appendChild(fieldDesc);
        fieldDescForm.appendChild(fieldDescToolbar);

        fieldDescContainer.appendChild(fieldDescForm);

        return fieldDescContainer;
    }

    static createActionButtons() {

        let fieldAction = document.createElement('div');
        fieldAction.className = 'field-action';

        let btnGroup = document.createElement('div');
        btnGroup.className = 'btn-group';
        btnGroup.setAttribute('role', 'group');
        btnGroup.setAttribute('aria-label', 'Field Menu Button');

        let copyButton = document.createElement('button');
        copyButton.className = 'btn btn-secondary';
        let copyIcon = document.createElement('i');
        copyIcon.setAttribute('data-feather', 'copy');
        copyButton.appendChild(copyIcon);

        let trashButton = document.createElement('button');
        trashButton.className = 'btn btn-secondary';
        let trashIcon = document.createElement('i');
        trashIcon.setAttribute('data-feather', 'trash-2');
        trashButton.appendChild(trashIcon);

        btnGroup.appendChild(copyButton);
        btnGroup.appendChild(trashButton);
        fieldAction.insertBefore(btnGroup, fieldAction.firstChild);

        return fieldAction;
    }

}

// let tempSelct = document.getElementById('b-field-4-type');

// ConfigPage.customField.setTypeOptions(tempSelct);

// ConfigPage.customField.createPluginObjects(4);




const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike', 'link', 'clean'],        // toggled buttons
    // ['blockquote', 'code-block'],
    // ['link', 'image', 'video', 'formula'],

    // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    // [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
    // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    // [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    // [{ 'direction': 'rtl' }],                         // text direction

    // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    // [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

    // [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    // [{ 'font': [] }],
    // [{ 'align': [] }],

    // ['clean']                                         // remove formatting button
];





// $('.max-vote-picker').selectpicker('destroy');


// ConfigPage.quill = new Quill('#posDescrptn', {
//     modules: {
//         toolbar: '#rich-txt-toolbar'
//     },
//     placeholder: 'Type duties and responsibilities here.',
// });

// ConfigPage.delEventListener(saveButton, 'click');
// ConfigPage.addEventListenerAndStore(saveButton, 'click', ConfigPage.saveFunc);

// quill.setContents(description);

// var initialOrder = ConfigPage.sortableObj.toArray();

// console.log('initialOrder');
// console.log(initialOrder);

// Sortable.create(list, {
//     multiDrag: true,
//     selectedClass: "selected"
// });
