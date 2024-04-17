import ViewportDimensions from './viewport.js';

class DatatableRowOrder {
    static searchLastRow(table_id) {
        const table = document.querySelector(`table#${table_id}`);
        const lastRow = table.querySelector('tbody tr:last-child');
        const lastSequenceSpan = lastRow.querySelector('.dt-type-numeric > span.d-none:first-child');
        let last_sequence = lastSequenceSpan.textContent.trim();
        last_sequence = parseInt(last_sequence);
        return last_sequence;
    }

    static addRow(table_id) {
        let sequence = DatatableRowOrder.searchLastRow(table_id);
        ++sequence;
        const td1 = document.createElement('td');
        td1.className = 'dt-type-numeric text-center col-1 grab sorting_1';
        td1.innerHTML = `
            <span class="d-none">${sequence}</span>
            <span class="fas fa-grip-lines"></span>
        `;

        const td2 = document.createElement('td');
        td2.className = 'text-left text-editable';
        td2.innerHTML = `
            <input class="text-editable" type="text" name="" id="${sequence}"  value="" placeholder="Enter a candidate position" pattern="[a-zA-Z .\\-]{1,50}" required>
        `;

        const newRow = document.createElement('tr');
        newRow.appendChild(td1);
        newRow.appendChild(td2);

        const tableBody = document.querySelector(`table#${table_id} tbody`);
        tableBody.appendChild(newRow);

        return [td1.outerHTML, td2.outerHTML];
    }
}


class CandidatePosition {
    static addTableListener(table_id) {
        const allTextEditable = getAllTextEditable();
        const tableBody = document.querySelector(`#${table_id} tbody`);
        const table_row = document.querySelectorAll(`#${table_id} tbody tr`);

        allTextEditable.forEach(inputElement => {
            inputElement.removeEventListener('input', handleInput);
        });

        allTextEditable.forEach(inputElement => {
            inputElement.addEventListener('input', handleInput);
        });

        table_row.forEach(row => {
            row.removeEventListener('click', handleTableRowClick);
        });

        table_row.forEach(row => {
            row.addEventListener('click', handleTableRowClick);
        });


    }
}

function handleInput(event) {
    setTextEditableWidth(event.target);

    const inputElement = event.target;

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        try {
            console.log('typed');
            if (position_validate.validate(inputElement)) {
                inputElement.style.outline = '';
                let form_data = getForm(inputElement);
                postData(form_data);
                console.log('Valid input:', inputElement);
            } else {
                inputElement.style.outline = '0.5px solid red';
                console.log('Invalid input:', inputElement);
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 300);
}

function handleTableRowClick(event) {
    const tableBody = document.querySelector(`#example tbody`);
    const inputsInFocus = event.currentTarget.querySelectorAll('input:focus-visible');
    if (inputsInFocus.length > 0) {
        return;
    }
    console.log('click' + event.currentTarget);
    event.currentTarget.classList.toggle('selected');
    const selectedRows = tableBody.querySelectorAll('tr.selected');
    const selectedCount = selectedRows.length;

    const toolbarButton = document.querySelector('div.toolbar button');
    if (selectedCount > 0) {
        toolbarButton.setAttribute('data-selected', selectedCount);
        toolbarButton.disabled = false;
    } else {
        toolbarButton.setAttribute('data-selected', '');
        toolbarButton.disabled = true;
    }
}

let table = new DataTable('#example', {
    rowReorder: true,
    columnDefs: [
        { targets: 0, className: `text-center col-1 grab` },
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
        topEnd: null,
        bottom: function () {
            let toolbar = document.createElement('div');
            toolbar.innerHTML = `<button class="add-new" id="add-new">
                                    Add New Position
                                </button>`;

            return toolbar;
        }
    },
    scrollY: '45dvh ',
    scrollCollapse: true,
    paging: false,
    initComplete: function (settings, json) {
        const textEditableElements = getAllTextEditable();
        textEditableElements.forEach(function (element) {
            setTextEditableWidth(element);
        });

        CandidatePosition.addTableListener('example');

        document.getElementById('add-new').addEventListener('click', function () {

            table.row.add(DatatableRowOrder.addRow('example')).draw(false);

            CandidatePosition.addTableListener('example');

            const dtScrollBody = document.querySelector('.dt-scroll-body');
            dtScrollBody.scrollTop = dtScrollBody.scrollHeight;
        });

    }
});

let text_editable_input;

function getAllTextEditable() {
    // Select all text input elements with type 'text' and class 'text-editable' within the 'main' element
    const mainElement = document.querySelector('main');
    const textEditableInputs = mainElement.querySelectorAll('input[type="text"].text-editable');

    // Convert NodeList to Array (if needed)
    // const textEditableInputsArray = Array.from(textEditableInputs);

    return textEditableInputs;
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

        let input_element = input_obj;
        let original_value = input_element.value;
        let trimmed_value = original_value;
        console.log('orig val ' + original_value);

        this.enforceAttributes(input_element);

        if (input_element.validity.typeMismatch) {
            console.log('type');
            return false;
        }


        if (this.#validations.attributes.required) {
            if (input_element.validity.valueMissing || original_value.trim() === '') {
                input_element.value = original_value.trim();
                setTextEditableWidth(input_element);
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

                input_element.value = trimmed_value;


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
                        input_element.value = trimmed_value;
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

const customValidation = {
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        ' +': ' '     // Replace consecutive spaces with a single space
    },
    attributes: {
        type: 'text',
        pattern: /[a-zA-Z .\-]{1,50}/,
        required: true,
        max_length: 50,
    }
};

let position_validate = new InputValidator(customValidation);
let typingTimeout;


function setTextEditableWidth(input_element) {
    const input = input_element;
    const value = input.value;
    const placeholderText = input.getAttribute('placeholder');

    // Determine the text content to measure (value or placeholder)
    const textContent = value || placeholderText;

    // Create a temporary span element to measure text width
    const tempElement = document.createElement('span');
    tempElement.textContent = textContent;
    tempElement.style.visibility = 'hidden';
    tempElement.style.position = 'absolute';

    // Copy font styles from input element to the temporary element
    tempElement.style.fontSize = window.getComputedStyle(input).fontSize;
    tempElement.style.fontFamily = window.getComputedStyle(input).fontFamily;
    tempElement.style.fontWeight = window.getComputedStyle(input).fontWeight;
    tempElement.style.fontStyle = window.getComputedStyle(input).fontStyle;
    tempElement.style.letterSpacing = window.getComputedStyle(input).letterSpacing;
    tempElement.style.textTransform = window.getComputedStyle(input).textTransform;

    // Append the temporary element to the document body to measure its width
    document.body.appendChild(tempElement);

    // Get the measured width of the text content
    const textWidth = tempElement.offsetWidth + (ViewportDimensions.getViewportWidth() * 0.005);

    // Set the width of the input element based on the text width
    input.style.width = textWidth + 'px';

    // Remove the temporary element from the document body
    document.body.removeChild(tempElement);
}


function setMainContainerPos(transition_off = false) {
    console.log('change ');

    const navBar = document.querySelector('nav.navbar');
    const footerBar = document.querySelector('footer.navbar');

    if (navBar && footerBar) {
        const NAV_HEIGHT = navBar.getBoundingClientRect().height;
        const FOOTER_HEIGHT = footerBar.getBoundingClientRect().height;
        const MAIN_HEIGHT = `calc(100vh - ${NAV_HEIGHT + FOOTER_HEIGHT}px)`;

        const mainElement = document.querySelector('main');

        if (mainElement) {
            mainElement.style.top = NAV_HEIGHT + 'px';
            mainElement.style.height = MAIN_HEIGHT;
        }
    }
}


ViewportDimensions.listenWindowResize(() => {
    setMainContainerPos();
    let textEditableInputs = document.querySelectorAll('main input[type="text"].text-editable');

    textEditableInputs.forEach(input => {
        setTextEditableWidth(input);
    });
});

function getForm(form) {
    let all_rows = [];

    if (form && form instanceof HTMLElement) {
        // Convert form to an array if it's not already an array
        form = Array.isArray(form) ? form : [form];

        form.forEach(element => {
            let parent = element.closest('tr');

            if (parent) {
                console.log('Parent <tr> found:', parent);
                all_rows.push(parent);
            } else {
                console.warn('Parent <tr> not found for element:', element);
            }
        });
    } else {
        // Select all <tr> elements within the main table's tbody
        all_rows = Array.from(document.querySelectorAll('main table tbody tr'));
    }

    let form_data = [];

    all_rows.forEach(row => {
        let sequence_dom = row.querySelector('span.d-none');
        let input_dom = row.querySelector('input[type="text"].text-editable');

        if (sequence_dom && input_dom) {
            let input_id = input_dom.id;
            let data_sequence = sequence_dom.textContent.trim();
            let data_val = input_dom.value.trim();
            let data_id = input_dom.name;

            let row_data = {
                'input_id': input_id,
                'data_id': data_id,
                'sequence': data_sequence,
                'value': data_val

            };

            form_data.push(row_data);
        }
    });

    console.log(form_data);
    return form_data;
}



function postData(post_data) {
    let url = 'src/includes/classes/candidate-pos-controller.php';
    let json_data = JSON.stringify(post_data);

    fetch(url, {
        method: 'POST',
        body: json_data,
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function (data) {
            console.log('POST request successful:', data);
        })
        .catch(function (error) {
            console.error('POST request error:', error);
        });


}

function fetchData() {
    var url = 'src/includes/classes/candidate-pos-controller.php';

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
}












