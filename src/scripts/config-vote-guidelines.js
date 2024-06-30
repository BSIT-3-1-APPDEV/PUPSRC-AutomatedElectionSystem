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

ConfigJS(ConfigPage);
ConfigPage.removeEventListeners();
ConfigPage = null;
ConfigPage = {};

ConfigPage = {

    fetchSchedule: function (requestData) {
        let url = `src/includes/classes/config-election-sched-controller.php`;
        const queryParams = new URLSearchParams(requestData);
        url = `${url}?${queryParams.toString()}`;

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                // process shedule data

            })
            .catch(function (error) {
                console.error('GET request error:', error);
            });
    },

    fetchVoteGuidelines: function (requestData) {
        let url = `src/includes/classes/config-vote-guideline-controller.php`;
        const queryParams = new URLSearchParams(requestData);
        url = `${url}?${queryParams.toString()}`;

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {

                const errorCodes = ConfigPage.extractErrorCodes(data);

                const voteGuidelines = ConfigPage.removeErrorCodes(data);

                const guidelineData = ConfigPage.processData(voteGuidelines);

                ConfigPage.TableHandler.setData(guidelineData, ConfigPage.table)
                // TABLE.rows.add(TABLE_DATA).draw(true);

            })
            .catch(function (error) {
                ConfigPage.table.draw(false);
                console.error('GET request error:', error);
            });
    },

    handleTableRowLongPress: function (event) {

        const hasFocusedInput = event.target.querySelectorAll('input:focus-visible').length > 0;

        if (hasFocusedInput) {

            return;
        }


        ConfigPage.showCandidatePositionDialog(event.target);
    },

    handleTableRowClick: function (event) {

        const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
        if (INPUT_FOCUSED.length > 0) {
            return;
        }

        event.currentTarget.classList.toggle('selected');

        const SELECTED_COUNT = ConfigPage.TableHandler.countSelectedRows();

        ConfigPage.TableHandler.updateToolbarButton(SELECTED_COUNT);
    },

    handleTableRowDblClick: function (event) {
        // const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');

        try {
            const dataContainer = event.currentTarget.querySelector('div.vote-rule-text');

            let data = {
                sequence: dataContainer.getAttribute('data-seq'),
                guideline_id: dataContainer.id,
                description: dataContainer.textContent
            }

            ConfigPage.EditorModal.show(data, false);

        } catch (error) {

        }

    },

    customValidation: {
        clear_invalid: false,
        trailing: {
            '-+': '-',    // Replace consecutive dashes with a single dash
            '\\.+': '.',  // Replace consecutive periods with a single period
            ' +': ' '     // Replace consecutive spaces with a single space
        },
        attributes: {
            required: true,
            // pattern: /[a-zA-Z .\-]{1,50}/,
            max_length: 500,
        },
        customMsg: {
            required: true,
            max_length: 'Vote rule length limit reached.',
        },
        errorFeedback: {
            required: 'ERR_BLANK_RULE',
            max_length: 'ERR_MAX_RULE_LENGTH',
        }
    },

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


ConfigPage.isoDateConverter = function (date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

// Make the date time act like constant
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
        let isoToday = ConfigPage.isoDateConverter(today);
        return isoToday;
    },
    enumerable: true,
    configurable: false,
});

Object.defineProperty(ConfigPage, 'CSRF_TOKEN', {
    value: setCSRFToken(),
    writable: false,
    enumerable: false,
    configurable: false
});

ConfigPage.vote_rule_validate = new InputValidator(ConfigPage.customValidation);

try {
    ConfigPage.fetchSchedule({ csrf: ConfigPage.CSRF_TOKEN });
} catch (error) {
    console.warn(error);
}



ConfigPage.processData = function (data) {
    const TABLE_DATA = [];
    // console.log('data')
    // console.log(data)
    for (const key in data) {
        const item = data[key];
        // console.log('process for loop key')
        // console.log(key)
        // console.log('process for loop item')
        // console.log(item)

        if (typeof item === 'object' && !Array.isArray(item)) {

            const tableItem = {
                0: item.sequence,
                1: {
                    data_id: item.guideline_id || null,
                    sequence: item.sequence,
                    description: item.description,
                },
            };
            TABLE_DATA.push(tableItem);
        }
    }




    return TABLE_DATA;
}

ConfigPage.postData = function (post_data, method) {
    let url = 'src/includes/classes/config-vote-guideline-controller.php';
    post_data.push({ csrf_token: `${ConfigPage.CSRF_TOKEN}` });
    let json_data = JSON.stringify(post_data);
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
        .then(function (response) {
            console.log('POST request successful:', response.data);
            let data = [];
            data = response.data;
            console.log(data)
            return { data, success: true };
        })
        .catch(function (error) {
            console.error('POST request error:', error);
            return { error, success: false };
        });
}


ConfigPage.extractErrorCodes = function (data) {
    if ("error_codes" in data) {
        const errorCodes = data.error_codes;
        return errorCodes;
    }

    return {};
}

ConfigPage.removeErrorCodes = function (data) {

    const cleanData = Object.assign({}, data);
    delete cleanData.error_codes;
    return cleanData;
}


try {
    ConfigPage.fetchVoteGuidelines({ csrf: ConfigPage.CSRF_TOKEN });
} catch (error) {
    console.warn(error);
}


ConfigPage.touchStartHandler = function (callback) {
    return (event) => {
        clearTimeout(ConfigPage.longPressTimer);

        ConfigPage.longPressTimer = setTimeout(() => {
            ConfigPage.longPressTimer = null;
            callback(event);
        }, 600);
    };
}

ConfigPage.cancelTouch = () => {
    clearTimeout(ConfigPage.longPressTimer);
}

ConfigPage.onLongPress = function (element, callback) {
    const touchStart = ConfigPage.touchStartHandler(callback);

    // If there are existing longPressHandlers, remove them
    ConfigPage.delEventListener(element, 'touchstart');
    ConfigPage.delEventListener(element, 'touchend');
    ConfigPage.delEventListener(element, 'touchmove');

    // Add new longPressHandlers
    ConfigPage.addEventListenerAndStore(element, 'touchstart', touchStart);
    ConfigPage.addEventListenerAndStore(element, 'touchend', ConfigPage.cancelTouch);
    ConfigPage.addEventListenerAndStore(element, 'touchmove', ConfigPage.cancelTouch);

}

ConfigPage.longPressTimer = null;

ConfigPage.TableHandler = class {


    static addListener(table_id) {
        /**
         * Retrieves all table rows within the specified table.
         * @type {NodeListOf<HTMLTableRowElement>}
         */
        const TABLE_ROW = document.querySelectorAll(`#${table_id} tbody tr`);

        this.updateTableRowListeners(TABLE_ROW);


        // Add event listeners for touch events
        TABLE_ROW.forEach(row => {
            ConfigPage.onLongPress(row, (event) => {
                // ConfigPage.handleTableRowLongPress(event);
            });

        });
    }

    static updateTableRowListeners(tableRows) {

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
    }

    static countSelectedRows() {
        const SELECTED_ROWS = ConfigPage.TABLE_BODY.querySelectorAll('tr.selected');
        return SELECTED_ROWS.length;
    }

    static updateToolbarButton(SELECTED_COUNT) {
        if (SELECTED_COUNT > 0) {
            ConfigPage.DELETE_BUTTON.setAttribute('data-selected', SELECTED_COUNT);

            if (ConfigPage.DELETE_BUTTON && ConfigPage.DELETE_LABEL) {
                this.handleDeleteLabel(false, SELECTED_COUNT);
                ConfigPage.delEventListener(ConfigPage.DELETE_BUTTON, 'click');
                ConfigPage.addEventListenerAndStore(ConfigPage.DELETE_BUTTON, 'click', this.handleDeleteBtn.bind(this));
            }
            ConfigPage.DELETE_BUTTON.disabled = false;
        } else {
            ConfigPage.DELETE_BUTTON.setAttribute('data-selected', '');

            if (ConfigPage.DELETE_BUTTON && ConfigPage.DELETE_LABEL) {
                this.handleDeleteLabel(true);
                ConfigPage.delEventListener(ConfigPage.DELETE_BUTTON, 'click');
            }
            ConfigPage.DELETE_BUTTON.disabled = true;
        }
    }

    static initReorderListener() {

        ConfigPage.table.on('row-reorder', function (e, diff, edit) {
            let data = [];
            // let result = 'Reorder started on row: ' +  + '<br>';
            for (var i = 0, ien = diff.length; i < ien; i++) {

                let row = $(diff[i].node).find('td .vote-rule-text');
                let sequence = diff[i].newData;
                let guideline_id = row.attr('id');
                let description = row.text();
                let extractedNumber;
                if (typeof guideline_id === 'string' && guideline_id.startsWith('rule-')) {
                    extractedNumber = parseInt(guideline_id.substring(5), 10);
                }

                const NEW_DATA_SEQ = {
                    guideline_id: extractedNumber,
                    sequence: sequence,
                    description: description,
                };

                data.push(NEW_DATA_SEQ);
            }

            ConfigPage.postData(data, 'PATCH')
                .then(function (result) {
                    const { data, success, error } = result;
                    if (success) {
                        try {
                            const { data, success, error } = result;

                            if (success) {
                                console.log(data)
                                // let processedData = ConfigPage.processData(data);
                                // console.log(processedData)
                                // this.updateData(processedData);
                            } else if (error.data) {
                                // error.data.forEach(item => {


                                // });
                            }
                        } catch (e) {
                            console.error('POST request failed:', e);
                        }
                    } else {
                        console.error('POST request failed:', error);
                    }
                })

        });

        ConfigPage.table.on('draw', function () {
            if (ConfigPage.table.data().any()) {
                ConfigPage.TableHandler.addListener('config-table');
                $('div.toolbar').show();
                $('table#config-table').show();
                $(this).parent().show();
            } else {
                $('div.toolbar').hide();
                $('table#config-table').hide();
                $(this).parent().hide();
            }
            ConfigPage.TableHandler.deselectAll();
        });


    }

    static handleDeleteLabel(isDisabled, SELECTED_COUNT = 0) {

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

    static extractData(selected) {

        let data = [];
        selected.forEach((row) => {
            const dataContainer = row.querySelector('div.vote-rule-text');

            const guidelineId = dataContainer.id;
            let extractedNumber;

            if (typeof guidelineId === 'string' && guidelineId.startsWith('rule-')) {
                extractedNumber = parseInt(guidelineId.substring(5), 10);
            }

            let item = {
                sequence: dataContainer.getAttribute('data-seq'),
                guideline_id: extractedNumber,
                description: dataContainer.textContent
            }
            data.push(item);
        });

        return data;
    }

    static async handleDeleteBtn() {
        ConfigPage.DELETE_BUTTON.disabled = true;

        if (await
            ConfigPage.showConfirmModal(ConfigPage.ConfirmDeleteModal, ConfigPage.ConfirmModalInstance, 'confirmDeleteInput', 'Confirm Delete', true)
            == 'true') {
            const selectedData = document.querySelectorAll(`table tbody tr.selected`);
            const deleteData = this.extractData(selectedData);

            ConfigPage.postData(deleteData, 'DELETE')
                .then(function (result) {
                    try {
                        const { data, success, error } = result;

                        if (success) {
                            console.log(data)
                            let processedData = ConfigPage.processData(data);
                            console.log(processedData)
                            this.deleteEntry(processedData);
                        } else if (error.data) {
                            // error.data.forEach(item => {


                            // });
                        }
                    }
                    catch (e) {
                        console.error('POST request failed:', e);
                    }
                }.bind(this))
        }

    }

    // static deleteEntry(DATA) {

    //     if (DATA && DATA.data && Array.isArray(DATA.data)) {

    //         DATA.data.forEach(item => {
    //             const { data_id, input_id } = item;

    //             let INPUT_ELEMENT = document.getElementById(input_id);
    //             let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
    //             if (DATA_ROW) {
    //                 ConfigPage.table.row(DATA_ROW).remove().draw();
    //                 const SELECTED_COUNT = this.countSelectedRows();
    //                 this.updateToolbarButton(SELECTED_COUNT);
    //                 // this.deselectAll();

    //             } else {

    //                 console.error(`Input element with ID not found.`);
    //             }
    //         });
    //     } else {
    //         // console.error('Invalid or missing DATA structure.');
    //     }
    // }

    static deleteEntry(DATA, isdraw = false) {

        try {
            for (const item of DATA) {


                let rowId = document.getElementById(`rule-${item[1].data_id}`);

                let DATA_ROW = rowId.closest(`tr`);

                if (DATA_ROW) {
                    ConfigPage.table.row(DATA_ROW).remove().draw(isdraw);
                } else {

                    console.error(`Input element with ID not found.`);
                }

            }
        } catch (error) {
            console.log(error);
        }


    }

    static deselectAll() {
        const selectedRows = ConfigPage.TABLE_BODY.querySelectorAll('tr.selected');
        selectedRows.forEach(row => {
            row.classList.remove('selected');
        });
        const SELECTED_COUNT = this.countSelectedRows();
        this.updateToolbarButton(SELECTED_COUNT);
    }


    static insertData(DATA, isdraw = false) {

        try {
            for (const item of DATA) {

                ConfigPage.table.row.add(item).draw(isdraw);

            }
        } catch (error) {
            console.log(error);
        }


    }

    static updateData(DATA, isdraw = false) {

        try {
            for (const item of DATA) {

                let rowId = document.getElementById(`rule-${item[1].data_id}`);

                let DATA_ROW = rowId.closest(`tr`);

                if (DATA_ROW) {

                    // ConfigPage.table.row(DATA_ROW).data(rowData).draw(false);
                    ConfigPage.table.row(DATA_ROW).data(item).draw(isdraw);
                } else {

                    console.error(`Input element with ID not found.`);
                }

            }
        } catch (error) {
            console.log(error);
        }


    }

    static setData(TABLE_DATA, TABLE) {
        TABLE.clear();
        TABLE.rows.add(TABLE_DATA).draw(true);

    }
}

try {
    ConfigPage.table.destroy();
} catch (error) {
    console.warn(error);
}

ConfigPage.table = new DataTable('#config-table', {
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
            targets: 1, className: ``,
            render: function (data) {
                return `
                    <div class="vote-rule-text" id="rule-${data.data_id}" data-seq="${data.sequence}">
                        ${data.description}
                    </div>
                `;
            }
        },

        // {
        //     targets: 2, className: `d-none`,
        //     render: function (data) {
        //         const DATA = `${data}` !== undefined && `${data}` !== '' ? `${data}` : '';
        //         return `<div class="text-truncate">${DATA}</div>`;
        //     }
        // }
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
            toolbar.innerHTML = `<button class="add-new " id="add-new">
                                    Add New Rule
                                </button>`;

            return toolbar;
        }
    },
    scrollY: '4.5rem ',
    scrollCollapse: true,
    paging: false,
    initComplete: function (settings, json) {
        ConfigPage.addEventListenerAndStore(document.getElementById('add-new'), 'click', function () {
            let lastSequence = ConfigPage.FindLastSequence();
            let data = {
                sequence: ++lastSequence,
                guideline_id: `rule-${lastSequence}`,
                description: ''
            }
            ConfigPage.EditorModal.show(data, false, true);
        })


    }
});

ConfigPage.ConfirmDeleteModal = document.getElementById('delete-modal');
ConfigPage.ConfirmModalInstance = { instance: null };

ConfigPage.showConfirmModal = async function (modal, instanceRef, inputId = null, inputVal = null, isDisabled = false) {
    // https://stackoverflow.com/questions/65454144/javascript-await-bootstrap-modal-close-by-user
    instanceRef.instance = new bootstrap.Modal(modal);
    instanceRef.instance.show();

    if (isDisabled) {
        ConfigPage.handleConfirmInput(modal, inputId, inputVal);
    }

    modal.removeEventListener('hidden.bs.modal', ConfigPage.handleConfirmModalDispose)
    modal.addEventListener('hidden.bs.modal', ConfigPage.handleConfirmModalDispose)

    return new Promise(resolve => {

        $(modal).find('.prompt-action button').off('click');
        $(modal).find('.prompt-action button').on('click', (event) => {
            const buttonValue = event.currentTarget.value;


            if (isDisabled) {
                let inputElement = modal.querySelector(`#${inputId}`);
                inputElement.classList.remove('is-invalid');
                inputElement.value = '';
                $(modal).find('.prompt-action .btn-secondary.primary').prop('disabled', true)
                    .val('false')
            }

            instanceRef.instance.hide();
            resolve(buttonValue);
        });
    });
}

ConfigPage.handleConfirmModalDispose = function () {
    ConfigPage.ConfirmModalInstance.instance.dispose();
}

ConfigPage.handleConfirmInput = function (modal, inputId, inputVal) {
    let inputElement = modal.querySelector(`#${inputId}`);

    ConfigPage.delEventListener(inputElement, 'blur');
    ConfigPage.addEventListenerAndStore(inputElement, 'blur', function () {
        if (inputElement.value == inputVal) {
            $(modal).find('.prompt-action .btn-secondary.primary').prop('disabled', false)
                .val('true');
            inputElement.classList.remove('is-invalid');
        }
        else {
            $(modal).find('.prompt-action .btn-secondary.primary').prop('disabled', true)
                .val('false');
            inputElement.classList.add('is-invalid')
        }

    })

}

ConfigPage.FindLastSequence = function (table_id = 'config-table') {
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

ConfigPage.TABLE_BODY = document.querySelector(`#config-table tbody`);
ConfigPage.DELETE_BUTTON = document.getElementById('delete');
ConfigPage.DELETE_LABEL = document.getElementById('delete-label');
ConfigPage.TableHandler.initReorderListener();
ConfigPage.itemSequence;
ConfigPage.itemId;
ConfigPage.description;

ConfigPage.validateTextEditor = function (event) {
    const inputElement = event.target;
    const primaryBtn = document.getElementById('modal-action-primary');

    clearTimeout(ConfigPage.typingTimeout);
    ConfigPage.typingTimeout = setTimeout(() => {
        try {
            if (ConfigPage.vote_rule_validate.validate(inputElement)) {
                inputElement.classList.remove('is-invalid');
                primaryBtn.disabled = false;
            } else {

                inputElement.classList.add('is-invalid');
                primaryBtn.disabled = true;
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }, 300);
}

ConfigPage.EditorModal = class {
    static modalElement = document.querySelector('.modal:has(.modal-header.editor)');
    static data;
    static mode;
    static modalInstance;

    static show(data, isEdit, isAdd = false) {
        this.isEdit = isEdit;
        this.isAdd = isAdd;
        this.mode = 'view';

        if (this.isEdit) {
            this.mode = 'edit';
        } else if (this.isAdd) {
            this.mode = 'add';
        }

        this.#updateContent(data);

        if (this.modalElement) {

            this.modalInstance = new bootstrap.Modal(this.modalElement);
            this.modalInstance.show();

            ConfigPage.delEventListener(this.modalElement, 'close');
            ConfigPage.addEventListenerAndStore(this.modalElement, 'close', ConfigPage.TableHandler.deselectAll());

            this.modalElement.removeEventListener('hidden.bs.modal', event => {
                this.modalInstance.dispose();
                ConfigPage.TableHandler.deselectAll();
            }).bind(this)
            this.modalElement.addEventListener('hidden.bs.modal', event => {
                this.modalInstance.dispose();
                ConfigPage.TableHandler.deselectAll();
            }).bind(this)
        }
    }

    static #updateContent(data) {
        this.data = data;

        this.#setEditField();
        let modalActionDiv = this.#initBtn();
        let modalBody = this.modalElement.querySelector('.modal-body');
        modalBody.append(modalActionDiv);

        if (this.mode === 'view') {
            let editBtn = this.modalElement.querySelector('#modal-action-edit');
            ConfigPage.delEventListener(editBtn, 'click');
            ConfigPage.addEventListenerAndStore(editBtn, 'click', this.#toggleEditState.bind(this));
        } else {
            this.#toggleEditState();
        }


        let primaryBtn = this.modalElement.querySelector('label[for="modal-action-primary"]');

        let cancelBtn = this.modalElement.querySelector('.modal-body .cancel-btn');

        if (this.mode === 'view') {
            primaryBtn.classList.add('d-none');
            cancelBtn.classList.add('d-none');
        }


        let closeBtn = this.modalElement.querySelector('button.modal-close');
        ConfigPage.delEventListener(closeBtn, 'click');
        ConfigPage.addEventListenerAndStore(closeBtn, 'click', this.close.bind(this));

        let textarea = this.modalElement.querySelector('textarea');
        ConfigPage.delEventListener(textarea, 'input');
        ConfigPage.addEventListenerAndStore(textarea, 'input', ConfigPage.validateTextEditor);

    }

    static #setEditField() {
        this.#removeEditor();
        let prompTitle = this.modalElement.querySelector('h5.modal-title span.guideline-num');
        let modalBody = this.modalElement.querySelector('.modal-body');

        prompTitle.textContent = this.data.sequence;

        let descriptionTextArea = document.createElement('textarea');
        descriptionTextArea.classList.add('form-control');
        descriptionTextArea.required = true;
        const trimmedDescription = this.data.description.trim();
        descriptionTextArea.value = trimmedDescription;
        if (this.mode === 'view') {
            descriptionTextArea.readOnly = true;
            descriptionTextArea.disabled = true;
        }
        ConfigPage.itemSequence = this.data.sequence;

        const guidelineId = this.data.guideline_id;

        if (typeof guidelineId === 'string' && guidelineId.startsWith('rule-')) {
            const extractedNumber = parseInt(guidelineId.substring(5), 10);
            ConfigPage.itemId = extractedNumber;
        }


        modalBody.insertBefore(descriptionTextArea, modalBody.firstChild)

    }

    static #initBtn() {

        this.#removeBtn();

        const modalActionDiv = document.createElement('div');
        modalActionDiv.classList.add('modal-action', 'w-100');

        // Create the label and primary button
        const label = document.createElement('label');
        label.setAttribute('for', 'modal-action-primary');

        let editBtn;
        if (this.mode === 'view') {
            editBtn = document.createElement('button');
            editBtn.id = 'modal-action-edit';
            editBtn.type = 'button';
            editBtn.classList.add('btn', 'btn-sm', 'btn-outline-primary');
            editBtn.textContent = 'Edit';
        }

        const primaryButton = document.createElement('button');
        primaryButton.id = 'modal-action-primary';
        primaryButton.type = 'button';
        primaryButton.classList.add('btn', 'btn-sm', 'btn-primary');
        primaryButton.textContent = 'Save Changes';

        if (this.mode === 'add') {
            primaryButton.textContent = 'Add Rule';
        }

        label.appendChild(primaryButton);

        const cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.classList.add('btn', 'btn-sm', 'btn-secondary', 'cancel-btn');
        cancelButton.textContent = 'Cancel';

        // Append everything to the main div
        if (this.mode === 'view') {
            modalActionDiv.appendChild(editBtn);
        }

        modalActionDiv.appendChild(cancelButton);
        modalActionDiv.appendChild(label);

        return modalActionDiv;
    }

    static #toggleEditState() {

        if (this.mode !== 'add') {
            let editBtn = this.modalElement.querySelector('#modal-action-edit');
            editBtn.classList.add('d-none');
        }

        let descriptionTextArea = this.modalElement.querySelector('.modal-body textarea');
        descriptionTextArea.readOnly = false;
        descriptionTextArea.disabled = false;

        let primaryBtn = this.modalElement.querySelector('label[for="modal-action-primary"]');
        primaryBtn.classList.remove('d-none');
        ConfigPage.delEventListener(primaryBtn, 'click');
        ConfigPage.addEventListenerAndStore(primaryBtn, 'click', this.#handlePrimaryBtn.bind(this));

        let cancelBtn = this.modalElement.querySelector('.modal-body .cancel-btn');
        cancelBtn.classList.remove('d-none');
        ConfigPage.delEventListener(cancelBtn, 'click');
        ConfigPage.addEventListenerAndStore(cancelBtn, 'click', this.close.bind(this));
    }

    static #removeBtn() {
        let modalBody = this.modalElement.querySelector('.modal-body');
        let existingModalAction = modalBody.querySelector('.modal-action');
        if (existingModalAction) {
            modalBody.removeChild(existingModalAction);
        }
    }

    static #removeEditor() {
        let modalBody = this.modalElement.querySelector('.modal-body');
        let isExistTextEditor = modalBody.querySelector('textarea');
        if (isExistTextEditor) {
            modalBody.removeChild(isExistTextEditor);
        }
    }

    static #handlePrimaryBtn() {
        if (this.data) {
            let primaryButton = this.modalElement.querySelector('.modal-body #modal-action-primary');
            primaryButton.disabled = true;
            this.#handleSubmit();
        }
    }

    static #handleSubmit() {
        if (this.data) {
            this.#extractData();

            let data = [];
            let item = {
                guideline_id: ConfigPage.itemId,
                sequence: ConfigPage.itemSequence,
                description: ConfigPage.description,
            }

            data.push(item);

            let method = 'PUT';

            if (this.isAdd) {
                method = 'POST';
            }

            ConfigPage.postData(data, method).then(function (result) {

                try {
                    let { data, success, error } = result;
                    if (success) {
                        data = [data];

                        let processedData = ConfigPage.processData(data);
                        if (this.isAdd) {

                            ConfigPage.TableHandler.insertData(processedData);
                        } else {
                            ConfigPage.TableHandler.updateData(processedData);
                        }




                        ConfigPage.EditorModal.close();
                    }
                }
                catch (e) {
                    console.error('', e);
                }
            }.bind(this));
        }
    }

    static toggleSaveBtn() {
        let textarea = this.modalElement.querySelector('.modal-body textarea');
        let hasInvalidTextarea = textarea?.classList.matches('.is-invalid') && textarea?.classList.matches('.form-control');
        let saveBtn = this.modalElement.getElementById('modal-action-primary');

        if (hasInvalidTextarea) {
            saveBtn.disabled = true;
        } else {
            saveBtn.disabled = false;
        }
    }

    static close() {
        if (this.data) {

            ConfigPage.itemSequence = null;
            ConfigPage.itemId = null;
            ConfigPage.description = null;
            this.modalInstance.hide();
        }
    }

    static #extractData() {
        let modalBody = this.modalElement.querySelector('.modal-body');
        let isExistTextEditor = modalBody.querySelector('textarea');
        if (isExistTextEditor) {
            ConfigPage.description = isExistTextEditor.value;
        }

    }

}
