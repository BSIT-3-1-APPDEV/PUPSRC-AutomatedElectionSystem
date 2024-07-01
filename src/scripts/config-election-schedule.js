import { initializeConfigurationJS as ConfigJS, EventListenerUtils as EventUtils } from './configuration.js';
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

ConfigPage.allDayContainerClick = function (event) {
    if (event.target === ConfigPage.allDayContainer) {
        console.log('allday');
        ConfigPage.toggleAllDayBtn.click();
        event.stopPropagation();
    }
};

ConfigPage.handleToggleAllDay = function (event) {
    if (event) {
        ConfigPage.isScheduleChanged = true;
    }
    let isToggled = ConfigPage.toggleAllDayBtn.checked;
    let startTimeContainer = document.querySelector('#datetime-start .time-group');
    let endTimeContainer = document.querySelector('#datetime-end .time-group');

    let startDateContainer = document.querySelector('#datetime-start .date-group');
    let endDateContainer = document.querySelector('#datetime-end .date-group');

    if (isToggled) {
        // startTimeContainer.style.visibility = 'hidden';
        startTimeContainer.style.display = 'none';
        ConfigPage.timePickerStart.value = '00:00';
        ConfigPage.timePickerStart.min = '00:00';

        startDateContainer.classList.remove('col-6');
        startDateContainer.classList.add('col-12');

        // endTimeContainer.style.visibility = 'hidden';
        endTimeContainer.style.display = 'none';
        ConfigPage.timePickerEnd.value = '23:59';
        ConfigPage.timePickerEnd.min = '23:59';

        endDateContainer.classList.remove('col-6');
        endDateContainer.classList.add('col-12');
    } else {
        // startTimeContainer.style.visibility = 'visible';
        // endTimeContainer.style.visibility = 'visible';
        startTimeContainer.style.display = '';
        endTimeContainer.style.display = '';

        startDateContainer.classList.remove('col-12');
        startDateContainer.classList.add('col-6');

        endDateContainer.classList.remove('col-12');
        endDateContainer.classList.add('col-6');
        ConfigPage.resetDatetime(false);
    }
};

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

Object.defineProperty(ConfigPage, 'FIVE_YEARS_AHEAD', {
    get: function () {
        const futureDate = new Date(ConfigPage.NOW);
        futureDate.setFullYear(futureDate.getFullYear() + 5);
        futureDate.setMonth(futureDate.getMonth() + 1, 0);
        futureDate.setHours(23, 59, 59, 999);
        return futureDate;
    },
    enumerable: true,
    configurable: false,
});

Object.defineProperty(ConfigPage, 'DATE_REGEX', {
    get: function () {
        let regex = new RegExp(`^[0-9]+$`);
        let regexString = regex.toString().slice(1, -1);
        return regexString;
    },
    enumerable: true,
    configurable: false,
});

ConfigPage.dateGroupStart = document.getElementById(`datetime-start`);
ConfigPage.datePickerStart = ConfigPage.dateGroupStart.querySelector(`input[type="date"]`);
ConfigPage.timePickerStart = ConfigPage.dateGroupStart.querySelector(`input[type="time"]`);
ConfigPage.dateGroupEnd = document.getElementById(`datetime-end`);
ConfigPage.datePickerEnd = ConfigPage.dateGroupEnd.querySelector(`input[type="date"]`);
ConfigPage.timePickerEnd = ConfigPage.dateGroupEnd.querySelector(`input[type="time"]`);

ConfigPage.resetDatetime = function (date = true, time = true) {


    if (date) {
        let startDateValue = ConfigPage.datePickerStart.getAttribute('data-value');
        let endDateValue = ConfigPage.datePickerEnd.getAttribute('data-value');

        ConfigPage.datePickerStart.value = startDateValue;
        ConfigPage.datePickerStart.min = ConfigPage.TODAY;
        ConfigPage.datePickerEnd.value = endDateValue;
        ConfigPage.datePickerEnd.min = ConfigPage.TODAY;
    }

    if (time) {
        let startTimeValue = ConfigPage.timePickerStart.getAttribute('data-value');
        let endTimeValue = ConfigPage.timePickerEnd.getAttribute('data-value');

        ConfigPage.timePickerStart.value = startTimeValue;
        ConfigPage.timePickerStart.min = '';
        ConfigPage.timePickerEnd.value = endTimeValue;
        ConfigPage.timePickerEnd.min = '';
    }
}

ConfigPage.allDayContainer = document.querySelector('.all-day');
ConfigPage.toggleAllDayBtn = document.getElementById('all-day-input');
ConfigPage.addEventListenerAndStore(ConfigPage.toggleAllDayBtn, 'click', ConfigPage.handleToggleAllDay);

ConfigPage.dateGroupStart = document.getElementById(`datetime-start`);
ConfigPage.datePickerStart = ConfigPage.dateGroupStart.querySelector(`input[type="date"]`);
ConfigPage.timePickerStart = ConfigPage.dateGroupStart.querySelector(`input[type="time"]`);
ConfigPage.dateGroupEnd = document.getElementById(`datetime-end`);
ConfigPage.datePickerEnd = ConfigPage.dateGroupEnd.querySelector(`input[type="date"]`);
ConfigPage.timePickerEnd = ConfigPage.dateGroupEnd.querySelector(`input[type="time"]`);
ConfigPage.datetimePickers = document.querySelectorAll('.schedule-group input');

ConfigPage.addEventListenerAndStore(ConfigPage.allDayContainer, 'click', ConfigPage.allDayContainerClick);

for (const dateTimePicker of ConfigPage.datetimePickers) {

    ConfigPage.addEventListenerAndStore(dateTimePicker, 'click', function () {
        try {
            this.showPicker();
        } catch (error) {
            // Use external library when this fails.
        }
    });
    // dateTimePicker.addEventListener('click', );
}

ConfigPage.fetchData = function (requestData) {
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
            console.log('GET request successful:', data);
            ConfigPage.setErrorDictionary(data.error_codes);
            ConfigPage.setFetchedSchedule(data);

        })
        .catch(function (error) {
            console.error('GET request error:', error);
        });
};

ConfigPage.isoDateConverter = function (date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

ConfigPage.setFetchedSchedule = function (data) {
    ConfigPage.datePickerStart.setAttribute('min', ConfigPage.TODAY);
    ConfigPage.datePickerEnd.setAttribute('min', ConfigPage.TODAY);

    if (data[0]) {
        let startDateTime = data[0].electionStart.split(' ');
        let endDateTime = data[0].electionEnd.split(' ');

        let startDate = startDateTime[0]; // "2024-06-10"
        let startTime = startDateTime[1]; // "12:12:00"
        let endDate = endDateTime[0]; // "2024-06-30"
        let endTime = endDateTime[1]; // "12:09:00"

        startTime = startTime.substring(0, 5); // "12:12"
        endTime = endTime.substring(0, 5);   // "12:09"


        console.log(startDateTime[1]);
        console.log(endDateTime[1]);
        if ((startDateTime[1] == '00:00' || startDateTime[1] == '00:00:00') && (endDateTime[1] === '23:59' || endDateTime[1] === '23:59:00')) {
            ConfigPage.toggleAllDayBtn.checked = true;
        } else {
            ConfigPage.toggleAllDayBtn.checked = false;
        }

        console.log(ConfigPage.toggleAllDayBtn.value);

        ConfigPage.datePickerStart.value = startDate;
        ConfigPage.timePickerStart.value = startTime;
        ConfigPage.datePickerEnd.value = endDate;
        ConfigPage.timePickerEnd.value = endTime;

        ConfigPage.datePickerStart.setAttribute('data-value', startDate);
        ConfigPage.timePickerStart.setAttribute('data-value', startTime);
        ConfigPage.datePickerEnd.setAttribute('data-value', endDate);
        ConfigPage.timePickerEnd.setAttribute('data-value', endTime);

        ConfigPage.handleToggleAllDay();
    }
}



ConfigPage.postData = function (post_data) {
    let url = 'src/includes/classes/config-election-sched-controller.php';
    let method = 'PUT';
    post_data.csrf_token = `${ConfigPage.CSRF_TOKEN}`;
    console.log(post_data);
    let json_data = JSON.stringify(post_data);

    return fetch(url, {
        method: method,
        body: json_data,
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(async function (response) {
            if (!response.ok) {
                let data = await response.json();
                throw { response, data };
            }
            return Promise.all([response.clone(), response.json()]);
        })
        .then(async ([response, data]) => {
            console.log('POST request successful:', response);
            console.log('Status:', response.status);
            console.log('Data:', data);



            return { data, success: true };
        })
        .catch(function (error) {
            console.error('PUT request error:', error.data);
            console.error('Status:', error.response.status);
            ConfigPage.handleResponseStatus(error.response.status, error.data);
            return { data: error.data, success: false };
        });
};

ConfigPage.handleResponseStatus = function (statusCode, data) {
    if (statusCode >= 400) {
        // if (statusCode == 401) {
        ConfigPage.createToast(ConfigPage.errorDictionary[data.message] || data.message, 'danger');
    }
}


ConfigJS();

Object.defineProperty(ConfigPage, 'CSRF_TOKEN', {
    value: setCSRFToken(),
    writable: false,
    enumerable: false,
    configurable: false
});

console.log(ConfigPage.CSRF_TOKEN);
ConfigPage.fetchData({ csrf: ConfigPage.CSRF_TOKEN });

ConfigPage.startDateValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        // pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.TODAY,
        max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    },
    customMsg: {
        // pattern: 'Only date in numbers are allowed.',
        required: true,
        min: 'Date cannot be past',
        max: '',
    },
    errorFeedback: {
        required: 'ERR_MISSING_START_DATE',
        min: 'ERR_START_DATE_EXCEEDS_LIMIT',
        max: 'ERR_START_DATE_EXCEEDS_LIMIT',
    }
}


ConfigPage.endDateValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        // pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.TODAY,
        max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    },
    customMsg: {
        // pattern: 'Only date in numbers are allowed.',
        required: true,
        min: 'Date cannot be past',
        max: '',
    },
    errorFeedback: {
        required: 'ERR_MISSING_END_DATE',
        min: 'ERR_END_DATE_EXCEEDS_LIMIT',
        max: 'ERR_END_DATE_EXCEEDS_LIMIT',
    }
}

ConfigPage.startTimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'time',
        // pattern: ConfigPage.DATE_REGEX,
        required: true,
    },
    customMsg: {
        // pattern: 'Only date in numbers are allowed.',
        required: true,
    },
    errorFeedback: {
        required: 'ERR_MISSING_END_TIME',
    }
}


ConfigPage.endTimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'time',
        // pattern: ConfigPage.DATE_REGEX,
        required: true,
    },
    customMsg: {
        // pattern: 'Only date in numbers are allowed.',
        required: true,
    },
    errorFeedback: {
        required: 'ERR_MISSING_END_TIME',
    }
}


ConfigPage.startDateValidator = new InputValidator(ConfigPage.startDateValidation);
ConfigPage.endDateValidator = new InputValidator(ConfigPage.endDateValidation);
ConfigPage.startTimeValidator = new InputValidator(ConfigPage.startTimeValidation);
ConfigPage.endTimeValidator = new InputValidator(ConfigPage.endTimeValidation);



ConfigPage.typingTimeout;

ConfigPage.getDatetimeInput = function (dateGroup) {
    let datePicker = dateGroup.querySelector(`input[type="date"]`);
    let timePicker = dateGroup.querySelector(`input[type="time"]`);

    let dateValue = datePicker.value;
    let timeValue = timePicker.value;

    // Combine the date and time into a single string
    let dateTimeString = `${dateValue}T${timeValue}`;
    const utcDateString = dateTimeString.toLocaleString('en-US', { timeZone: 'UTC' });
    return utcDateString;
}



ConfigPage.inputFeedbackHandler = function (event, feedbackId) {
    console.log(event);
    console.log(feedbackId);
    console.log(ConfigPage.errorDictionary[feedbackId]);
    try {
        const inputElement = event;
        // const parentElement = inputElement.parentNode;
        const parentElement = inputElement.closest('.datetime');

        const feedbackField = parentElement.nextElementSibling;

        console.log(feedbackField);
        feedbackField.textContent = ConfigPage.errorDictionary[feedbackId];
    } catch (error) {

    }
}

ConfigPage.setErrorDictionary = function (definitions) {
    ConfigPage.errorDictionary = definitions;
    console.log(ConfigPage.errorDictionary);
}

ConfigPage.handleValidation = function (inputElement, validatorObj, isInput = true) {
    let parentElement = inputElement.closest('.datetime');

    let feedbackField = parentElement.nextElementSibling;
    console.log(feedbackField);

    if (validatorObj.validate(inputElement, ConfigPage.inputFeedbackHandler)) {
        if (isInput === true) {
            inputElement.classList.remove('is-invalid');
            feedbackField.textContent = "\u00A0";
        }
    } else {
        inputElement.classList.add('is-invalid');
    }

    if (parentElement && parentElement.id === 'datetime-start') {
        ConfigPage.toggleEndDateTime();
    }

    ConfigPage.toggleSaveBtn();
}

ConfigPage.handleInput = function (event, validatorObj) {
    ConfigPage.isScheduleChanged = true;
    const inputElement = event.target;
    // const parentElement = inputElement.parentNode;

    clearTimeout(ConfigPage.typingTimeout);
    ConfigPage.typingTimeout = setTimeout(() => {
        try {

            ConfigPage.handleValidation(inputElement, validatorObj);
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 400);
}



ConfigPage.toggleEndDateTime = function () {
    console.log(ConfigPage.dateGroupStart.querySelector('.is-invalid'));
    console.log(ConfigPage.dateGroupStart.querySelector('.is-invalid')?.matches('.form-control'));
    const hasInvalidStart = ConfigPage.dateGroupStart.querySelector('.is-invalid')?.matches('.form-control');

    if (hasInvalidStart) {
        ConfigPage.datePickerEnd.value = '';
        ConfigPage.timePickerEnd.value = '';
        ConfigPage.datePickerEnd.disabled = true;
        ConfigPage.timePickerEnd.disabled = true;

    } else {
        ConfigPage.datePickerEnd.disabled = false;
        ConfigPage.timePickerEnd.disabled = false;
    }
};

ConfigPage.toggleSaveBtn = function () {
    const hasInvalidStart = ConfigPage.dateGroupStart.querySelector('.is-invalid')?.matches('.form-control');
    const hasInvalidEnd = ConfigPage.dateGroupEnd.querySelector('.is-invalid')?.matches('.form-control');

    if (hasInvalidStart || hasInvalidEnd) {

        ConfigPage.submitBtn.disabled = true;

    } else {

        ConfigPage.submitBtn.disabled = false;
    }
};


ConfigPage.addEventListenerAndStore(ConfigPage.datePickerStart, 'input', (event) => ConfigPage.handleInput(event, ConfigPage.startDateValidator));
ConfigPage.addEventListenerAndStore(ConfigPage.datePickerEnd, 'input', (event) => ConfigPage.handleInput(event, ConfigPage.endDateValidator));
ConfigPage.addEventListenerAndStore(ConfigPage.timePickerStart, 'input', (event) => ConfigPage.handleInput(event, ConfigPage.startTimeValidator));
ConfigPage.addEventListenerAndStore(ConfigPage.timePickerEnd, 'input', (event) => ConfigPage.handleInput(event, ConfigPage.endTimeValidator));

ConfigPage.submitBtn = document.querySelector('section.schedule .action-btn #submit-schedule');
ConfigPage.cancelBtn = document.querySelector('section.schedule .action-btn #cancel-schedule');
ConfigPage.editBtn = document.querySelector('section.schedule .action-btn #edit-schedule');

// ConfigPage.delEventListener(saveButton, 'click');
// ConfigPage.addEventListenerAndStore(saveButton, 'click', ConfigPage.saveFunc);

ConfigPage.handleSetSchedule = function () {
    ConfigPage.handleValidation(ConfigPage.datePickerStart, ConfigPage.startDateValidator, false);
    ConfigPage.handleValidation(ConfigPage.datePickerEnd, ConfigPage.endDateValidator, false);
    ConfigPage.handleValidation(ConfigPage.timePickerStart, ConfigPage.startTimeValidator, false);
    ConfigPage.handleValidation(ConfigPage.timePickerEnd, ConfigPage.endTimeValidator, false);

    let schedule = {
        electionStart: ConfigPage.getDatetimeInput(ConfigPage.dateGroupStart),
        electionEnd: ConfigPage.getDatetimeInput(ConfigPage.dateGroupEnd),
    }

    ConfigPage.postData(schedule);
}

ConfigPage.warningModal = new bootstrap.Modal(document.getElementById('warning-modal'));

ConfigPage.isScheduleChanged = false;

ConfigPage.handleDiscardSchedule = async function () {
    if (ConfigPage.isScheduleChanged && ConfigPage.checkUnsaveSchedule) {

        if (await ConfigPage.showDiscardModal() == 'true') {
            ConfigPage.resetDatetime();
            console.log('true');
        }
    }
    else {
        console.log('false');
    }
}

ConfigPage.checkUnsaveSchedule = function () {
    let isStartDateChange = ConfigPage.datePickerStart.value == ConfigPage.datePickerStart.getAttribute('data-value');
    let isEndDateChange = ConfigPage.datePickerStart.value == ConfigPage.datePickerEnd.getAttribute('data-value');
    let isStartTimeChange = ConfigPage.datePickerStart.value == ConfigPage.timePickerStart.getAttribute('data-value');
    let isEndTimeChange = ConfigPage.datePickerStart.value == ConfigPage.timePickerEnd.getAttribute('data-value');
    let conditions = [isStartDateChange, isEndDateChange, isStartTimeChange, isEndTimeChange];

    return conditions.every(condition => condition === true);
}

ConfigPage.showDiscardModal = async function () {
    // https://stackoverflow.com/questions/65454144/javascript-await-bootstrap-modal-close-by-user
    ConfigPage.warningModal.show();

    return new Promise(resolve => {
        $('.prompt-action button').off('click');
        $('.prompt-action button').on('click', (event) => {
            const buttonValue = event.currentTarget.value;
            console.log('button value ', buttonValue);
            ConfigPage.warningModal.hide();
            resolve(buttonValue);
        });
    });
}


ConfigPage.addEventListenerAndStore(ConfigPage.submitBtn, 'click', ConfigPage.handleSetSchedule);
ConfigPage.addEventListenerAndStore(ConfigPage.cancelBtn, 'click', ConfigPage.handleDiscardSchedule);





ConfigPage.toastContainer = document.querySelector('.toast-container-unstacked');

ConfigPage.createToast = function (message, type) {
    const toast = document.createElement('div');
    toast.classList.add('toast');

    const toastBody = document.createElement('div');
    toastBody.classList.add('toast-body', `text-bg-${type}`);
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('toast-content');
    messageDiv.textContent = message;
    toastBody.prepend(messageDiv);


    const closeContainer = document.createElement('div');
    const closeButton = document.createElement('button');
    closeButton.classList.add('btn-close');
    closeButton.setAttribute('type', 'button');
    closeButton.setAttribute('data-bs-dismiss', 'toast');
    closeButton.setAttribute('aria-label', 'Close');

    closeContainer.appendChild(closeButton);
    toastBody.appendChild(closeContainer);
    toast.appendChild(toastBody);

    ConfigPage.toastContainer.appendChild(toast);

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });

    let toastElList = [].slice.call(
        document.querySelectorAll('.toast'))
    let toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    })

    toastList.forEach(toast => toast.hide())
    new bootstrap.Toast(toast).show();
}

ConfigPage.setToViewOnlyState = function () {

}

ConfigPage.setToEditState = function () {

}

