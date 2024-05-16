import { initializeConfigurationJS as ConfigJS } from './configuration.js';
import ViewportDimensions from './viewport.js';
import InputValidator from './input-validator.js';
import setTextEditableWidth from './configuration-set-text-editable-width.js';

import { Debugout } from '../../vendor/node_modules/debugout.js/dist/debugout.min.js';

const logToFile = new Debugout({ realTimeLoggingOn: true, useTimestamps: true });

var ConfigPage = {};

ConfigPage = {
    configJs: function () {
        ConfigJS();
    }
}

ConfigPage.configJs();

// (function () {


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


        // Add event listeners for touch events
        TABLE_ROW.forEach(row => {
            onLongPress(row, (event) => {
                handleTableRowLongPress(event);
            });

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

        let editModalElement = document.getElementById(POSITION_MODAL_ID);
        let editModal = new bootstrap.Modal(editModalElement);

        return editModal;

    }

    static updateModalContent(DATA, quill, isAdd = false) {

        let edit_position_modal = document.getElementById(POSITION_MODAL_ID);
        let positionNameInput = edit_position_modal.querySelector('input[type="text"]');

        if (isAdd) {
            edit_position_modal.querySelector('.modal-title').textContent = 'Add New Position';
        } else {

            edit_position_modal.querySelector('.modal-title').textContent = 'Edit a Candidate Position';

        }

        if (positionNameInput) {
            positionNameInput.setAttribute('data-data-id', DATA[0].data_id);
            positionNameInput.setAttribute('data-target-input', DATA[0].input_id);
            positionNameInput.setAttribute('data-sequence', DATA[0].sequence);
            positionNameInput.setAttribute('data-initial', DATA[0].value);
            positionNameInput.value = DATA[0].value ?? '';
        }
        console.log("Text editor " + JSON.stringify(DATA[0].description));
        try {
            if (quill) {
                let description = (DATA[0].description !== undefined && DATA[0].description !== '') ? JSON.parse(DATA[0].description) : '';
                logToFile.log(`is ADD ${isAdd} dta `, description, ' stringtified ', JSON.stringify(description));
                // logToFile.downloadLog();
                quill.setContents(description);

            }
        } catch (error) {
            logToFile.log(`is ADD ${isAdd} error ${error} dta `, DATA[0].description, ' stringtified ', JSON.stringify(DATA[0].description));
            // logToFile.downloadLog();
        }
    }

    static showModal(EDIT_MODAL) {
        if (EDIT_MODAL) {


            EDIT_MODAL.show();
        }
    }
}

const POSITION_MODAL_ID = 'edit-modal';
let longPressTimer;
let longPressHandlers = new Map();

function touchStartHandler(callback) {
    return (event) => {
        longPressTimer = setTimeout(() => {
            longPressTimer = null;
            callback(event);
        }, 600);
    };
}

const cancelTouch = () => {
    clearTimeout(longPressTimer);
};

function onLongPress(element, callback) {
    // If there are existing longPressHandlers, remove them
    if (longPressHandlers.has(element)) {
        const { touchStart, cancelTouch } = longPressHandlers.get(element);
        element.removeEventListener('touchstart', touchStart);
        element.removeEventListener('touchend', cancelTouch);
        element.removeEventListener('touchmove', cancelTouch);
    }

    // Create new longPressHandlers
    const touchStart = touchStartHandler(callback);
    longPressHandlers.set(element, { touchStart, cancelTouch: cancelTouch });

    // Add new longPressHandlers
    element.addEventListener('touchstart', touchStart);
    element.addEventListener('touchend', cancelTouch);
    element.addEventListener('touchmove', cancelTouch);
}




class EditPositionModal {

    static createTemplate() {
        let positionInput = document.createElement('input');
        positionInput.setAttribute('type', 'text');
        positionInput.setAttribute('id', 'positionInput');
        positionInput.setAttribute('data-data-id', '');
        positionInput.setAttribute('data-target-input', '');
        positionInput.setAttribute('data-sequence', '');
        positionInput.setAttribute('data-initial', '');
        positionInput.classList.add('form-control', 'mb-4');
        positionInput.setAttribute('placeholder', 'Enter a candidate position');
        positionInput.setAttribute('pattern', '[a-zA-Z .\\-]{1,50}');
        positionInput.setAttribute('required', '');

        let positionInputLabel = document.createElement('label');
        positionInputLabel.setAttribute('for', 'positionInput');
        positionInputLabel.textContent = 'Position Name';
        positionInputLabel.classList.add('mb-2');

        let richTextToolbar = document.createElement('div');
        richTextToolbar.setAttribute('id', 'rich-txt-toolbar');
        richTextToolbar.classList.add('ql-toolbar', 'ql-snow');

        // Create the bold button
        let boldButton = document.createElement('button');
        boldButton.setAttribute('type', 'button');
        boldButton.classList.add('ql-bold');
        boldButton.innerHTML = '<svg viewBox="0 0 18 18"><path class="ql-stroke" d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5v0A2.5,2.5,0,0,1,9.5,9H5A0,0,0,0,1,5,9V4A0,0,0,0,1,5,4Z"></path><path class="ql-stroke" d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5v0A2.5,2.5,0,0,1,10.5,14H5a0,0,0,0,1,0,0V9A0,0,0,0,1,5,9Z"></path></svg>';

        // Create the italic button
        let italicButton = document.createElement('button');
        italicButton.setAttribute('type', 'button');
        italicButton.classList.add('ql-italic');
        italicButton.innerHTML = '<svg viewBox="0 0 18 18"><line class="ql-stroke" x1="7" x2="13" y1="4" y2="4"></line><line class="ql-stroke" x1="5" x2="11" y1="14" y2="14"></line><line class="ql-stroke" x1="8" x2="10" y1="14" y2="4"></line></svg>';

        let underlineButton = document.createElement('button');
        underlineButton.setAttribute('type', 'button');
        underlineButton.classList.add('ql-underline');
        underlineButton.innerHTML = '<svg viewBox="0 0 18 18"><path class="ql-stroke" d="M5,3V9a4.012,4.012,0,0,0,4,4H9a4.012,4.012,0,0,0,4-4V3"></path><rect class="ql-fill" height="1" rx="0.5" ry="0.5" width="12" x="3" y="15"></rect></svg>';

        let bulletButton = document.createElement('button');
        bulletButton.setAttribute('type', 'button');
        bulletButton.setAttribute('value', 'bullet');
        bulletButton.classList.add('ql-list');
        bulletButton.innerHTML = '<svg viewBox="0 0 18 18"><line class="ql-stroke" x1="6" x2="15" y1="4" y2="4"></line><line class="ql-stroke" x1="6" x2="15" y1="9" y2="9"></line><line class="ql-stroke" x1="6" x2="15" y1="14" y2="14"></line><line class="ql-stroke" x1="3" x2="3" y1="4" y2="4"></line><line class="ql-stroke" x1="3" x2="3" y1="9" y2="9"></line><line class="ql-stroke" x1="3" x2="3" y1="14" y2="14"></line></svg>';


        let orderedButton = document.createElement('button');
        orderedButton.setAttribute('type', 'button');
        orderedButton.setAttribute('value', 'ordered');
        orderedButton.classList.add('ql-list');
        orderedButton.innerHTML = '<svg viewBox="0 0 18 18"><line class="ql-stroke" x1="7" x2="15" y1="4" y2="4"></line><line class="ql-stroke" x1="7" x2="15" y1="9" y2="9"></line><line class="ql-stroke" x1="7" x2="15" y1="14" y2="14"></line><line class="ql-stroke ql-thin" x1="2.5" x2="4.5" y1="5.5" y2="5.5"></line><path class="ql-fill" d="M3.5,6A0.5,0.5,0,0,1,3,5.5V3.085l-0.276.138A0.5,0.5,0,0,1,2.053,3c-0.124-.247-0.023-0.324.224-0.447l1-.5A0.5,0.5,0,0,1,4,2.5v3A0.5,0.5,0,0,1,3.5,6Z"></path><path class="ql-stroke ql-thin" d="M4.5,10.5h-2c0-.234,1.85-1.076,1.85-2.234A0.959,0.959,0,0,0,2.5,8.156"></path><path class="ql-stroke ql-thin" d="M2.5,14.846a0.959,0.959,0,0,0,1.85-.109A0.7,0.7,0,0,0,3.75,14a0.688,0.688,0,0,0,.6-0.736,0.959,0.959,0,0,0-1.85-.109"></path></svg>';


        // Append buttons and select to the toolbar container
        richTextToolbar.appendChild(boldButton);
        richTextToolbar.appendChild(italicButton);
        richTextToolbar.appendChild(underlineButton);
        richTextToolbar.appendChild(bulletButton);
        richTextToolbar.appendChild(orderedButton);


        let posDescrptnInput = document.createElement('div');
        posDescrptnInput.setAttribute('id', 'posDescrptn');
        // posDescrptnInput.setAttribute('contenteditable', true);
        // posDescrptnInput.classList.add('form-control');

        let posDescrptnLabel = document.createElement('label');
        posDescrptnLabel.setAttribute('for', 'posDescrptn');
        posDescrptnLabel.textContent = 'Rules and Responsibilities';
        posDescrptnLabel.classList.add('mb-2');


        // Create the inner div element
        var modalActions = document.createElement('div');
        modalActions.className = 'modal-action w-100';

        // Create the Save button
        var saveButton = document.createElement('button');
        saveButton.setAttribute('id', 'save-button');
        saveButton.type = 'button';
        saveButton.className = 'btn btn-sm btn-primary';
        // saveButton.setAttribute('data-bs-dismiss', 'modal');
        saveButton.textContent = 'Save';
        var saveButtonLabel = document.createElement('label');
        saveButtonLabel.appendChild(saveButton);
        saveButtonLabel.setAttribute('for', 'save-button');

        // Create the Cancel button
        var cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.className = 'btn btn-sm btn-secondary';
        cancelButton.setAttribute('data-bs-dismiss', 'modal');
        cancelButton.textContent = 'Cancel';

        // Append buttons to the inner div
        modalActions.appendChild(saveButtonLabel);
        modalActions.appendChild(cancelButton);

        let editModalContent = document.createDocumentFragment();

        editModalContent.appendChild(positionInputLabel);
        editModalContent.appendChild(positionInput);
        editModalContent.appendChild(posDescrptnLabel);
        editModalContent.appendChild(richTextToolbar);
        editModalContent.appendChild(posDescrptnInput);
        editModalContent.appendChild(modalActions);


        let tempContainer = document.createElement('div');
        tempContainer.appendChild(editModalContent);

        editModalContent = tempContainer.innerHTML;


        return editModalContent;
    }

    static extractData(INPUT_ELEMENT, TEXT_EDITOR) {


        if (INPUT_ELEMENT) {
            let data_id = INPUT_ELEMENT.getAttribute('data-data-id');
            let input_id = INPUT_ELEMENT.getAttribute('data-target-input');
            let data_sequence = INPUT_ELEMENT.getAttribute('data-sequence');
            let initial_val = INPUT_ELEMENT.getAttribute('data-initial');
            let data_val = INPUT_ELEMENT.value;

            let description = TEXT_EDITOR.getContents();
            let extracted = {
                isChange: true,
                data: [{
                    'input_id': input_id,
                    'data_id': data_id,
                    'sequence': data_sequence,
                    'value': data_val,
                    'description': description,
                }]
            }
            return extracted;
        }


    }

}



function onSavePosition(INPUT_ELEMENT, TEXT_EDITOR) {
    let data = EditPositionModal.extractData(INPUT_ELEMENT, TEXT_EDITOR);
    if (data && data.isChange) {
        postData(data.data)
            .then(function (result) {
                const { data, success, error } = result;

                if (success) {
                    updatePostion(data);
                    edit_position_modal.hide();
                } else {
                    console.error('POST request failed:', error);
                }
            })

    }
}


function onCancelPosition() {
    let data = extractData();
    if (data && data.isChange) {
        // warn unsave changes
    }
}



let edit_position_modal = CandidatePosition.createModal(POSITION_MODAL_ID,
    'modal fade',
    'modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down',
    {
        header: { className: '', innerHTML: '<h5 class="modal-title">Edit a Candidate Position</h5> <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x-circle" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i></button>' },
        body: { className: '', innerHTML: EditPositionModal.createTemplate() },
    });



function handleTableRowLongPress(event) {
    const INPUT_FOCUSED = event.target.querySelectorAll('input:focus-visible');
    if (INPUT_FOCUSED.length > 0) {

        return;
    }

    showCandidatePositionDialog(event.target)

}

function handleTableRowDblClick(event) {
    const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');

    if (INPUT_FOCUSED.length > 0) {

        return;
    }

    showCandidatePositionDialog(event.currentTarget)


}


let quill;
let positionInput;
const saveFunc = () => onSavePosition(positionInput, quill);
function showCandidatePositionDialog(event) {
    const FORM_DATA = getForm(event);

    quill = new Quill('#posDescrptn', {
        modules: {
            toolbar: '#rich-txt-toolbar'
        },
        placeholder: 'Type duties and responsibilities here.',
    });

    CandidatePosition.updateModalContent(FORM_DATA, quill);

    CandidatePosition.showModal(edit_position_modal);

    let modal = document.getElementById(POSITION_MODAL_ID);
    positionInput = modal.querySelector(`#positionInput`);
    positionInput.removeEventListener('input', handleModalInput);
    positionInput.addEventListener('input', handleModalInput);
    let saveButton = modal.querySelector(`#save-button`);

    saveButton.removeEventListener('click', saveFunc);
    saveButton.addEventListener('click', saveFunc);
}


function handleModalInput(event) {
    const inputElement = event.target;

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        try {
            if (position_validate.validate(inputElement)) {
                inputElement.style.borderBottomColor = '';
            } else {
                inputElement.style.borderBottomColor = 'red';
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 300);
}


function handleInput(event) {
    setTextEditableWidth(event.target);
    const inputElement = event.target;

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        try {

            if (position_validate.validate(inputElement, setTextEditableWidth)) {
                inputElement.style.outline = '';
                let form_data = getForm(inputElement);
                postData(form_data)
                    .then(function (result) {
                        const { data, success, error } = result;

                        if (success) {
                            updatePostion(data, false);
                        } else {
                            console.error('POST request failed:', error);
                        }
                    })

            } else {
                inputElement.style.outline = '0.5px solid red';
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 400);
}

const TABLE_BODY = document.querySelector(`#example tbody`);
const DELETE_BUTTON = document.getElementById('delete');
const DELETE_LABEL = document.getElementById('delete-label');

function handleTableRowClick(event) {

    const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
    if (INPUT_FOCUSED.length > 0) {
        return;
    }

    event.currentTarget.classList.toggle('selected');

    const SELECTED_COUNT = countSelectedRows();

    updateToolbarButton(SELECTED_COUNT);
}

function countSelectedRows() {
    const SELECTED_ROWS = TABLE_BODY.querySelectorAll('tr.selected');
    return SELECTED_ROWS.length;
}

function updateToolbarButton(SELECTED_COUNT) {
    if (SELECTED_COUNT > 0) {
        DELETE_BUTTON.setAttribute('data-selected', SELECTED_COUNT);

        if (DELETE_BUTTON && DELETE_LABEL) {
            handleDeleteLabel(false, SELECTED_COUNT);
            DELETE_BUTTON.addEventListener('click', handleDeleteBtn);
        }
        DELETE_BUTTON.disabled = false;
    } else {
        DELETE_BUTTON.setAttribute('data-selected', '');

        if (DELETE_BUTTON && DELETE_LABEL) {
            handleDeleteLabel(true);
            DELETE_BUTTON.removeEventListener('click', handleDeleteBtn);
        }
        DELETE_BUTTON.disabled = true;
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
            targets: 2, className: `d-none pos-description`,
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
    scrollY: '4.5rem ',
    scrollCollapse: true,
    paging: false,
    initComplete: function (settings, json) {

        CandidatePosition.addTableListener('example');

        document.getElementById('add-new').addEventListener('click', function () {
            let blankRowData = DTableUtil.AddRowData('example');
            table.row.add(blankRowData).draw(false);

            let rowData = [
                {
                    'input_id': 'text-editable-' + blankRowData[1].sequence,
                    'data_id': blankRowData[1].data_id,
                    'sequence': blankRowData[1].sequence,
                    'value': blankRowData[1].value,
                    'description': blankRowData[2]
                }
            ];

            quill = new Quill('#posDescrptn', {
                modules: {
                    toolbar: '#rich-txt-toolbar'
                },
                placeholder: 'Type duties and responsibilities here.',
            });

            CandidatePosition.updateModalContent(rowData, quill, true);

            CandidatePosition.showModal(edit_position_modal);

            let modal = document.getElementById(POSITION_MODAL_ID);
            positionInput = modal.querySelector(`#positionInput`);
            let saveButton = modal.querySelector(`#save-button`);

            saveButton.removeEventListener('click', saveFunc);
            saveButton.addEventListener('click', saveFunc);

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
        let position_input_val = position_input.val();
        let position_description = $(diff[i].node).find('td.pos-description .text-truncate');
        let position_description_value = position_description.html();

        console.log('diff ' + JSON.stringify($(diff[i].node)));
        console.log('data position_input_val ' + position_input_val);
        console.log('data position_description_value ' + position_description_value);
        const NEW_DATA_SEQ = {
            'input_id': position_input_id,
            'data_id': data_id,
            'sequence': new_sequence,
            'value': position_input_val,
            'description': position_description_value,
        }


        data.update_sequence.push(NEW_DATA_SEQ);

    }

    console.log('data sequence' + JSON.stringify(data));

    postData(data)
        .then(function (result) {
            const { data, success, error } = result;

            if (success) {
                // updatePostion(data);
            } else {
                console.error('POST request failed:', error);
            }
        })

});



table.on('draw', function () {
    if (table.data().any()) {
        CandidatePosition.addTableListener('example');
        setTimeout(() => {
            const textEditableElements = getAllTextEditable();
            textEditableElements.forEach(element => setTextEditableWidth(element));
        }, 50);
        $('div.toolbar').show();
        $('table#example').show();
        $(this).parent().show();
    } else {
        $('div.toolbar').hide();
        $('table#example').hide();
        $(this).parent().hide();
    }
});



function handleDeleteLabel(isDisabled, SELECTED_COUNT = 0) {

    let tooltip = bootstrap.Tooltip.getInstance("#delete-label");

    if (isDisabled) {
        tooltip._config.title = 'No item selected.';

    } else {
        if (SELECTED_COUNT > 1) {
            tooltip._config.title = `${SELECTED_COUNT} items selected.`;
        } else {
            tooltip._config.title = `${SELECTED_COUNT} item selected.`;
        }

    }
    tooltip.update();
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
}

function deletePosition(DATA) {
    if (DATA && DATA.data && Array.isArray(DATA.data)) {
        DATA.data.forEach(item => {
            const { data_id, input_id } = item;

            let INPUT_ELEMENT = document.getElementById(input_id);
            let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
            if (DATA_ROW) {
                table.row(DATA_ROW).remove().draw();
                const SELECTED_COUNT = countSelectedRows();
                updateToolbarButton(SELECTED_COUNT);
            } else {

                console.error(`Input element with ID not found.`);
            }
        });
    } else {
        // console.error('Invalid or missing DATA structure.');
    }
}

function updatePostion(DATA, draw = true) {

    if (DATA && DATA.data && Array.isArray(DATA.data)) {
        DATA.data.forEach(item => {
            console.log("each pos update " + JSON.stringify(item));
            let { sequence, data_id, input_id, value, description } = item;
            if (draw) {
                let rowData = {
                    0: sequence,
                    1: {
                        data_id: data_id,
                        sequence: sequence,
                        value: value
                    },
                    2: description
                }
                console.log('row ' + JSON.stringify(rowData));
                let INPUT_ELEMENT = document.getElementById(input_id);
                let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
                if (DATA_ROW) {
                    table.row(DATA_ROW).data(rowData).draw(false);
                } else {

                    console.error(`Input element with ID not found.`);
                }
            } else {


                // let INPUT_ELEMENT = document.getElementById(input_id);
                // if (INPUT_ELEMENT) {
                //     INPUT_ELEMENT.name = data_id;
                // } else {

                //     console.error(`Input element with ID not found.`);
                // }
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
    return textEditableInputs;
}

const customValidation = {
    clear_invalid: true,
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


ViewportDimensions.listenWindowResize(() => {
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

    return form_data;
}



function postData(post_data) {
    let url = 'src/includes/classes/config-candidate-pos-controller.php';
    let method = 'PUT';
    let json_data = JSON.stringify(post_data);

    if ('update_sequence' in post_data) {
        method = 'UPDATE';
        logToFile.log('Update dta ', post_data, ' stringtified ', JSON.stringify(post_data));
        // logToFile.downloadLog();
    } else if ('delete_position' in post_data) {
        logToFile.log('Delete dta ', post_data, ' stringtified ', JSON.stringify(post_data));
        // logToFile.downloadLog();
        method = 'DELETE';
    }
    logToFile.log('PUT dta ', post_data, ' stringtified ', JSON.stringify(post_data));
    // logToFile.downloadLog();
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
            logToFile.log('Response dta ', data, ' stringtified ', JSON.stringify(data));
            // logToFile.downloadLog();
            return { data, success: true };
        })
        .catch(function (error) {
            console.error('POST request error:', error);
            return { error, success: false };
        });
}


fetchData();

function fetchData() {
    var url = 'src/includes/classes/config-candidate-pos-controller.php';

    fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function (data) {
            const TABLE_DATA = processData(data);
            logToFile.log('fetched dta ', data, ' stringtified ', JSON.stringify(data));
            logToFile.log('fetched processed dta ', TABLE_DATA, ' stringtified ', JSON.stringify(TABLE_DATA));
            // logToFile.downloadLog();
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

    return TABLE_DATA;
}


function insertData(TABLE_DATA, TABLE) {

    TABLE.clear();
    TABLE.rows.add(TABLE_DATA).draw(true);

    CandidatePosition.addTableListener('example');

}


// })();