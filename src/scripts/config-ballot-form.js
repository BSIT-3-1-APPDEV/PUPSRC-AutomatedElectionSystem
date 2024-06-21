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

// const initialOrder = ['b-field-4', 'b-field-1', 'b-field-2', 'b-field-3'];
// ConfigPage.setInitialOrder(initialOrder);

ConfigPage.sortableObj = new Sortable(ConfigPage.sortableForms, {
    handle: '.handle',
    filter: 'add-item',
    group: 'nested',
    multiDrag: true, // Enable the plugin
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

ConfigPage.formTemplate = function () {

}


ConfigPage.formCreator = function () {

}

ConfigPage.inputHandler = function () {

}

ConfigPage.defaultFormHandler = function () {

}


ConfigPage.quill1 = new Quill('#b-field-4-name', {
    modules: {
        toolbar: '#b-field-4-name-toolbar'
    },
    placeholder: 'Question',
});

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


ConfigPage.quill2 = new Quill('#b-field-4-desc', {
    modules: {
        toolbar: '#b-field-4-desc-toolbar'
    },
    placeholder: 'Description.',
});

$('#b-field-4-type').selectpicker();

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
