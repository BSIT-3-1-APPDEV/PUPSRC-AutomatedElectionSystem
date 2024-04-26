import ViewportDimensions from './viewport.js';
import InputValidator from './input-validator.js';
import setTextEditableWidth from './configuration-set-text-editable-width.js';

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
                return 0;
                // throw new Error(`No last row found in table '${table_id}'.`);
            } else {
                console.log('last row ' + LAST_ROW.outerHTML);
            }

            const LAST_SEQUENCE_SPAN = LAST_ROW.querySelector('.dt-type-numeric > span.d-none:first-child');

            if (!LAST_SEQUENCE_SPAN) {
                return 0;
                // throw new Error(`No sequence span found in last row of table '${table_id}'.`);
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
    static AddRowData(table_id) {
        try {
            let sequence = DTableUtil.FindLastSequence(table_id);
            if (sequence >= 0) {
                ++sequence
            } else if (sequence < 0) {
                sequence = 1;
            }

            const TABLE_ITEM = {
                0: sequence,
                1: {
                    data_id: '',
                    sequence: sequence,
                    value: ''
                },
                2: ''
            };

            return TABLE_ITEM;

        } catch (error) {
            console.error(`Error generating row content for table '${table_id}':`, error);
            // Optionally handle the error by returning a default value or rethrowing
            throw error;
        }
    }
}


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

        let editModalElement = document.getElementById('edit-modal');
        let editModal = new bootstrap.Modal(editModalElement);

        return editModal;

    }

    static updateModalContent(positionName, positionDescription) {

        let edit_position_modal = document.getElementById('edit-modal');
        let positionNameInput = edit_position_modal.querySelector('input[type="text"]');
        let posDescrptnInput = edit_position_modal.querySelector('textarea');

        if (positionNameInput) {
            positionNameInput.value = positionName;
        }
        if (posDescrptnInput) {
            posDescrptnInput.value = positionDescription;
        }
    }

    static showModal(EDIT_MODAL) {
        if (EDIT_MODAL) {
            EDIT_MODAL.show();
        }
    }
}

class EditPositionModal {

    static createTemplate() {
        let positionInput = document.createElement('input');
        positionInput.setAttribute('type', 'text');
        positionInput.setAttribute('id', 'positionInput');
        positionInput.classList.add('form-control');

        let positionInputLabel = document.createElement('label');
        positionInputLabel.setAttribute('for', 'positionInput');
        positionInputLabel.textContent = 'Position Name';

        let posDescrptnInput = document.createElement('textarea');
        posDescrptnInput.setAttribute('id', 'posDescrptn');
        posDescrptnInput.classList.add('form-control');

        let posDescrptnLabel = document.createElement('label');
        posDescrptnLabel.setAttribute('for', 'posDescrptn');
        posDescrptnLabel.textContent = 'Rules and Responsibilities';

        let editModalContent = document.createDocumentFragment();

        editModalContent.appendChild(positionInputLabel);
        editModalContent.appendChild(positionInput);
        editModalContent.appendChild(posDescrptnLabel);
        editModalContent.appendChild(posDescrptnInput);


        let tempContainer = document.createElement('div');
        tempContainer.appendChild(editModalContent);

        editModalContent = tempContainer.innerHTML;


        return editModalContent;
    }

}





let edit_position_modal = CandidatePosition.createModal('edit-modal',
    'modal fade',
    'modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down',
    {
        header: { className: '', innerHTML: '<h5 class="modal-title">Edit a Candidate Position</h5>' },
        body: { className: '', innerHTML: EditPositionModal.createTemplate() },
        footer: {
            className: '', innerHTML: `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>` }
    });


function handleTableRowDblClick(event) {
    const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
    if (INPUT_FOCUSED.length > 0) {

        return;
    }

    const FORM_DATA = getForm(event.currentTarget);

    console.log(FORM_DATA);

    CandidatePosition.updateModalContent(FORM_DATA[0].value, FORM_DATA[0].description);

    CandidatePosition.showModal(edit_position_modal);
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
                postData(form_data)
                    .then(function (result) {
                        const { data, success, error } = result;

                        if (success) {
                            updatePostionID(data);
                        } else {
                            console.error('POST request failed:', error);
                        }
                    })

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
const DELETE_BUTTON = document.getElementById('delete');
const DELETE_LABEL = document.getElementById('delete-label');

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
        if (DELETE_BUTTON && DELETE_LABEL) {
            handleDeleteLabel(false);
            DELETE_BUTTON.addEventListener('click', handleDeleteBtn);
        }
        TOOLBAR_BUTTON.disabled = false;
    } else {
        TOOLBAR_BUTTON.setAttribute('data-selected', '');
        if (DELETE_BUTTON && DELETE_LABEL) {
            handleDeleteLabel(true);
            DELETE_BUTTON.removeEventListener('click', handleDeleteBtn);
        }
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
                return `<input class="text-editable" type="text" name="${data.data_id}" id="text-editable-${data.sequence}" value="${data.value}" placeholder="Enter a candidate position" pattern="[a-zA-Z .\\-]{1,50}" required="" style="width: 92.885px;">`;
            }
        },

        {
            targets: 2, className: ``,
            render: function (data) {
                const DATA = `${data}` !== undefined && `${data}` !== '' ? `${data}` : '';
                return `<div class="text-truncate">${DATA}</div>`;
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
            toolbar.innerHTML = `<button class="add-new text-uppercase" id="add-new">
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

            table.row.add(DTableUtil.AddRowData('example')).draw(false);

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
    // let result = 'Reorder started on row: ' +  + '<br>';
    for (var i = 0, ien = diff.length; i < ien; i++) {
        let rowData = table.row(diff[i].node).data();
        let data_id = rowData[1].data_id;
        if (!data_id) { continue }
        let new_sequence = diff[i].newData;
        let position_input = $(diff[i].node).find('td input[type="text"].text-editable');
        let position_input_id = position_input.attr('id');

        const NEW_DATA_SEQ = {
            'input_id': position_input_id,
            'data_id': data_id,
            'sequence': new_sequence
        }

        data.update_sequence.push(NEW_DATA_SEQ);

    }

    postData(data)
        .then(function (result) {
            const { data, success, error } = result;

            if (success) {
                updatePostionID(data);
            } else {
                console.error('POST request failed:', error);
            }
        })

});



table.on('draw', function () {
    if (table.data().any()) {
        $('div.toolbar').show();
        $('table#example').show();
        $(this).parent().show();
    } else {
        $('div.toolbar').hide();
        $('table#example').hide();
        $(this).parent().hide();
    }
});



function handleDeleteLabel(isDisabled) {
    if (isDisabled) {
        DELETE_LABEL.dataset.bsToggle = 'tooltip';
        DELETE_LABEL.dataset.bsTitle = 'No items selected.';
        DELETE_LABEL.dataset.bsPlacement = 'right';
    } else {
        DELETE_LABEL.dataset.bsToggle = 'tooltip';
        DELETE_LABEL.dataset.bsTitle = '';
        DELETE_LABEL.dataset.bsPlacement = 'right';
    }

}

function handleDeleteBtn() {
    const FORM = document.querySelectorAll(`table tbody tr.selected`);

    let deleteData = {
        'delete_position': []
    };

    const DATA = getForm(FORM, false);

    for (const ITEM of DATA) {
        deleteData.delete_position.push(ITEM);
    }


    postData(deleteData)
        .then(function (result) {
            const { data, success, error } = result;
            deletePosition(data);
            if (success) {
                deletePosition(DATA);
            } else {
                console.error('POST request failed:', error);
            }
        })

    console.log(JSON.stringify(DATA));
}

function deletePosition(DATA) {
    if (DATA && DATA.data && Array.isArray(DATA.data)) {
        DATA.data.forEach(item => {
            const { data_id, input_id } = item;

            let INPUT_ELEMENT = document.getElementById(input_id);
            let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
            if (DATA_ROW) {
                DATA_ROW.remove();
            } else {

                console.error(`Input element with ID not found.`);
            }
        });
    } else {
        // console.error('Invalid or missing DATA structure.');
    }
}

function updatePostionID(DATA) {
    if (DATA && DATA.data && Array.isArray(DATA.data)) {
        DATA.data.forEach(item => {
            const { data_id, input_id } = item;

            let INPUT_ELEMENT = document.getElementById(input_id);
            if (INPUT_ELEMENT) {
                INPUT_ELEMENT.name = data_id;
            } else {

                console.error(`Input element with ID not found.`);
            }
        });
    } else {
        // console.error('Invalid or missing DATA structure.');
    }

}

function getAllTextEditable() {
    // Select all text input elements with type 'text' and class 'text-editable' within the 'main' element
    const mainElement = document.querySelector('main');
    const textEditableInputs = mainElement.querySelectorAll('input[type="text"].text-editable');

    // Convert NodeList to Array (if needed)
    // const textEditableInputsArray = Array.from(textEditableInputs);

    return textEditableInputs;
}

const customValidation = {
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        ' +': ' '     // Replace consecutive spaces with a single space
    },
    attributes: {
        type: 'text',
        pattern: /[a-zA-Z .\\-]{1,50}/,
        required: true,
        max_length: 50,
    }
};




let position_validate = new InputValidator(customValidation);
let typingTimeout;

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

function getForm(form, search = true) {
    let all_rows = [];
    if (!search) {
        all_rows = form;
    } else
        if (form && form instanceof HTMLElement) {
            // Convert form to an array if it's not already an array
            form = Array.isArray(form) ? form : [form];
            form.forEach(element => {
                let parent = element.closest(`tr`);

                if (parent) {
                    console.log('Parent <tr> found:', parent);
                    all_rows.push(parent);
                } else {
                    console.warn('Parent <tr> not found for element:', element);
                }
            });
        } else {
            // Select all <tr> elements within the main table's tbody
            all_rows = Array.from(document.querySelectorAll('table tbody tr'));
        }

    let form_data = [];

    all_rows.forEach(row => {
        let sequence_dom = row.querySelector('span.d-none');
        let input_dom = row.querySelector('input[type="text"].text-editable');
        let description_dom = row.querySelector('.text-truncate');

        if (sequence_dom && input_dom) {
            let input_id = input_dom.id;
            let data_sequence = sequence_dom.textContent.trim();
            let data_val = (input_dom.value.trim() || '');
            let data_id = input_dom.name || '';
            let data_description = description_dom.textContent || '';

            let row_data = {
                'input_id': input_id,
                'data_id': data_id,
                'sequence': data_sequence,
                'value': data_val,
                'description': data_description,
            };

            form_data.push(row_data);
        }
    });

    console.log(form_data);
    return form_data;
}



function postData(post_data) {
    let url = 'src/includes/classes/candidate-pos-controller.php';
    let method = 'PUT';
    let json_data = JSON.stringify(post_data);
    console.log(json_data);
    if ('update_sequence' in post_data) {
        method = 'UPDATE';
    } else if ('delete_position' in post_data) {
        method = 'DELETE';
    }
    return fetch(url, {
        method: method,
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
            return { data, success: true };
        })
        .catch(function (error) {
            console.error('POST request error:', error);
            return { error, success: false };
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