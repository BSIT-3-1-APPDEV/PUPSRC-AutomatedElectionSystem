import { initializeConfigurationJS as ConfigJS } from './configuration.js';
import InputValidator from './input-validator.js';

var ConfigPage = {};

ConfigPage = {
    configJs: function () {
        ConfigJS();
    },
    fetchData: function () {
        var url = 'src/includes/classes/config-election-year-controller.php';

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                console.log('GET request successful:', data);
                ConfigPage.processData(data);
                ConfigPage.displayCurrentYear(ConfigPage.electionYears.currentYear);
                ConfigPage.startDatePicker();
                ConfigPage.initPage();
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },

    electionYears: { currentYear: '', previousYears: [] },
    processData: function (DATA) {
        DATA.forEach(element => {
            const YEAR = parseInt(element.year);

            if (element.is_current_year === 1) {
                ConfigPage.electionYears.currentYear = new Date(YEAR, 0);
            } else {
                ConfigPage.electionYears.previousYears.push(new Date(YEAR, 0));
            }

        });
    },
    startDatePicker: function () {
        ConfigPage.datePickerInst = $(`#year-picker`).datepicker({
            classes: 'col-10 col-md-5 col-xl-4',
            position: 'bottom center',
            language: "en",
            view: 'years',
            minView: 'years',
            // minDate: ConfigPage.electionYears.currentYear,
            minDate: ConfigPage.today,
            maxDate: ConfigPage.fiveYearsAhead,
            clearButton: true,
            isMobile: true,
            autoClose: false,
            dateFormat: "yyyy",
            onSelect: function (formattedDate, date, inst) {
                inst.el.dispatchEvent(new Event('input', { bubbles: true }));
            },
            onShow: function (inst, animationComplete) {
                let thisPicker = document.getElementsByClassName('datepicker');
                let input = document.getElementById('year-picker');
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

ConfigPage.configJs();
ConfigPage.yearInput = document.getElementById('year-picker');

ConfigPage.today = new Date();
ConfigPage.today.setDate(ConfigPage.today.getDate());

ConfigPage.fiveYearsAhead = new Date();
ConfigPage.fiveYearsAhead.setFullYear(ConfigPage.fiveYearsAhead.getFullYear() + 5);
ConfigPage.fiveYearsAhead.setMonth(ConfigPage.fiveYearsAhead.getMonth() + 1, 0);
ConfigPage.fiveYearsAhead.setHours(23, 59, 59, 999);

ConfigPage.fetchData();

ConfigPage.initPage = function () {
    ConfigPage.yearMaxLength = ConfigPage.electionYears.currentYear.getFullYear().toString().length;

    // let yearPattern = new RegExp(`[0-9]{1,${ConfigPage.yearMaxLength}}`);
    let yearPattern = new RegExp(`^[0-9]+$`);
    let patternString = yearPattern.toString().slice(1, -1);


    ConfigPage.customValidation = {
        clear_invalid: true,
        attributes: {
            type: 'text',
            pattern: patternString,
            required: true,
            max_length: ConfigPage.yearMaxLength,
            min_length: 4,
        }
    };

    ConfigPage.yearInputListener = ConfigPage.yearInput.addEventListener('input', ConfigPage.handleDatepickerChange);
    ConfigPage.yearValidate = new InputValidator(ConfigPage.customValidation);
}

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

ConfigPage.displayCurrentYear = function (CURRENT_YEAR) {
    let currentYearContainer = document.querySelector('#curr-year-container h4');
    ConfigPage.currentYear = CURRENT_YEAR.getFullYear();
    currentYearContainer.innerHTML = `Current Election Year: <span>&ensp;${ConfigPage.currentYear}</span>`;
}


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


ConfigPage.handleDatepickerChange();

ConfigPage.typingTimeout;
// ConfigPage.yearInputListener();


