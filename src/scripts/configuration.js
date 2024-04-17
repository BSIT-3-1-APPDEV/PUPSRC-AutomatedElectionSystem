import ViewportDimensions from './viewport.js';

/**
 * Utility class for Datatable.
 */
class DTableUtil {
    /**
     * Finds the last sequence number from a specified HTML table.
     * @param {string} table_id - The ID of the HTML table to search.
     * @returns {number} The last sequence number found in the table.
     * @throws {Error} If the table, last row, or sequence span is not found, or if the sequence number is invalid.
     */
    static FindLastSequence(table_id) {
        try {
            const TABLE = document.querySelector(`table#${table_id}`);

            if (!TABLE) {
                throw new Error(`Table with id '${table_id}' not found.`);
            }

            const LAST_ROW = TABLE.querySelector('tbody tr:last-child');

            if (!LAST_ROW) {
                throw new Error(`No last row found in table '${table_id}'.`);
            }

            const LAST_SEQUENCE_SPAN = LAST_ROW.querySelector('.dt-type-numeric > span.d-none:first-child');

            if (!LAST_SEQUENCE_SPAN) {
                throw new Error(`No sequence span found in last row of table '${table_id}'.`);
            }

            let last_sequence = LAST_SEQUENCE_SPAN.textContent.trim();
            last_sequence = parseInt(last_sequence);

            if (isNaN(last_sequence)) {
                throw new Error(`Invalid sequence number found in table '${table_id}'.`);
            }

            return last_sequence;
        } catch (error) {
            console.error(`Error finding last sequence for table '${table_id}':`, error);
            // Optionally handle the error by returning a default sequence or rethrowing
            throw error; // Rethrow the error to propagate it to the caller
        }
    }

    /**
     * Adds a new row to the specified HTML table using a template of <td> elements.
     * @param {string} table_id - The ID of the HTML table to which the row will be added.
     * @param {Object} td_template - An object containing template definitions for <td> elements.
     * @returns {Array<string>} An array of strings representing the outerHTML of the generated <td> elements.
     * @throws {Error} If there is an error generating the row content.
     */
    static AddRowData(table_id, td_template) {
        try {
            let sequence = DTableUtil.FindLastSequence(table_id);
            ++sequence;

            let td_elements = [];

            for (const key in td_template) {
                if (Object.prototype.hasOwnProperty.call(td_template, key)) {
                    // Destructure td template
                    const { className, innerHTML } = td_template[key];

                    // Replace placeholders in innerHTML with last sequence number of table body row
                    const PROCESSED_TD = innerHTML.replace(/\${sequence}/g, sequence);

                    // Create new <td> element with the processed values
                    const TD_ELEMENT = document.createElement('td');
                    TD_ELEMENT.className = className;
                    TD_ELEMENT.innerHTML = PROCESSED_TD;

                    // Push the outerHTML of the <td> element into the array
                    td_elements.push(TD_ELEMENT.outerHTML);
                }
            }

            return td_elements;
        } catch (error) {
            console.error(`Error generating row content for table '${table_id}':`, error);
            // Optionally handle the error by returning a default value or rethrowing
            throw error;
        }
    }
}



const CANDIDATE_POS_ROW = {
    td1: {
        className: 'dt-type-numeric text-center col-1 grab sorting_1',
        innerHTML: `
            <span class="d-none">\${sequence}</span>
            <span class="fas fa-grip-lines"></span>
        `
    },
    td2: {
        className: 'text-left text-editable',
        innerHTML: `
            <input class="text-editable" type="text" name="" id="\${sequence}" value="" placeholder="Enter a candidate position" pattern="[a-zA-Z .\\-]{1,50}" required>
        `
    }
};



/**
 * Utility class for managing candidate position table.
 */
class CandidatePosition {
    /**
     * Adds event listeners to enable interactive features on a candidate position table.
     * @param {string} table_id - The ID of the HTML table to which event listeners will be added.
     */
    static addTableListener(table_id) {
        /**
         * Retrieves all text editable input elements within the specified table.
         * @returns {NodeListOf<HTMLInputElement>} A list of text editable input elements.
         */
        const ALL_TEXT_EDITABLE = getAllTextEditable();

        /**
         * Retrieves all table rows within the specified table.
         * @type {NodeListOf<HTMLTableRowElement>}
         */
        const TABLE_ROW = document.querySelectorAll(`#${table_id} tbody tr`);

        // Removes existing input listener for input[type="text"].text-editable
        ALL_TEXT_EDITABLE.forEach(inputElement => {
            inputElement.removeEventListener('input', handleInput);
        });
        // Adds input listener for input[type="text"].text-editable
        ALL_TEXT_EDITABLE.forEach(inputElement => {
            inputElement.addEventListener('input', handleInput);
        });
        // Removes existing click listener for #${table_id} tbody tr
        TABLE_ROW.forEach(row => {
            row.removeEventListener('click', handleTableRowClick);
        });
        // Adds click listener for #${table_id} tbody tr
        TABLE_ROW.forEach(row => {
            row.addEventListener('click', handleTableRowClick);
        });

        TABLE_ROW.forEach(row => {
            row.removeEventListener('dblclick', handleTableRowDblClick);
        });
        // Adds click listener for #${table_id} tbody tr
        TABLE_ROW.forEach(row => {
            row.addEventListener('dblclick', handleTableRowDblClick);
        });
    }

    static createModal(modalId, modalClass, modalDialogClass, modalParts) {
        // Get the modal element
        let body = document.getElementsByTagName('body')[0];

        // Create modal dialog div
        let modal = document.createElement('div');
        modal.className = modalClass;

        // Create modal dialog div
        let modalDialog = document.createElement('div');
        modalDialog.className = modalDialogClass;

        // Create modal content div
        let modalContent = document.createElement('div');
        modalContent.className = 'modal-content';

        // Create and append modal parts (header, body, footer)
        ['header', 'body', 'footer'].forEach(function (part) {
            if (modalParts[part]) {
                let modalPart = document.createElement('div');
                modalPart.className = 'modal-' + part + ' ' + modalParts[part].className;
                modalPart.innerHTML = modalParts[part].innerHTML;
                modalContent.appendChild(modalPart);
            }
        });

        // Append all elements
        modalDialog.appendChild(modalContent);
        modal.appendChild(modalDialog);

        modal.id = modalId;
        body.appendChild(modal);

        var editModalElement = document.getElementById('edit-modal');
        var editModal = new bootstrap.Modal(editModalElement);

        return editModal;

    }

    static showModal(EDIT_MODAL) {
        if (EDIT_MODAL) {
            EDIT_MODAL.show();
        }
    }
}



let positionNameInput = document.createElement('input');
positionNameInput.setAttribute('type', 'text');

let posDescrptnInput = document.createElement('textarea');

let editModalContent = document.createDocumentFragment();

editModalContent.appendChild(positionNameInput);
editModalContent.appendChild(posDescrptnInput);

let tempContainer = document.createElement('div');
tempContainer.appendChild(editModalContent);

editModalContent = tempContainer.innerHTML;



const EDIT_MODAL = CandidatePosition.createModal('edit-modal',
    'modal fade',
    'modal-dialog modal-dialog-centered modal-fullscreen-sm-down',
    {
        header: { className: '', innerHTML: '<h5 class="modal-title">Edit a Candidate Position</h5>' },
        body: { className: '', innerHTML: editModalContent },
        footer: { className: '', innerHTML: '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' }
    });


function handleTableRowDblClick(event) {
    const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
    if (INPUT_FOCUSED.length > 0) {
        return;
    }
    console.log('dbl');
    CandidatePosition.showModal(EDIT_MODAL);
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
    const TABLE_BODY = document.querySelector(`#example tbody`);
    const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
    if (INPUT_FOCUSED.length > 0) {
        return;
    }
    console.log('click' + event.currentTarget);
    event.currentTarget.classList.toggle('selected');
    const SELECTED_ROWS = TABLE_BODY.querySelectorAll('tr.selected');
    const SELECTED_COUNT = SELECTED_ROWS.length;

    const TOOLBAR_BUTTON = document.querySelector('div.toolbar button');
    if (SELECTED_COUNT > 0) {
        TOOLBAR_BUTTON.setAttribute('data-selected', SELECTED_COUNT);
        TOOLBAR_BUTTON.disabled = false;
    } else {
        TOOLBAR_BUTTON.setAttribute('data-selected', '');
        TOOLBAR_BUTTON.disabled = true;
    }
}


let table = new DataTable('#example', {
    rowReorder: true,
    columnDefs: [
        {
            targets: 0, className: `text-center col-1 grab`,
            render: function (data) {
                return `<span class="d-none">${data}</span>
            <span class="fas fa-grip-lines"></span>`;
            }
        },
        {
            targets: 1, className: `text-left text-editable`,
            render: function (data) {
                return `<input class="text-editable" type="text" name="${data.data_id}" id="${data.sequence}" value="${data.value}" placeholder="Enter a candidate position" pattern="[a-zA-Z .\-]{1,50}" required="" style="width: 92.885px;">`;
            }
        },

        {
            targets: 2, className: ``,
            render: function (data) {
                return `<div class="text-truncate">${data}</div>`;
            }
        }
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

            table.row.add(DTableUtil.AddRowData('example', CANDIDATE_POS_ROW)).draw(false);

            CandidatePosition.addTableListener('example');

            const dtScrollBody = document.querySelector('.dt-scroll-body');
            dtScrollBody.scrollTop = dtScrollBody.scrollHeight;
        });

    }
});

table.on('row-reorder', function (e, diff, edit) {
    let data = {
        'update_sequence': []
    };

    for (var i = 0, ien = diff.length; i < ien; i++) {
        let rowData = table.row(diff[i].node).data();
        let data_id = rowData[1].data_id;
        let new_sequence = diff[i].newData;
        const NEW_DATA_SEQ = {
            'data_id': data_id,
            'sequence': new_sequence
        }

        data.update_sequence.push(NEW_DATA_SEQ);

    }

    postData(data);
});

table.on('row-reorder-changed', function (e, { insertPlacement, insertPoint }) {

    // console.log('Row moved ' + insertPlacement + ' the ' + insertPoint + '. row');
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
                setTextEditableWidth(input_element);

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

    const dtScrollBody = document.querySelector('div.dt-container > div:nth-child(1) > div > div > div.dt-scroll-body');

    if (dtScrollBody) {
        // dtScrollBody.style.maxHeight = getUsableContent();
    }


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
        let description_dom = row.querySelector('.text-truncate');

        if (sequence_dom && input_dom) {
            let input_id = input_dom.id;
            let data_sequence = sequence_dom.textContent.trim();
            let data_val = input_dom.value.trim();
            let data_id = input_dom.name;
            let data_description = description_dom.textContent;

            let row_data = {
                'input_id': input_id,
                'data_id': data_id,
                'sequence': data_sequence,
                'value': data_val,
                'description': data_description

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

fetchData();

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
            const TABLE_DATA = processData(data);
            insertData(TABLE_DATA, table);
            console.log('GET request successful:', data);
        })
        .catch(function (error) {
            console.error('GET request error:', error);
        });
}

function processData(data) {
    const TABLE_DATA = [];

    data.forEach(item => {
        const tableItem = {
            0: item.sequence,
            1: {
                data_id: item.data_id,
                sequence: item.sequence,
                value: item.value
            },
            2: item.description
        };

        TABLE_DATA.push(tableItem);
    });

    console.log('Processed TABLE_DATA:', TABLE_DATA);
    return TABLE_DATA;
}


function insertData(TABLE_DATA, TABLE) {

    console.log('To be inserted TABLE_DATA:', TABLE_DATA);
    TABLE.clear();
    TABLE.rows.add(TABLE_DATA);
    TABLE.draw();
    CandidatePosition.addTableListener('example');
}


function getUsableContent() {
    // Select all the specified elements
    const ulElement = document.querySelector('body > main > div > div > div > div > div:nth-child(2) > div > ul.nav');
    const h4Element = document.querySelector('body > main > div > div > div > div > div.d-flex');
    const navbarElement = document.querySelector('body > nav.navbar');
    const addElement = document.querySelector('#add-new');
    const footerElement = document.querySelector('body > footer');
    const toolbarElement = document.querySelector('body > main > div > div > div > div > div.toolbar');

    // Calculate the heights of each element
    const ulHeight = ulElement ? ulElement.getBoundingClientRect().height : 0;
    const h4Height = h4Element ? h4Element.getBoundingClientRect().height : 0;
    const navbarHeight = navbarElement ? navbarElement.getBoundingClientRect().height : 0;
    const addNewHeight = addElement ? addElement.getBoundingClientRect().height : 0;
    const footerHeight = footerElement ? footerElement.getBoundingClientRect().height : 0;
    const toolbarHeight = toolbarElement ? toolbarElement.getBoundingClientRect().height : 0;

    console.log('ulHeight:', ulHeight);
    console.log('h4Height:', h4Height);
    console.log('navbarHeight:', navbarHeight);
    console.log('addNewHeight:', addNewHeight);
    console.log('footerHeight:', footerHeight);
    console.log('toolbarHeight:', toolbarHeight);

    // Calculate the total height of all specified elements
    const totalElementsHeight = ulHeight + h4Height + navbarHeight + footerHeight + toolbarHeight + addNewHeight;

    // Calculate 100vh minus the total height of specified elements
    const remainingHeight = window.innerHeight - totalElementsHeight;

    // Output the remaining height
    return remainingHeight;

}


const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))










