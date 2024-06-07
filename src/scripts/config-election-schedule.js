import { initializeConfigurationJS as ConfigJS } from './configuration.js';
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

// Make the date time act like constant
Object.defineProperty(ConfigPage, 'NOW', {
    value: JS_DATE_TZ(),
    writable: false,
    enumerable: true,
    configurable: false
});


ConfigPage = {
    configJs: function () {
        ConfigJS();
    },
    fetchData: function () {
        let url = 'src/includes/classes/config-election-year-controller.php';

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
    },

}

ConfigPage.configJs();
// ConfigPage.fetchData();

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


