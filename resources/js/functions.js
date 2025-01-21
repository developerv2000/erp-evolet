/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showModal, showSpinner } from "../custom-components/script";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Global
const TOGGLE_LEFTBAR_PATCH_URL = '/settings/collapsed-leftbar';

// IVP
const GET_PRODUCTS_SIMILAR_RECORDS_POST_URL = '/products/get-similar-records'

// VPS
const UPDATE_PROCESSES_CONTRACTED_VALUE_POST_URL = '/processes/update-contracted-value';
const UPDATE_PROCESSES_REGISTERED_VALUE_POST_URL = '/processes/update-registered-value';
const GET_PROCESS_CREATE_STAGE_INPUTS_POST_URL = '/processes/get-create-form-stage-inputs';
const GET_PROCESS_CREATE_FORECAST_INPUTS_POST_URL = '/processes/get-create-form-forecast-inputs';
const GET_PROCESS_EDIT_STAGE_INPUTS_POST_URL = '/processes/get-edit-form-stage-inputs';
const GET_PROCESS_DUPLICATE_STAGE_INPUTS_POST_URL = '/processes/get-duplicate-form-stage-inputs';

// KVPP
const GET_PRODUCT_SEARCHES_SIMILAR_RECORDS_POST_URL = '/product-searches/get-similar-records'

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const leftbar = document.querySelector('.leftbar');
const targetDeleteModal = document.querySelector('.target-delete-modal');
const targetRestoreModal = document.querySelector('.target-restore-modal');
const similarRecordsWrapper = document.querySelector('.similar-records-wrapper');

// VPS
const processesForecastInputsWrapper = document.querySelector('.processes-create__forecast-inputs-wrapper');
const processesStageInputsWrapper = document.querySelector('.processes-stage-inputs-wrapper');

/*
|--------------------------------------------------------------------------
| Export functions
|--------------------------------------------------------------------------
*/

export function toggleTextMaxLines(target) {
    target.closest('[data-on-click="toggle-td-text-max-lines"]').classList.toggle('td__max-lines-limited-text');
}

export function toggleLeftbar() {
    axios.patch(TOGGLE_LEFTBAR_PATCH_URL)
        .finally(() => {
            leftbar.classList.toggle('leftbar--collapsed');
        });
}

export function toggleTableCheckboxes(table) {
    const checkboxes = table.querySelectorAll('tr td:first-child .td__checkbox');
    const checkedAll = table.querySelector('tr td:first-child .td__checkbox:not(:checked)') ? false : true;

    checkboxes.forEach((checkbox) => {
        checkbox.checked = !checkedAll;
    });
}

function exitFullscreen(target) {
    target.classList.remove('fullscreen');
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
};

export function enterFullscreen(target) {
    if (target.requestFullscreen) {
        target.requestFullscreen();
    } else if (target.webkitRequestFullscreen) {
        target.webkitRequestFullscreen();
    } else if (target.msRequestFullscreen) {
        target.msRequestFullscreen();
    }
};

export function toggleFullscreenClass(target) {
    if (document.fullscreenElement) {
        target.classList.add('fullscreen');
    } else {
        target.classList.remove('fullscreen');
    }
};

export function appendFormInputsBeforeSubmit(evt) {
    evt.preventDefault();
    const form = evt.target;
    const inputs = document.querySelectorAll(form.dataset.inputsSelector);

    // Append each input to the form
    const inputsContainer = form.querySelector('.form__hidden-appended-inputs-container');

    inputs.forEach((input) => {
        const inputCopy = input.cloneNode(true);
        inputsContainer.appendChild(inputCopy);
    });

    form.submit();
}

export function showTargetDeleteModal(button) {
    setupTargetedModalForSubmit(targetDeleteModal, button);
    showModal(targetDeleteModal);
}

export function showTargetRestoreModal(button) {
    setupTargetedModalForSubmit(targetRestoreModal, button);
    showModal(targetRestoreModal);
}

/**
 * Setup targeted modal before show for submit, on action button click
 */
function setupTargetedModalForSubmit(modal, button) {
    const form = modal.querySelector('form');
    const idInput = modal.querySelector('input[name="id"]');

    idInput.value = button.dataset.targetId;
    form.action = button.dataset.formAction;
}

export function handleFilterFormSubmit(evt) {
    const form = evt.target;

    // Remove empty inputs
    form.querySelectorAll('input, select.single-selectize').forEach((input) => {
        if (!input.value) {
            input.remove();
        }
    });
}

export function moveFilterActiveInputsToTop(form) {
    if (!form) return;

    const inputs = form.querySelectorAll('input.input--highlight, select.single-selectize--highlight, select.multiple-selectize--highlight');
    // Reverse elements to keep same highlighted inputs order
    const reversedInputs = Array.from(inputs).reverse();

    reversedInputs.forEach((input) => {
        const formGroup = input.closest('.form-group');
        form.insertBefore(formGroup, form.firstChild);
    });
}

export function handleUpdateNestedsetSubmit(evt) {
    showSpinner();
    const button = evt.currentTarget;
    const action = button.dataset.formAction;

    const params = {
        record_hierarchy: $('.nested').nestedSortable('toHierarchy', { startDepthCount: 0 }),
        records_array: $('.nested').nestedSortable('toArray', { startDepthCount: 0 })
    }

    axios.post(action, params)
        .then(() => {
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            hideSpinner();
        });
}

export function displayLocalImage(evt) {
    const input = evt.target;
    const file = input.files[0];
    const image = input.nextElementSibling;

    if (file) {
        image.src = URL.createObjectURL(file);
    }
}

export function disableExportAsExcelFormSubmitButton(evt) {
    const form = evt.target;
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
}

/**
 * Increase/decrease table columns form trackbar width, on width input update
 */
export function handleTableColumnWidthInputUpdate(evt) {
    const sortableItem = evt.target.closest('.sortable-columns__item');
    const trackbar = sortableItem.querySelector('.sortable-columns__width-trackbar');
    trackbar.style.width = evt.target.value + 'px';
}

export function handleEditTableColumnsSubmit(evt) {
    evt.preventDefault();
    showSpinner();

    const form = evt.target;
    const action = form.action;
    const columns = Array.from(form.querySelectorAll('.sortable-columns__item'));
    const sortedColumns = columns.map(mapSortableTableColumnsData)

    axios.patch(action, { columns: sortedColumns }, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(() => {
            window.location.reload();
        })
        .finally(hideSpinner);
}

export function displayProductsSimilarRecords() {
    // Get manufacturer, inn, and form ID values
    const manufacturerID = document.querySelector('select[name="manufacturer_id"]').value;
    const innID = document.querySelector('select[name="inn_id"]').value;
    const formID = document.querySelector('select[name="form_id"]').value;

    // Return if any required fields are empty
    if (manufacturerID == '' || innID == '' || formID == '') {
        similarRecordsWrapper.innerHTML = '';
        return;
    }

    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'manufacturer_id': manufacturerID,
        'inn_id': innID,
        'form_id': formID,
    };

    // Send a POST request to the server to get similar records
    axios.post(GET_PRODUCTS_SIMILAR_RECORDS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            similarRecordsWrapper.innerHTML = response.data;
        })
        .finally(function () {
            hideSpinner();
        });
}

/**
 * Validate specific input ('dosage', 'pack', 'INN', etc) values.
 */
export function validateSpecificFormatableInput(evt) {
    const target = evt.target;

    target.value = target.value
        // Add spaces before and after '*', '+', '%' and '/' symbols
        .replace(/([+%/*])/g, ' $1 ')
        // Replace consecutive whitespaces with a single space
        .replace(/\s+/g, ' ')
        // Separate letters from numbers
        .replace(/(\d+)([a-zA-Z]+)/g, '$1 $2')
        .replace(/([a-zA-Z]+)(\d+)/g, '$1 $2')
        // Remove non-English characters
        .replace(/[^a-zA-Z0-9\s!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '')
        // Remove inner whitespaces
        .replace(/\s+(?=\S)/g, ' ')
        // Replace symbols ',' with '.'
        .replace(/,/g, '.')
        // Convert the entire string to uppercase
        .toUpperCase();
}

export function updateProcessContractedValue(evt) {
    showSpinner();

    const chb = evt.target;
    const processID = chb.dataset.processId;

    const data = {
        'contracted': chb.checked,
        'process_id': processID,
    };

    axios.post(UPDATE_PROCESSES_CONTRACTED_VALUE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .finally(function () {
            // Hide any loading spinner after the request is complete
            hideSpinner();
        });
}

export function updateProcessRegisteredValue(evt) {
    showSpinner();

    const chb = evt.target;
    const processID = chb.dataset.processId;

    const data = {
        'registered': chb.checked,
        'process_id': processID,
    };

    axios.post(UPDATE_PROCESSES_REGISTERED_VALUE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .finally(function () {
            // Hide any loading spinner after the request is complete
            hideSpinner();
        });
}

export function updateProcessCreateStageInputs(status_id) {
    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'product_id': document.querySelector('input[name="product_id"]').value,
        'status_id': status_id,
    }

    // Send a POST request to the server to get updated stage inputs
    axios.post(GET_PROCESS_CREATE_STAGE_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Replace old inputs with the new ones received from the server
            processesStageInputsWrapper.innerHTML = response.data;

            // Initialize new unselectized selects
            initializeUnselectizedSelects();

            // Refresh forecast inputs
            const countriesSelect = document.querySelector('select[name="country_ids[]"]');
            const selectedCountryIDs = countriesSelect.selectize.getValue();
            updateProcessCreateForecastInputs(selectedCountryIDs);
        })
        .finally(function () {
            hideSpinner();
        });
}

export function updateProcessCreateForecastInputs(country_ids) {
    // Return if status_id not selected yet
    const statusID = document.querySelector('select[name="status_id"]').value;

    if (!statusID) {
        return;
    }

    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'country_ids': country_ids,
        'status_id': statusID,
    }

    // Send a POST request to the server to get updated forecast inputs
    axios.post(GET_PROCESS_CREATE_FORECAST_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Replace old inputs with the new ones received from the server
            processesForecastInputsWrapper.innerHTML = response.data;
        })
        .finally(function () {
            hideSpinner();
        });
}

export function updateProcessEditStageInputs(status_id) {
    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'process_id': document.querySelector('input[name="process_id"]').value,
        'status_id': status_id,
    }

    // Send a POST request to the server to get updated stage inputs
    axios.post(GET_PROCESS_EDIT_STAGE_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Replace old inputs with the new ones received from the server
            processesStageInputsWrapper.innerHTML = response.data;

            // Initialize new unselectized selects
            initializeUnselectizedSelects();
        })
        .finally(function () {
            hideSpinner();
        });
}

export function updateProcessDuplicateStageInputs(status_id) {
    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'process_id': document.querySelector('input[name="process_id"]').value,
        'status_id': status_id,
    }

    // Send a POST request to the server to get updated stage inputs
    axios.post(GET_PROCESS_DUPLICATE_STAGE_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Replace old inputs with the new ones received from the server
            processesStageInputsWrapper.innerHTML = response.data;

            // Initialize new unselectized selects
            initializeUnselectizedSelects();
        })
        .finally(function () {
            hideSpinner();
        });
}

export function displayProductSearchesSimilarRecords() {
    // Get manufacturer, inn, and form ID values
    const countryID = document.querySelector('select[name="country_id"]').value;
    const innID = document.querySelector('select[name="inn_id"]').value;
    const formID = document.querySelector('select[name="form_id"]').value;
    const dosage = document.querySelector('input[name="dosage"]').value;
    const pack = document.querySelector('input[name="pack"]').value;

    // Return if any required fields are empty
    if (countryID == '' || innID == '' || formID == '') {
        similarRecordsWrapper.innerHTML = '';
        return;
    }

    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'country_id': countryID,
        'inn_id': innID,
        'form_id': formID,
        'dosage': dosage,
        'pack': pack,
    };

    // Send a POST request to the server to get similar records
    axios.post(GET_PRODUCT_SEARCHES_SIMILAR_RECORDS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            similarRecordsWrapper.innerHTML = response.data;
        })
        .finally(function () {
            hideSpinner();
        });
}

/*
|--------------------------------------------------------------------------
| Private functions
|--------------------------------------------------------------------------
*/

function initializeUnselectizedSelects() {
    const SELECTIZE_CLASSES = {
        SINGLE_UNLINKED: 'select.single-selectize:not(.single-selectize--linked):not(.single-selectize--manually-initializable):not(.selectized)',
        SINGLE_LINKED: 'select.single-selectize--linked:not(.single-selectize--manually-initializable):not(.selectized)',
        MULTIPLE_UNTAGGABLE: 'select.multiple-selectize:not(.multiple-selectize--taggable):not(.multiple-selectize--manually-initializable):not(.selectized)',
        MULTIPLE_TAGGABLE: 'select.multiple-selectize--taggable:not(.multiple-selectize--manually-initializable):not(.selectized)',
    };

    // Single unlinked selectize
    $(SELECTIZE_CLASSES.SINGLE_UNLINKED).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
    });

    // Single linked selectize
    $(SELECTIZE_CLASSES.SINGLE_LINKED).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
        onChange(value) {
            window.location = value;
        },
    });

    // Multiple untaggable selectize
    $(SELECTIZE_CLASSES.MULTIPLE_UNTAGGABLE).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
    });

    // Multiple Taggable Selectize
    $(SELECTIZE_CLASSES.MULTIPLE_TAGGABLE).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
        create(input, callback) {
            callback({
                value: input,
                text: input,
            });
        },
        // createOnBlur: true,
    });
}

function mapSortableTableColumnsData(item, index) {
    const column = {};
    column.name = item.dataset.columnName;
    column.order = index + 1;
    column.width = parseInt(item.querySelector('.sortable-columns__width-input').value);
    column.visible = item.querySelector('.switch').checked ? 1 : 0;
    return column;
}
