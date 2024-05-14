import setTextEditableWidth from './configuration-set-text-editable-width.js';

/**
 * A utility class for validating input based on constructed criteria.
 * 
 * Has @function validate which returns true in invalid input.
 * @class
 */
export default class InputValidator {
    #validations;

    /**
     * @typedef {Object} ValidationCriteria
     * @property {Object} attributes - Attributes for input validation.
     * @property {string} attributes.type - The type of input element.
     * @property {number} [attributes.max_length] - The maximum length of the input.
     * @property {number} [attributes.min] - The minimum value allowed for numeric input.
     * @property {number} [attributes.max] - The maximum value allowed for numeric input.
     * @property {number} [attributes.size] - The size attribute for input elements.
     * @property {string} [attributes.pattern] - The pattern for input validation.
     * @property {boolean} [attributes.required] - Whether the input is required.
     * @property {boolean} [attributes.disabled] - Whether the input is disabled.
     * @property {boolean} [attributes.read_only] - Whether the input is read-only.
     * @property {boolean} [attributes.multiple] - Whether the input allows multiple selections (for select elements).
     * @property {Object.<string, string>} [trailing] - Trailing operations to be applied to the input value.
     */

    /**
    * Constructs an InputValidator object with the specified validation criteria.
    * @param {ValidationCriteria} validations - Validation criteria for input elements.
    */
    constructor(validations) {
        this.#validations = validations;
    }

    /**
     * Validates the input element based on the specified criteria.
     * @param {Element} input_obj - The input element to validate.
     * @param {Function} [callback] - Optional callback function to execute after validation.
     * @returns {boolean} - Returns true if the input is valid, otherwise false.
     * @throws {Error} - Throws an error if the provided input element is invalid.
     */
    validate(input_obj, callback) {

        if (!(input_obj instanceof Element)) {
            throw new Error('Invalid input element provided.');
        }

        let input_element = input_obj;
        let original_value = input_element.value;
        let trimmed_value = original_value;
        // console.log('orig val ' + original_value);

        this.enforceAttributes(input_element);

        if (input_element.validity.typeMismatch) {
            return false;
        }


        if (this.#validations.attributes.required) {
            if (input_element.validity.valueMissing || original_value.trim() === '') {
                input_element.value = original_value.trim();
                if (typeof callback === 'function') {
                    callback(input_element);
                }
                return false;
            }
        }

        if (this.#validations.attributes.pattern) {
            let trimmed_value = '';
            const regex = new RegExp(this.#validations.attributes.pattern);
            if (input_element.validity.patternMismatch) {
                for (let i = 0; i < original_value.length; i++) {
                    const char = original_value[i];
                    if (regex.test(char)) {
                        trimmed_value += char;
                    }
                }

                input_element.value = trimmed_value;
                if (typeof callback === 'function') {
                    callback(input_element);
                }

                return false;
            }
        }


        if (this.#validations.attributes.max_length && input_element.validity.tooLong) {
            trimmed_value = trimmed_value.slice(0, this.#validations.attributes.max_length);
            return false;
        }

        if (this.#validations.attributes.min && input_element.validity.rangeUnderflow) {
            return false;
        }

        if (this.#validations.attributes.max && input_element.validity.rangeOverflow) {
            trimmed_value = trimmed_value.slice(0, validations.max);
            return false;
        }

        if (this.#validations.trailing && Object.keys(this.#validations.trailing).length > 0) {
            Object.keys(this.#validations.trailing).forEach(pattern => {
                let replacementValue = this.#validations.trailing[pattern];
                try {
                    const regex = new RegExp(pattern, 'g');
                    trimmed_value = trimmed_value.replace(regex, replacementValue);

                    if (original_value !== trimmed_value) {
                        input_element.value = trimmed_value;

                        return false;
                    }
                } catch (error) {
                    console.error(`Invalid regex pattern '${pattern}' specified:`, error);
                }

            });

        }

        return input_element.checkValidity();

    }

    enforceAttributes(input_obj) {

        input_obj.type = this.#validations.attributes.type;

        this.setMaxLength(input_obj, this.#validations.attributes);

        this.setMinLength(input_obj, this.#validations.attributes);

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
            input_obj.maxLength = attributes.max_length;
        }
    }

    /**
     * Sets the minimum length attribute on the input element.
     * @param {Element} input_obj - The input element to set the minimum length attribute on.
     * @param {Object} attributes - Attributes containing the minimum length value.
     */
    setMinLength(input_obj, attributes) {
        if (typeof attributes.min_length === 'number' && !isNaN(attributes.min_length)) {
            input_obj.minLength = attributes.min_length;
        }
    }


    setMin(input_obj, attributes) {
        if (typeof attributes.min === 'number' && !isNaN(attributes.min)) {
            input_obj.min = attributes.min;
        }
    }

    setMax(input_obj, attributes) {
        if (typeof attributes.max === 'number' && !isNaN(attributes.max)) {
            input_obj.max = attributes.max;
        }
    }

    setSize(input_obj, attributes) {
        if (typeof attributes.size === 'number' && !isNaN(attributes.size) && attributes.size > 0) {
            const SIZE_VALUE = Math.floor(attributes.size);
            input_obj.size = SIZE_VALUE;
        }
    }

    setPattern(input_obj, attributes) {
        if (attributes.pattern.trim() !== '') {
            try {

                const regex = new RegExp(attributes.pattern);
                input_obj.pattern = regex.source;
            } catch (error) {
                console.error(`Invalid pattern '${attributes.pattern}' specified:`, error);

            }
        }
    }

    setRequired(input_obj, attributes) {
        if (attributes.required === true) {
            input_obj.required = true;
        }
    }

    setDisabled(input_obj, attributes) {
        if (attributes.disabled === true) {
            input_obj.disabled = true;
        }
    }

    setReadOnly(input_obj, attributes) {
        if (attributes.read_only === true) {
            input_obj.readOnly = true;
        }
    }

    setMultiple(input_obj, attributes) {
        if (attributes.multiple === true && input_obj.tagName === 'SELECT') {
            input_obj.multiple = true;
        } else if (input_obj.tagName === 'SELECT') {
            input_obj.multiple = false;
        }
    }

}