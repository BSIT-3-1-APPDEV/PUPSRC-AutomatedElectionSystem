import { initializeConfigurationJS as ConfigJS } from './configuration.js';


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
        console.log('clicked')
        const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');
        if (INPUT_FOCUSED.length > 0) {
            return;
        }

        event.currentTarget.classList.toggle('selected');

        const SELECTED_COUNT = ConfigPage.TableHandler.countSelectedRows();

        ConfigPage.TableHandler.updateToolbarButton(SELECTED_COUNT);
    },

    handleTableRowDblClick: function (event) {
        console.log('dbl click event ');
        console.log(event);
        // const INPUT_FOCUSED = event.currentTarget.querySelectorAll('input:focus-visible');

        try {
            const dataContainer = event.currentTarget.querySelector('div.vote-rule-text');
            console.log(dataContainer);

            let data = {
                sequence: dataContainer.getAttribute('data-seq'),
                guideline_id: dataContainer.id,
                description: dataContainer.textContent
            }

            console.log(data);

            ConfigPage.EditorModal.show(data, false);

        } catch (error) {

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



try {
    ConfigPage.fetchSchedule({ csrf: ConfigPage.CSRF_TOKEN });
} catch (error) {
    console.warn(error);
}



ConfigPage.processData = function (data) {
    const TABLE_DATA = [];

    for (const key in data) {
        const item = data[key];

        // Check if the current item is an object
        if (typeof item === 'object' && !Array.isArray(item)) {
            // Create a new table item with properties extracted from the nested object
            const tableItem = {
                0: item.sequence,
                1: {
                    data_id: item.guideline_id || null, // Set default value for missing data_id
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
    console.log(post_data);
    let json_data = JSON.stringify(post_data);

    console.log(method, post_data, ' stringtified ', JSON.stringify(post_data));

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
                ConfigPage.addEventListenerAndStore(ConfigPage.DELETE_BUTTON, 'click', ConfigPage.handleDeleteBtn);
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
            let data = {
                'update_sequence': []
            };
            // let result = 'Reorder started on row: ' +  + '<br>';
            for (var i = 0, ien = diff.length; i < ien; i++) {
                let rowData = ConfigPage.table.row(diff[i].node).data();
                let data_id = rowData[1].data_id;
                if (!data_id) { continue }
                let new_sequence = diff[i].newData;


                console.log('diff ' + JSON.stringify($(diff[i].node)));

            }

            console.log('data sequence' + JSON.stringify(data));

            // ConfigPage.postData(data)
            //     .then(function (result) {
            //         const { data, success, error } = result;

            //         if (success) {
            //             // ConfigPage.updatePostion(data);
            //         } else {
            //             console.error('POST request failed:', error);
            //         }
            //     })

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

    static handleDeleteBtn() {
        ConfigPage.DELETE_BUTTON.disabled = true;
        const selectedData = document.querySelectorAll(`table tbody tr.selected`);

        let deleteData = {
            'delete_data': []
        };

        const DATA = ConfigPage.extractData(selectedData, false);

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


                        });
                    }
                }
                catch (e) {
                    console.error('POST request failed:', e);
                }
            })
    }

    static deleteEntry(DATA) {

        if (DATA && DATA.data && Array.isArray(DATA.data)) {

            DATA.data.forEach(item => {
                const { data_id, input_id } = item;

                let INPUT_ELEMENT = document.getElementById(input_id);
                let DATA_ROW = INPUT_ELEMENT.closest(`tr`);
                if (DATA_ROW) {
                    ConfigPage.table.row(DATA_ROW).remove().draw();
                    const SELECTED_COUNT = this.countSelectedRows();
                    this.updateToolbarButton(SELECTED_COUNT);
                    // this.deselectAll();

                } else {

                    console.error(`Input element with ID not found.`);
                }
            });
        } else {
            // console.error('Invalid or missing DATA structure.');
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


    static insertData(DATA, draw = false) {

        if (DATA && DATA.data && Array.isArray(DATA.data)) {
            DATA.data.forEach(item => {

                let { sequence, data_id, input_id, description } = item;
                if (!draw) {
                    let rowData = {
                        0: sequence,
                        1: {
                            data_id: data_id,
                            sequence: sequence,
                            description: description,
                        }
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

{/* <tr class=\"\">
<td>" . $i + 2 . "</td>
<td>
 Voting Rule " . $i + 1 . "
</td>
</tr> */}

// 0: sequence,
// 1: {
//     data_id: data_id,
//     sequence: sequence,
//     description: description,
// }

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


ConfigPage.FindLastSequence = function (table_id = 'config-table') {
    try {
        const TABLE = document.querySelector(`table#${table_id}`);

        if (!TABLE) {
            throw new Error(`Table with id '${table_id}' not found.`);
        }

        const LAST_ROW = TABLE.querySelector('tbody tr:last-child');


        if (!LAST_ROW) {
            return 1;
            // throw new Error(`No last row found in table '${table_id}'.`);
        } else {
            console.log('last row ' + LAST_ROW.outerHTML);
        }

        const LAST_SEQUENCE_SPAN = LAST_ROW.querySelector('.dt-type-numeric > span.d-none:first-child');

        if (!LAST_SEQUENCE_SPAN) {
            return 1;
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

        console.log(isAdd)
        console.log(this.modalElement)
        this.#updateContent(data);

        if (this.modalElement) {
            console.log('showing')
            this.modalInstance = new bootstrap.Modal(this.modalElement);
            this.modalInstance.show();

            ConfigPage.delEventListener(this.modalElement, 'close');
            ConfigPage.addEventListenerAndStore(this.modalElement, 'close', ConfigPage.TableHandler.deselectAll());

            this.modalElement.removeEventListener('hidden.bs.modal', event => {
                this.modalInstance.dispose();
                ConfigPage.TableHandler.deselectAll();
            })
            this.modalElement.addEventListener('hidden.bs.modal', event => {
                this.modalInstance.dispose();
                ConfigPage.TableHandler.deselectAll();
            })
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
    }

    static #setEditField() {
        this.#removeEditor();
        let prompTitle = this.modalElement.querySelector('h5.modal-title span.guideline-num');
        let modalBody = this.modalElement.querySelector('.modal-body');

        prompTitle.textContent = this.data.sequence;

        let descriptionTextArea = document.createElement('textarea');
        const trimmedDescription = this.data.description.trim();
        descriptionTextArea.value = trimmedDescription;
        if (this.mode === 'view') {
            descriptionTextArea.readOnly = true;
            descriptionTextArea.disabled = true;
        }
        ConfigPage.itemSequence = this.data.sequence;

        const guidelineId = this.data.guideline_id;

        if (typeof guidelineId === 'string' && guidelineId.startsWith('rule-')) {
            const extractedNumber = parseInt(guidelineId.substring(5), 10); // Remove "rule-" (length 5)
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
            this.#handleSubmit().bind(this);
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
                console.log(result);
                try {
                    const { data, success, error } = result;
                    if (success) {
                        this.close();
                    }
                }
                catch (e) {
                    console.error('', e);
                }
            });
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
