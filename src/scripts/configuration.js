import ViewportDimensions from './viewport.js';


const MAIN_CONTENT_MARGIN = $('.content-margin');
const SIDEBAR = $(sidebar);

function setMainContentMargin() {
    if (!$('.sidebar').hasClass('close')) {
        MAIN_CONTENT_MARGIN.css('margin', `1.75rem calc(4rem + 5vw - ${SIDEBAR.width()}px * 0.3)`);
    } else {
        MAIN_CONTENT_MARGIN.css('margin', '1.75rem calc(4rem + 5vw)');
    }
}


setMainContentMargin();
$(sidebarClose).on('click', function () {
    setMainContentMargin();
});

let table = $('#example').DataTable({
    rowReorder: true,
    columnDefs: [
        { targets: 0, className: `text-center col-1` },
        { targets: 1, className: `text-left text-editable` }
    ],
    select: {
        style: 'multi',
        selector: 'row'
    },
    layout: {
        bottomStart: null,
        bottomEnd: null,
        topStart: null,
        topEnd: null
    },
    scrollY: '220px',
    scrollCollapse: true,
    paging: false
});

let text_editable_input;

function getAllTextEditable() {
    return $('main input[type="text"].text-editable');
}

function setMaxWidth() {
    var $txt_editable_width = $('main td.text-editable').width();

    $('main input[type="text"].text-editable').css({
        'max-width': ($txt_editable_width * 0.95) + 'px'
    });

}

class InputValidator {
    #validations;

    constructor(validations) {
        this.#validations = validations;
    }

    validate(input_obj) {

        if (!(input_obj instanceof Element)) {
            throw new Error('Invalid input element provided.');
        }

        let input_element = $(input_obj)[0];
        let original_value = $(input_obj).val();
        let trimmed_value = original_value;
        console.log('orig val ' + original_value);

        this.enforceAttributes(input_element);

        if (input_element.validity.typeMismatch) {
            console.log('type');
            return false;
        }


        if (this.#validations.attributes.required) {
            if (input_element.validity.valueMissing || original_value.trim() === '') {
                $(input_obj).val(original_value.trim());
                setMaxWidth();
                console.log('required');
                return false;
            }
        }

        if (this.#validations.attributes.pattern) {
            const regex = new RegExp(this.#validations.attributes.pattern);
            let trimmed_value = '';

            if (input_element.validity.patternMismatch) {
                for (let i = 0; i < original_value.length; i++) {
                    const char = original_value[i];
                    if (regex.test(char)) {
                        trimmed_value += char;
                    }
                }

                console.log('pattern ' + this.#validations.attributes.pattern);
                console.log('regex ' + regex);

                $(input_obj).val(trimmed_value)

                return false;
            }
        }


        if (this.#validations.attributes.max_length && input_element.validity.tooLong) {
            trimmed_value = trimmed_value.slice(0, this.#validations.attributes.max_length);
            console.log('trimmed ' + trimmed_value);
            console.log('max length ' + this.#validations.attributes.max_length);
            return false;
        }

        if (this.#validations.attributes.min && input_element.validity.rangeUnderflow) {
            console.log('min');
            return false;
        }

        if (this.#validations.attributes.max && input_element.validity.rangeOverflow) {
            trimmed_value = trimmed_value.slice(0, validations.max);
            console.log('max');
            return false;
        }

        if (this.#validations.trailing && Object.keys(this.#validations.trailing).length > 0) {
            Object.keys(this.#validations.trailing).forEach(pattern => {
                let replacementValue = this.#validations.trailing[pattern];
                try {
                    const regex = new RegExp(pattern, 'g');
                    trimmed_value = trimmed_value.replace(regex, replacementValue);

                    if (original_value !== trimmed_value) {
                        $(input_obj).val(trimmed_value)
                        console.log('regex ' + pattern);

                        return false;
                    }
                } catch (error) {
                    console.error(`Invalid regex pattern '${pattern}' specified:`, error);
                }

            });

        }

        return true;

    }

    enforceAttributes(input_obj) {

        $(input_obj).attr('type', this.#validations.attributes.type);

        this.setMaxLength(input_obj, this.#validations.attributes);

        this.setMin(input_obj, this.#validations.attributes);

        this.setMax(input_obj, this.#validations.attributes);

        this.setSize(input_obj, this.#validations.attributes);

        this.setPattern(input_obj, this.#validations.attributes);

        this.setRequired(input_obj, this.#validations.attributes);

        this.setDisabled(input_obj, this.#validations.attributes);

        this.setReadOnly(input_obj, this.#validations.attributes);

        this.setMultiple(input_obj, this.#validations.attributes);

    }

    setMaxLength(input_obj, attributes) {
        if (typeof attributes.max_length === 'number' && !isNaN(attributes.max_length)) {
            $(input_obj).attr('maxlength', attributes.max_length);
        }
    }

    setMin(input_obj, attributes) {
        if (typeof attributes.min === 'number' && !isNaN(attributes.min)) {
            $(input_obj).attr('min', attributes.min);
        }
    }

    setMax(input_obj, attributes) {
        if (typeof attributes.max === 'number' && !isNaN(attributes.max)) {
            $(input_obj).attr('max', attributes.max);
        }
    }

    setSize(input_obj, attributes) {
        if (typeof attributes.size === 'number' && !isNaN(attributes.size) && attributes.size > 0) {
            const SIZE_VALUE = Math.floor(attributes.size);
            $(input_obj).attr('size', SIZE_VALUE);
        }
    }

    setPattern(input_obj, attributes) {
        if (attributes.pattern && typeof attributes.pattern === 'string' && attributes.pattern.trim() !== '') {
            try {

                const regex = new RegExp(attributes.pattern);
                $(input_obj).attr('pattern', regex.source);
            } catch (error) {
                console.error(`Invalid pattern '${attributes.pattern}' specified:`, error);

            }
        }
    }

    setRequired(input_obj, attributes) {
        if (attributes.required === true) {
            $(input_obj).attr('required', true);
        }
    }

    setDisabled(input_obj, attributes) {
        if (attributes.disabled === true) {
            $(input_obj).prop('disabled', true);
        }
    }

    setReadOnly(input_obj, attributes) {
        if (attributes.read_only === true) {
            $(input_obj).prop('readonly', true);
        }
    }

    setMultiple(input_obj, attributes) {
        if (attributes.multiple === true && $(input_obj).is('select')) {
            $(input_obj).prop('multiple', true);
        } else if ($(input_obj).is('select')) {
            $(input_obj).prop('multiple', false);
        }
    }

}

const customValidation = {
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        ' +': ' '     // Replace consecutive spaces with a single space
    },
    attributes: {
        pattern: /[a-zA-Z .\-]{1,50}/,
        required: true,
        // max_length: 50,
    }
};

let position_validate = new InputValidator(customValidation);
let typingTimeout;


getAllTextEditable().on('input', function () {
    const inputElement = this;

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        try {
            if (position_validate.validate(inputElement)) {
                $(inputElement).css({
                    'outline': ''
                });
                console.log('Valid input:', inputElement);
            } else {
                $(inputElement).css({
                    'outline': '0.5px solid red'
                });

                console.log('Invalid input:', inputElement);
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 300);
});



$('#example tbody').on('click', 'tr', function (event) {


    // if ($(event.target).is('input, textarea')) {
    //     // Do nothing if the clicked element is an input text
    //     return;
    // }
    if ($(this).find('input:focus-visible').length > 0) {
        // Return early if any input or textarea is in focus
        return;
    }


    $(this).toggleClass('selected');


});

// table.on('row-reorder', function (e, diff, edit) {
//     var result = 'Reorder started on row: ' + edit.triggerRow.data()[1] + '<br>';

//     for (var i = 0, ien = diff.length; i < ien; i++) {
//         var rowData = table.row(diff[i].node).data();

//         result +=
//             rowData[1] +
//             ' updated to be in position ' +
//             diff[i].newData +
//             ' (was ' +
//             diff[i].oldData +
//             ')<br>';
//     }

//     $('#result').html('Event result:<br>' + result);
// });

const viewport = new ViewportDimensions(setMaxWidth);

viewport.listenWindowResize(setMaxWidth);

$(function () {
    $('main input[type="text"].text-editable').on('input', function () {
        const $input = $(this);
        const value = $input.val();
        const placeholderText = $input.attr('placeholder');

        // Determine the text content to measure (value or placeholder)
        const textContent = value || placeholderText;

        // Create a temporary element to measure text width
        const $tempElement = $('<span>')
            .text(textContent)
            .css({
                visibility: 'hidden',
                position: 'absolute'
            })
            .appendTo('body');

        // Get the measured width of the text content
        const textWidth = $tempElement.width() + (viewport.getViewportWidth() * 0.005);

        // Set the width of the input element based on the text width
        $input.width(textWidth);

        // Remove the temporary element
        $tempElement.remove();
    });

    // Trigger initial adjustment when the page loads
    $('main input[type="text"].text-editable').trigger('input');
});









