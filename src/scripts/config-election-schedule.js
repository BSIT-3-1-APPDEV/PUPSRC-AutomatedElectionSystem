import { initializeConfigurationJS as ConfigJS, EventListenerUtils as EventUtils } from './configuration.js';
import InputValidator from './input-validator.js';

/**
 * The ConfigPage object holds variables classes and function of the current page.
 * If ConfigPage is already defined, it retains its current value; otherwise, it is initialized as an empty object.
 * It will be reset to empty when another configuration script is added and executed.
 * @type {object}
 */
var ConfigPage = ConfigPage || {};

EventUtils.clearEventListeners(ConfigPage.eventListeners);
ConfigPage = null;
ConfigPage = {};

ConfigPage.fetchData = function () {
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
        })
        .catch(function (error) {
            console.error('GET request error:', error);
        });
};

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
        .then(function (response) {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(function (data) {
            console.log('POST request successful:', data);
            return { data, success: true };
        })
        .catch(function (error) {
            console.error('POST request error:', error);
            return { error, success: false };
        });
};



ConfigJS();

Object.defineProperty(ConfigPage, 'CSRF_TOKEN', {
    value: setCSRFToken(),
    writable: false,
    enumerable: false,
    configurable: false
});

console.log(ConfigPage.CSRF_TOKEN);
// ConfigPage.fetchData({csrf: ConfigPage.CSRF_TOKEN});

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
        return today;
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

ConfigPage.startDateValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.TODAY.toISOString().split('T')[0],
        max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    }
}


ConfigPage.endDateValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.TODAY.toISOString().split('T')[0],
        max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    }
}

ConfigPage.startTimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.NOW.toLocaleTimeString('en-GB', { hour12: false }),
        // max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    }
}


ConfigPage.endTimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'date',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        min: ConfigPage.NOW.toLocaleTimeString('en-GB', { hour12: false }),
        // max: ConfigPage.FIVE_YEARS_AHEAD.toISOString().split('T')[0],
    }
}

const currentTime = ConfigPage.NOW.toLocaleTimeString('en-GB', { hour12: false });


ConfigPage.startDatetimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'time',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        readonly: true,
        hidden: true,
        // min:,
        // max:
    }
}

ConfigPage.endDatetimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'time',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        readonly: true,
        hidden: true,
        // min:,
        // max:
    }
}

ConfigPage.startDatetimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'text',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        readonly: true,
        hidden: true,
        // min:,
        // max:
    }
}

ConfigPage.endDatetimeValidation = {
    clear_invalid: false,
    attributes: {
        type: 'text',
        pattern: ConfigPage.DATE_REGEX,
        required: true,
        readonly: true,
        hidden: true,
        // min:,
        // max:
    }
}


ConfigPage.startDateValidator = new InputValidator(ConfigPage.startDateValidation);

ConfigPage.handleDatepickerChange = function (event) {

    clearTimeout(ConfigPage.typingTimeout);
    ConfigPage.typingTimeout = setTimeout(() => {
        let yearSubmit = document.getElementById('save-button');
        let yearVal = ConfigPage.yearInput.value.trim();

        let isValidDate;

        let yearIsBlank = yearVal === '' || yearVal === undefined;
        let sameYear = yearVal == ConfigPage.currentYear;
        let tooltip = bootstrap.Tooltip.getInstance("#save-button-label");

        yearSubmit.disabled = true;

        if (event) {
            isValidDate = ConfigPage.yearValidate.validate(event.target);

            switch (true) {
                case yearIsBlank:
                    tooltip._config.title = 'Year is blank.';
                    event.target.classList.toggle('input-invalid', true);
                    break;

                case !isValidDate:
                    tooltip._config.title = 'Invalid Year';
                    event.target.classList.toggle('input-invalid', true);
                    break;

                case sameYear:
                    tooltip._config.title = 'Year is not changed.';
                    event.target.classList.toggle('input-invalid', true);
                    break;

                default:
                    yearSubmit.disabled = false;
                    tooltip._config.title = '';
                    event.target.classList.toggle('input-invalid', false);
                    break;
            }
        }

        tooltip.update();
    }, 400);
}


// ConfigPage.handleDatepickerChange();

ConfigPage.typingTimeout;
// ConfigPage.yearInputListener();


ConfigPage.setDatetimeInput = function (groupId) {
    let dateGroup = document.getElementById(`${groupId}`);
    let datePicker = dateGroup.querySelector(`input[type="date"]`);
    let timePicker = dateGroup.querySelector(`input[type="time"]`);
    // let datetimePicker = dateGroup.querySelector(`input[type="datetime-local"]`);
    // console.log('date ' + datePicker.value);
    // console.log('time ' + timePicker.value);
    // console.log('datetime ' + datetimePicker.value);

    let dateValue = datePicker.value;
    let timeValue = timePicker.value;

    // Combine the date and time into a single string
    let combinedDateTime = `${dateValue}T${timeValue}+08:00`;

    // Create a new Date object and convert it to ISO format
    let isoFormat = new Date(combinedDateTime).toISOString();
    console.log('datetime format: ' + combinedDateTime);
    console.log('ISO format: ' + isoFormat);
    return isoFormat;
}

let submit = document.getElementById('submit-schedule');
submit.addEventListener('click', function () {
    let schedule = {
        electionStart: ConfigPage.setDatetimeInput('datetime-start'),
        electionEnd: ConfigPage.setDatetimeInput('datetime-end'),
    }

    ConfigPage.postData(schedule);
});

