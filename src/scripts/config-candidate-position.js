import { initializeConfigurationJS as ConfigJS } from './configuration.js';
import ViewportDimensions from './viewport.js';
import InputValidator from './input-validator.js';
import setTextEditableWidth from './configuration-set-text-editable-width.js';

import { Debugout } from '../../vendor/node_modules/debugout.js/dist/debugout.min.js';

const logToFile = new Debugout({ realTimeLoggingOn: true, useTimestamps: true });

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

ConfigJS(ConfigPage);
ConfigPage.removeEventListeners();

ConfigPage = null;
ConfigPage = {};

ConfigPage = {

    touchStartHandler: function (callback) {
        return (event) => {
            clearTimeout(ConfigPage.longPressTimer);

            ConfigPage.longPressTimer = setTimeout(() => {
                ConfigPage.longPressTimer = null;
                callback(event);
            }, 600);
        };
    },

    cancelTouch: () => {
        clearTimeout(ConfigPage.longPressTimer);
    },

    onLongPress: function (element, callback) {
        const touchStart = ConfigPage.touchStartHandler(callback);

        // If there are existing longPressHandlers, remove them
        ConfigPage.delEventListener(element, 'touchstart');
        ConfigPage.delEventListener(element, 'touchend');
        ConfigPage.delEventListener(element, 'touchmove');

        // Add new longPressHandlers
        ConfigPage.addEventListenerAndStore(element, 'touchstart', touchStart);
        ConfigPage.addEventListenerAndStore(element, 'touchend', ConfigPage.cancelTouch);
        ConfigPage.addEventListenerAndStore(element, 'touchmove', ConfigPage.cancelTouch);

    },

    EditPositionModal: class EditPositionModal {

        static createTemplate() {
            let positionInput = document.createElement('input');
            positionInput.setAttribute('type', 'text');
            positionInput.setAttribute('id', 'positionInput');
            positionInput.setAttribute('data-data-id', '');
            positionInput.setAttribute('data-target-input', '');
            positionInput.setAttribute('data-sequence', '');
            positionInput.setAttribute('data-initial', '');
            positionInput.classList.add('form-control', 'mb-1');
            positionInput.setAttribute('placeholder', 'Enter a candidate position');
            positionInput.setAttribute('pattern', '[a-zA-Z .\\-]{1,50}');
            positionInput.setAttribute('required', '');

            let positionAlert = document.createElement('div');
            positionAlert.classList.add('input-alert', 'mb-2');
            positionAlert.style.color = 'red';
            positionAlert.innerHTML = "&nbsp;";


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
            let appendedPositionInput = editModalContent.appendChild(positionInput);
            appendedPositionInput.insertAdjacentElement('afterend', positionAlert);
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
                let max_votes = $('#max-vote-picker').val();
                let data_val = INPUT_ELEMENT.value;

                let description = TEXT_EDITOR.getContents();
                let extracted = {
                    isChange: true,
                    data: [{
                        'input_id': input_id,
                        'data_id': data_id,
                        'sequence': data_sequence,
                        'value': data_val,
                        'max_votes': max_votes,
                        'description': description,
                    }]
                }
                return extracted;
            }


        }

    },

    onSavePosition: function (INPUT_ELEMENT, TEXT_EDITOR) {
        let data = ConfigPage.EditPositionModal.extractData(INPUT_ELEMENT, TEXT_EDITOR);
        console.log(data);
        if (!ConfigPage.position_validate.validate(INPUT_ELEMENT)) {
            INPUT_ELEMENT.style.borderBottomColor = '0.5px solid red';
            console.log(data);
            return;
        }

        INPUT_ELEMENT.style.borderBottomColor = '';

        if (data && data.isChange) {
            ConfigPage.postData(data.data)
                .then(function (result) {
                    const { data, success, error } = result;
                    console.log('ConfigPage.mode');
                    console.log(ConfigPage.mode);
                    if (success) {
                        if (ConfigPage.mode === 'add') {
                            ConfigPage.insertPosition(data);
                        } else if (ConfigPage.mode === 'update') {
                            ConfigPage.updatePostion(data);
                        }

                        ConfigPage.edit_position_modal.hide();
                    } else {
                        console.error('POST request failed:', error);
                    }
                })

        }
    },

    onCancelPosition: function () {
        let data = ConfigPage.extractData();
        if (data && data.isChange) {
            // warn unsave changes
        }
    },

    handleTableRowLongPress: function (event) {
        const INPUT_FOCUSED = event.target.querySelectorAll('input:focus-visible');
        if (INPUT_FOCUSED.length > 0) {

            return;
        }

        ConfigPage.showCandidatePositionDialog(event.target)

    },

    handleTableRowDblClick: function (event) {
        console.log('dbl click event ');
        console.log(event);
        const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');

        if (INPUT_FOCUSED.length > 0) {

            return;
        }

        ConfigPage.showCandidatePositionDialog(event.currentTarget)


    },

    showCandidatePositionDialog: function (event) {
        const FORM_DATA = ConfigPage.getForm(event);

        ConfigPage.quill = new Quill('#posDescrptn', {
            modules: {
                toolbar: '#rich-txt-toolbar'
            },
            placeholder: 'Type duties and responsibilities here.',
        });

        ConfigPage.CandidatePosition.updateModalContent(FORM_DATA, ConfigPage.quill);

        ConfigPage.CandidatePosition.showModal(ConfigPage.edit_position_modal);

        let modal = document.getElementById(ConfigPage.POSITION_MODAL_ID);
        ConfigPage.positionInput = modal.querySelector(`#positionInput`);
        ConfigPage.delEventListener(ConfigPage.positionInput, 'input');
        ConfigPage.addEventListenerAndStore(ConfigPage.positionInput, 'input', ConfigPage.handleModalInput);

        let saveButton = modal.querySelector(`#save-button`);

        ConfigPage.delEventListener(saveButton, 'click');
        ConfigPage.addEventListenerAndStore(saveButton, 'click', ConfigPage.saveFunc);
    },

    handleModalInput: function (event) {
        const inputElement = event.target;
        const adjacentElement = inputElement.nextElementSibling;


        clearTimeout(ConfigPage.typingTimeout);
        ConfigPage.typingTimeout = setTimeout(() => {
            try {
                if (ConfigPage.position_validate.validate(inputElement)) {
                    inputElement.style.borderBottomColor = '';
                    if (adjacentElement && adjacentElement.classList.contains('input-alert')) {
                        adjacentElement.innerHTML = "&nbsp;";
                    }
                } else {
                    inputElement.style.borderBottomColor = 'red';
                    if (adjacentElement && adjacentElement.classList.contains('input-alert')) {
                        adjacentElement.innerHTML = 'Valid characters are A-Z a-z dash (-) period(.) and spaces.';
                    }
                }
            } catch (error) {
                console.error('Validation error:', error);
            }
        }, 300);
    },

    handleInput: function (event) {
        setTextEditableWidth(event.target);
        const inputElement = event.target;
        console.log('getform');
        console.log(event.target);
        clearTimeout(ConfigPage.typingTimeout);
        ConfigPage.typingTimeout = setTimeout(() => {
            try {

                if (ConfigPage.position_validate.validate(inputElement, setTextEditableWidth)) {
                    inputElement.style.outline = '';
                    let form_data = ConfigPage.getForm(inputElement);
                    ConfigPage.postData(form_data)
                        .then(function (result) {
                            const { data, success, error } = result;

                            if (success) {
                                ConfigPage.updatePostion(data, false);
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
    },

    handleTableRowClick: function (event) {

        const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
        if (INPUT_FOCUSED.length > 0) {
            return;
        }

        event.currentTarget.classList.toggle('selected');

        const SELECTED_COUNT = ConfigPage.countSelectedRows();

        ConfigPage.updateToolbarButton(SELECTED_COUNT);
    },

    countSelectedRows: function () {
        const SELECTED_ROWS = ConfigPage.TABLE_BODY.querySelectorAll('tr.selected');
        return SELECTED_ROWS.length;
    },

    updateToolbarButton: function (SELECTED_COUNT) {
        if (SELECTED_COUNT > 0) {
            ConfigPage.DELETE_BUTTON.setAttribute('data-selected', SELECTED_COUNT);

            if (ConfigPage.DELETE_BUTTON && ConfigPage.DELETE_LABEL) {
                ConfigPage.handleDeleteLabel(false, SELECTED_COUNT);
                ConfigPage.addEventListenerAndStore(ConfigPage.DELETE_BUTTON, 'click', ConfigPage.handleDeleteBtn);
            }
            ConfigPage.DELETE_BUTTON.disabled = false;
        } else {
            ConfigPage.DELETE_BUTTON.setAttribute('data-selected', '');

            if (ConfigPage.DELETE_BUTTON && ConfigPage.DELETE_LABEL) {
                ConfigPage.handleDeleteLabel(true);
                ConfigPage.delEventListener(ConfigPage.DELETE_BUTTON, 'click');
            }
            ConfigPage.DELETE_BUTTON.disabled = true;
        }
    },

    startTableListener: function () {
        ConfigPage.table.on('row-reorder', function (e, diff, edit) {
            let data = {
                'update_sequence': []
            };
            // let result = 'Reorder started on row: ' +  + '<br>';
            for (var i = 0, ien = diff.length; i < ien; i++) {
                let rowData = ConfigPage.table.row(diff[i].node).data();
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

            ConfigPage.postData(data)
                .then(function (result) {
                    const { data, success, error } = result;

                    if (success) {
                        // ConfigPage.updatePostion(data);
                    } else {
                        console.error('POST request failed:', error);
                    }
                })

        });



        ConfigPage.table.on('draw', function () {
            if (ConfigPage.table.data().any()) {
                ConfigPage.CandidatePosition.addTableListener('example');
                setTimeout(() => {
                    const textEditableElements = ConfigPage.getAllTextEditable();
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
            ConfigPage.deselectAll();
        });


    },

    handleDeleteLabel: function (isDisabled, SELECTED_COUNT = 0) {

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
    },

    handleDeleteBtn: function () {
        ConfigPage.DELETE_BUTTON.disabled = true;
        const FORM = document.querySelectorAll(`table tbody tr.selected`);

        let deleteData = {
            'delete_position': []
        };

        const DATA = ConfigPage.getForm(FORM, false);

        for (const ITEM of DATA) {
            deleteData.delete_position.push(ITEM);
        }


        ConfigPage.postData(deleteData)
            .then(function (result) {
                try {
                    const { data, success, error } = result;
                    if (success) {
                        ConfigPage.deletePosition(data);
                    } else if (error.data) {
                        error.data.forEach(item => {
                            console.log('item');
                            console.log(item);
                            console.log(item.hasOwnProperty('affected_candidates'));

                            if (item.hasOwnProperty('affected_candidates')) {
                                ConfigPage.NativeModal.show(item);
                            } else {
                                ConfigPage.deletePosition({ data: [item] });
                            }
                        });
                    }
                }
                catch (e) {
                    console.error('POST request failed:', e);
                }
            })
    },

    deletePosition: function (DATA) {
        console.log('removing');
        console.log(DATA);
        console.log(DATA.data);
        console.log(Array.isArray(DATA.data));

        if (DATA && DATA.data && Array.isArray(DATA.data)) {

            DATA.data.forEach(item => {
                const { data_id, input_id } = item;

                let INPUT_ELEMENT = document.getElementById(input_id);
                let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
                if (DATA_ROW) {
                    ConfigPage.table.row(DATA_ROW).remove().draw();
                    const SELECTED_COUNT = ConfigPage.countSelectedRows();
                    ConfigPage.updateToolbarButton(SELECTED_COUNT);
                    // ConfigPage.deselectAll();

                } else {

                    console.error(`Input element with ID not found.`);
                }
            });
        } else {
            // console.error('Invalid or missing DATA structure.');
        }
    },

    deselectAll: function () {
        const selectedRows = ConfigPage.TABLE_BODY.querySelectorAll('tr.selected');
        selectedRows.forEach(row => {
            row.classList.remove('selected');
        });
        const SELECTED_COUNT = ConfigPage.countSelectedRows();
        ConfigPage.updateToolbarButton(SELECTED_COUNT);
    },

    updatePostion: function (DATA, draw = true) {
        console.log('update table');

        if (DATA && DATA.data && Array.isArray(DATA.data)) {
            DATA.data.forEach(item => {
                console.log("each pos update " + JSON.stringify(item));
                let { sequence, data_id, input_id, value, max_votes, description } = item;
                if (draw) {
                    let rowData = {
                        0: sequence,
                        1: {
                            data_id: data_id,
                            sequence: sequence,
                            value: value,
                            max_votes: max_votes
                        },
                        2: description
                    }

                    let INPUT_ELEMENT = document.getElementById(input_id);
                    let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
                    if (DATA_ROW) {
                        console.log(DATA_ROW);
                        ConfigPage.table.row(DATA_ROW).data(rowData).draw(false);
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

    },

    insertPosition: function (DATA, draw = false) {

        if (DATA && DATA.data && Array.isArray(DATA.data)) {
            DATA.data.forEach(item => {
                console.log("each pos update " + JSON.stringify(item));
                let { sequence, data_id, input_id, value, max_votes, description } = item;
                if (!draw) {
                    let rowData = {
                        0: sequence,
                        1: {
                            data_id: data_id,
                            sequence: sequence,
                            value: value,
                            max_votes: (Number.isInteger(max_votes) && max_votes !== null) ? max_votes :
                                (!isNaN(parseInt(max_votes)) ? parseInt(max_votes) : 1)
                        },
                        2: description
                    }

                    ConfigPage.table.row.add(rowData).draw(false);

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

    },

    getAllTextEditable: function () {
        // Select all text input elements with type 'text' and class 'text-editable' within the 'main' element
        const mainElement = document.querySelector('main');
        const textEditableInputs = mainElement.querySelectorAll('input[type="text"].text-editable');
        return textEditableInputs;
    },

    customValidation: {
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
    },

    addTextEditableResizeEvent: ViewportDimensions.listenWindowResize(() => {
        let textEditableInputs = document.querySelectorAll('main input[type="text"].text-editable');
        textEditableInputs.forEach(input => {
            setTextEditableWidth(input);
        });
    }),

    getForm: function (form, search = true) {
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
                let data_max_votes = input_dom.getAttribute('data-max');
                let data_description = description_dom.textContent || '';

                let row_data = {
                    'input_id': input_id,
                    'data_id': data_id,
                    'sequence': data_sequence,
                    'value': data_val,
                    'max_votes': data_max_votes,
                    'description': data_description,
                };

                form_data.push(row_data);
            }
        });

        return form_data;
    },

    postData: function (post_data) {
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
                    return response.json().then(data => {
                        throw data;
                    });
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
    },

    fetchData: function () {
        var url = 'src/includes/classes/config-candidate-pos-controller.php';

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                const TABLE_DATA = ConfigPage.processData(data);
                logToFile.log('fetched dta ', data, ' stringtified ', JSON.stringify(data));
                logToFile.log('fetched processed dta ', TABLE_DATA, ' stringtified ', JSON.stringify(TABLE_DATA));
                // logToFile.downloadLog();
                ConfigPage.insertData(TABLE_DATA, ConfigPage.table);
                console.log('GET request successful:', data);
            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },

    processData: function (data) {
        const TABLE_DATA = [];

        data.forEach(item => {
            const tableItem = {
                0: item.sequence,
                1: {
                    data_id: item.data_id,
                    sequence: item.sequence,
                    value: item.value,
                    max_votes: item.max_votes
                },
                2: item.description
            };

            TABLE_DATA.push(tableItem);
        });

        return TABLE_DATA;
    },

    insertData: function (TABLE_DATA, TABLE) {
        console.log(TABLE);
        TABLE.clear();
        TABLE.rows.add(TABLE_DATA).draw(true);

        ConfigPage.CandidatePosition.addTableListener('example');

    }

}

/**
     * Utility class for Datatable.
     */
ConfigPage.DTableUtil = class DTableUtil {
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
            let sequence = this.FindLastSequence(table_id);
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
                    value: '',
                    max_votes: 1,
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
ConfigPage.CandidatePosition = class CandidatePosition {

    static maxVoteOptions = [
        { value: '1', text: '' },
        { value: '1', text: 'One (1)' },
        { value: '2', text: 'Two (2)' },
        { value: '3', text: 'Three (3)' },
        { value: '4', text: 'Four (4)' }
    ];


    static updateTextEditableListeners(inputElements) {
        console.log('adding listener');
        console.log(inputElements);
        inputElements.forEach(inputElement => {
            ConfigPage.delEventListener(inputElement);
            ConfigPage.addEventListenerAndStore(inputElement, 'input', ConfigPage.handleInput);
        });
    };

    static updateTableRowListeners(tableRows) {
        console.log('adding click listener');
        console.log(tableRows);
        tableRows.forEach(row => {
            ConfigPage.delEventListener(row, 'click');
            ConfigPage.delEventListener(row, 'dblclick');
        });

        tableRows.forEach(row => {
            ConfigPage.addEventListenerAndStore(row, 'click', ConfigPage.handleTableRowClick);
        });

        tableRows.forEach(row => {
            ConfigPage.addEventListenerAndStore(row, 'dblclick', ConfigPage.handleTableRowDblClick);
        });
    };

    /**
     * Adds event listeners to enable interactive features on a candidate position table.
     * @param {string} table_id - The ID of the HTML table to which event listeners will be added.
     */
    static addTableListener(table_id) {
        /**
         * Retrieves all text editable input elements within the specified table.
         * @returns {NodeListOf<HTMLInputElement>} A list of text editable input elements.
         */
        const ALL_TEXT_EDITABLE = ConfigPage.getAllTextEditable();

        /**
         * Retrieves all table rows within the specified table.
         * @type {NodeListOf<HTMLTableRowElement>}
         */
        const TABLE_ROW = document.querySelectorAll(`#${table_id} tbody tr`);

        this.updateTextEditableListeners(ALL_TEXT_EDITABLE);
        this.updateTableRowListeners(TABLE_ROW);


        // Add event listeners for touch events
        TABLE_ROW.forEach(row => {
            ConfigPage.onLongPress(row, (event) => {
                ConfigPage.handleTableRowLongPress(event);
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

        let editModalElement = document.getElementById(ConfigPage.POSITION_MODAL_ID);
        let editModal = new bootstrap.Modal(editModalElement);

        return editModal;

    }

    static delSelectMaxVotes() {
        let existingSelectMaxVotes = document.getElementById('max-vote-picker');
        if (existingSelectMaxVotes) {
            $('.max-vote-picker').selectpicker('destroy');
            existingSelectMaxVotes.remove();
        }
    }

    static setMaxVoteOptions(selectElement) {
        this.maxVoteOptions.forEach(optionData => {
            let option = document.createElement('option');
            option.value = optionData.value;
            option.text = optionData.text;
            if (optionData.disabled) {
                option.disabled = true;
            }
            selectElement.appendChild(option);
        });
    }


    static initSelectMaxVotes(positionNameInput, value) {
        this.delSelectMaxVotes();

        let maxVotesLabel = document.querySelector('label[for="max-vote-picker"]');
        if (!maxVotesLabel) {
            maxVotesLabel = document.createElement('label');
            maxVotesLabel.setAttribute('for', 'max-vote-picker');
            maxVotesLabel.classList.add('d-block', 'mb-2');
            maxVotesLabel.innerHTML = 'Vote Selection <span class="required"> *</span>';
            let posNameInputAdjacent = positionInput.nextElementSibling;
            if (posNameInputAdjacent && posNameInputAdjacent.classList.contains('input-alert')) {
                posNameInputAdjacent.insertAdjacentElement('afterend', maxVotesLabel);
            }
        }

        maxVotesLabel = document.querySelector('label[for="max-vote-picker"]');

        let selectMaxVotes = document.createElement('select');
        selectMaxVotes.classList.add('max-vote-picker', 'd-block', 'mb-4');
        selectMaxVotes.setAttribute('data-none-selected-text', 'Choose');
        // selectMaxVotes.setAttribute('data-width', 'auto');
        selectMaxVotes.id = 'max-vote-picker';
        this.setMaxVoteOptions(selectMaxVotes);
        maxVotesLabel.insertAdjacentElement('afterend', selectMaxVotes);

        $('#max-vote-picker').selectpicker();
        console.log("value");
        console.log(value);
        $('#max-vote-picker').selectpicker('val', value);
    }

    static updateModalContent(DATA, quill, isAdd = false) {

        let edit_position_modal = document.getElementById(ConfigPage.POSITION_MODAL_ID);
        let positionNameInput = edit_position_modal.querySelector('input[type="text"]');

        if (isAdd) {
            edit_position_modal.querySelector('.modal-title').textContent = 'Add New Position';
            ConfigPage.mode = 'add';
        } else {

            edit_position_modal.querySelector('.modal-title').textContent = 'Edit a Candidate Position';
            ConfigPage.mode = 'update';

        }

        if (positionNameInput) {
            positionNameInput.setAttribute('data-data-id', DATA[0].data_id);
            positionNameInput.setAttribute('data-target-input', DATA[0].input_id);
            positionNameInput.setAttribute('data-sequence', DATA[0].sequence);
            positionNameInput.setAttribute('data-initial', DATA[0].value);
            positionNameInput.value = DATA[0].value ?? '';
        }

        this.initSelectMaxVotes(positionNameInput, DATA[0].max_votes);

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
            let modal = document.getElementById(ConfigPage.POSITION_MODAL_ID);
            modal.removeEventListener('hidden.bs.modal', event => {
                ConfigPage.deselectAll();
            })
            modal.addEventListener('hidden.bs.modal', event => {
                ConfigPage.deselectAll();
            })
        }
    }
}

/**
 * A Map that stores event listeners associated with elements.
 * This used to avoid duplicate event listeners.
 * @type {Map<Element, { event: string, handler: function }>}
 */
ConfigPage.eventListeners = new Map();

/**
 * Adds an event listener to the specified element and stores it in the ConfigPage.eventListeners Map.
 * @function
 * @name ConfigPage.addEventListenerAndStore
 * @memberof ConfigPage
 * @param {Element} element - The DOM element to which the event listener is added.
 * @param {string} event - The name of the event to listen for.
 * @param {function} handler - The function to be executed when the event is triggered.
 */
ConfigPage.addEventListenerAndStore = function (element, event, handler) {
    element.addEventListener(event, handler);
    const key = `${element}-${event}`;
    ConfigPage.eventListeners.set(key, handler);
}

/**
 * Removes the event listener associated with the specified element and deletes its entry from the ConfigPage.eventListeners Map.
 * @function
 * @name ConfigPage.delEventListener
 * @memberof ConfigPage
 * @param {Element} element - The DOM element from which the event listener is removed.
 */
ConfigPage.delEventListener = function (element, event) {
    const key = `${element}-${event}`;
    if (ConfigPage.eventListeners.has(key)) {
        const handler = ConfigPage.eventListeners.get(key);
        element.removeEventListener(event, handler);
        ConfigPage.eventListeners.delete(key);
    }
}

ConfigPage.POSITION_MODAL_ID = 'edit-modal';
ConfigPage.position_validate = new InputValidator(ConfigPage.customValidation);
ConfigPage.longPressTimer = null;

ConfigPage.table = new DataTable('#example', {
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
                return `<input class="text-editable" type="text" name="${data.data_id}" id="text-editable-${data.sequence}" data-max="${data.max_votes}" value="${data.value}" placeholder="Enter a candidate position" pattern="[a-zA-Z .\\-]{1,50}" required="" style="width: 92.885px;">`;
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

        ConfigPage.CandidatePosition.addTableListener('example');

        ConfigPage.addEventListenerAndStore(document.getElementById('add-new'), 'click', function () {
            let blankRowData = ConfigPage.DTableUtil.AddRowData('example');
            // ConfigPage.table.row.add(blankRowData).draw(false);

            let rowData = [
                {
                    'input_id': 'text-editable-' + blankRowData[1].sequence,
                    'data_id': blankRowData[1].data_id,
                    'sequence': blankRowData[1].sequence,
                    'value': blankRowData[1].value,
                    'max_votes': blankRowData[1].max_votes,
                    'description': blankRowData[2]
                }
            ];

            ConfigPage.quill = new Quill('#posDescrptn', {
                modules: {
                    toolbar: '#rich-txt-toolbar'
                },
                placeholder: 'Type duties and responsibilities here.',
            });

            ConfigPage.CandidatePosition.updateModalContent(rowData, ConfigPage.quill, true);

            ConfigPage.CandidatePosition.showModal(ConfigPage.edit_position_modal);

            let modal = document.getElementById(ConfigPage.POSITION_MODAL_ID);
            ConfigPage.positionInput = modal.querySelector(`#positionInput`);
            let saveButton = modal.querySelector(`#save-button`);

            ConfigPage.delEventListener(saveButton, 'click');
            ConfigPage.addEventListenerAndStore(saveButton, 'click', ConfigPage.saveFunc);

            ConfigPage.CandidatePosition.addTableListener('example');

            const dtScrollBody = document.querySelector('.dt-scroll-body');
            dtScrollBody.scrollTop = dtScrollBody.scrollHeight;
        });

    }
}),

    ConfigPage.TABLE_BODY = document.querySelector(`#example tbody`);
ConfigPage.DELETE_BUTTON = document.getElementById('delete');
ConfigPage.DELETE_LABEL = document.getElementById('delete-label');


ConfigPage.POSITION_MODAL_ID = 'edit-modal';
ConfigPage.longPressTimer;

ConfigPage.edit_position_modal = ConfigPage.CandidatePosition.createModal(ConfigPage.POSITION_MODAL_ID,
    'modal fade',
    'modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down',
    {
        header: { className: 'editor', innerHTML: '<h5 class="modal-title">Edit a Candidate Position</h5> <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x-circle" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i></button>' },
        body: { className: '', innerHTML: ConfigPage.EditPositionModal.createTemplate() },
    });

ConfigPage.positionInput;
ConfigPage.saveFunc = () => ConfigPage.onSavePosition(ConfigPage.positionInput, ConfigPage.quill);
ConfigPage.typingTimeout;

ConfigPage.fetchData();
ConfigPage.startTableListener();

ConfigPage.NativeModal = class {
    static modalElement = document.getElementsByClassName('modal-native');
    static data;

    static show(data, isModal = true) {
        this.#updateContent(data);
        if (this.modalElement.length > 0) {
            if (isModal) {
                this.modalElement[0].showModal();
            }
            else {
                this.modalElement[0].show();
            }

            ConfigPage.delEventListener(this.modalElement[0], 'close');
            ConfigPage.addEventListenerAndStore(this.modalElement[0], 'close', ConfigPage.deselectAll);
        }
    }
    static #updateContent(data) {
        console.log('updateContent');
        console.log(data);
        this.data = data;
        let candidatesList = this.modalElement[0].querySelector('.modal-body .affected.candidate-list');

        let promptMessage = this.modalElement[0].querySelector('.modal-body h5');
        if (!promptMessage) {
            promptMessage = this.#createPromptMsg();
        }
        const candidateCount = data.affected_candidates.length;
        promptMessage.innerHTML =
            `You are about to remove the candidate${candidateCount > 1 ? 's' : ''} and votes associated with the position of <b>${data.value}</b>. `;

        candidatesList.innerHTML = '';
        data.affected_candidates.forEach(candidate => {
            let fullName = `${candidate.last_name},<br> ${candidate.first_name}`;
            if (candidate.middle_name) {
                fullName += ` ${candidate.middle_name}`;
            }
            let parentDiv = document.createElement("div");
            parentDiv.classList.add('col-3');

            let fullNameDiv = document.createElement("div");
            fullNameDiv.classList.add('name');
            fullNameDiv.innerHTML = fullName;

            let photoDiv = document.createElement("div");
            photoDiv.classList.add('photo');

            let image = document.createElement("img");
            image.src = `src/${candidate.photo_url}`;
            image.alt = `${fullName} photo`;
            photoDiv.appendChild(image);

            parentDiv.appendChild(photoDiv);
            parentDiv.appendChild(fullNameDiv);

            candidatesList.appendChild(parentDiv);
        });


        let modalActionDiv = this.#initBtn();
        candidatesList.insertAdjacentElement('afterend', modalActionDiv);

        let primaryBtn = this.modalElement[0].querySelector('#modal-action-primary');
        ConfigPage.delEventListener(primaryBtn, 'click');
        ConfigPage.addEventListenerAndStore(primaryBtn, 'click', this.#confirmDelete.bind(this));

        // let primaryBtn = document.querySelector('modal-action-primary');
        // ConfigPage.delEventListener(saveButton, 'click');
        // ConfigPage.addEventListenerAndStore(saveButton, 'click', ConfigPage.saveFunc);

        let cancelBtn = this.modalElement[0].querySelector('.modal-body .cancel-btn');
        ConfigPage.delEventListener(cancelBtn, 'click');
        ConfigPage.addEventListenerAndStore(cancelBtn, 'click', this.close.bind(this));

        let closeBtn = this.modalElement[0].querySelector('button.modal-close');
        ConfigPage.delEventListener(closeBtn, 'click');
        ConfigPage.addEventListenerAndStore(closeBtn, 'click', this.close.bind(this));
    }

    static #createPromptMsg() {

        let promptMessage = document.createElement('h5');
        let modalBody = this.modalElement[0].querySelector('.modal-body');
        modalBody.insertBefore(promptMessage, modalBody.firstChild);
        return this.modalElement[0].querySelector('.modal-body h5');
    }

    static #initBtn() {

        this.#removeBtn();

        const modalActionDiv = document.createElement('div');
        modalActionDiv.classList.add('modal-action', 'w-100');

        // Create the label and primary button
        const label = document.createElement('label');
        label.setAttribute('for', 'modal-action-primary');

        const primaryButton = document.createElement('button');
        primaryButton.id = 'modal-action-primary';
        primaryButton.type = 'button';
        primaryButton.classList.add('btn', 'btn-sm', 'btn-primary');
        primaryButton.textContent = 'Delete';

        label.appendChild(primaryButton);

        const cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.classList.add('btn', 'btn-sm', 'btn-secondary', 'cancel-btn');
        cancelButton.textContent = 'Cancel';

        // Append everything to the main div
        modalActionDiv.appendChild(label);
        modalActionDiv.appendChild(cancelButton);

        return modalActionDiv;
    }

    static #removeBtn() {
        let modalBody = this.modalElement[0].querySelector('.modal-body');
        let existingModalAction = modalBody.querySelector('.modal-action');
        if (existingModalAction) {
            modalBody.removeChild(existingModalAction);
        }
    }

    static #confirmDelete() {
        if (this.data) {
            let primaryButton = this.modalElement[0].querySelector('.modal-body #modal-action-primary');
            primaryButton.disabled = true;
            this.#processDelete();
        }
    }

    static #processDelete() {
        if (this.data) {
            this.data.confirmed_delete = true;
            let data = { delete_position: [this.data] }
            // console.log(data);
            ConfigPage.postData(data).then(function (result) {
                console.log(result);
                try {
                    const { data, success, error } = result;
                    if (success) {
                        ConfigPage.deletePosition(data);
                        ConfigPage.NativeModal.close();
                    }
                }
                catch (e) {
                    console.error('Error deleting', e);
                }
            });
        }
    }

    static close() {
        if (this.data) {
            this.modalElement[0].close();
        }
    }

    static #redirect() {

    }

}
