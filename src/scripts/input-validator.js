import setTextEditableWidth from './configuration-set-text-editable-width.js';

export default class InputValidator {
    #validations;

    constructor(validations) {
        this.#validations = validations;
    }

    validate(input_obj) {

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
                setTextEditableWidth(input_element);
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
                setTextEditableWidth(input_element);

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

        return true;

    }

    enforceAttributes(input_obj) {

        input_obj.type = this.#validations.attributes.type;

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
            input_obj.maxLength = attributes.max_length;
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
        if (attributes.pattern && typeof attributes.pattern === 'string' && attributes.pattern.trim() !== '') {
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