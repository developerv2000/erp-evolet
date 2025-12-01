/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { createElementFromHTML } from '../utilities';
import { initializeSelectizes, initializeSimditors } from "../plugins";
import { initializeSpecificFormatableInputs } from "../main-functions";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const similarRecordsWrapper = document.querySelector('.similar-records-wrapper');
const formDynamicRowsList = document.querySelector('.form__dynamic-rows-list');

// IVP
const GET_PRODUCTS_SIMILAR_RECORDS_POST_URL = '/mad/products/get-similar-records';
const GET_PRODUCTS_ATX_INPUTS_POST_URL = '/mad/products/get-atx-inputs';
const GET_PRODUCTS_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL = '/mad/products/get-dynamic-rows-list-item-inputs';

// VPS
const UPDATE_PROCESSES_CONTRACTED_IN_ASP_VALUE_POST_URL = '/mad/processes/update-contracted-in-asp-value';
const UPDATE_PROCESSES_REGISTERED_IN_ASP_VALUE_POST_URL = '/mad/processes/update-registered-in-asp-value';
const UPDATE_PROCESSES_READY_FOR_ORDER_STATUS_POST_URL = '/mad/processes/toggle-ready-for-order-status';
const GET_PROCESS_CREATE_STAGE_INPUTS_POST_URL = '/mad/processes/get-create-form-stage-inputs';
const GET_PROCESS_CREATE_FORECAST_INPUTS_POST_URL = '/mad/processes/get-create-form-forecast-inputs';
const GET_PROCESS_EDIT_STAGE_INPUTS_POST_URL = '/mad/processes/get-edit-form-stage-inputs';
const GET_PROCESS_DUPLICATE_STAGE_INPUTS_POST_URL = '/mad/processes/get-duplicate-form-stage-inputs';

// KVPP
const GET_PRODUCT_SEARCHES_SIMILAR_RECORDS_POST_URL = '/mad/product-searches/get-similar-records';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// IVP
const ATXInputsWrapper = document.querySelector('.atx-inputs-wrapper');

// VPS
const processesForecastInputsWrapper = document.querySelector('.mad-processes-create__forecast-inputs-wrapper');
const processesStageInputsWrapper = document.querySelector('.mad-processes-stage-inputs-wrapper');

// ASP
const ASPTable = document.querySelector('.mad-asp-table');

/*
|--------------------------------------------------------------------------
| Variables
|--------------------------------------------------------------------------
*/

let formDynamicInputsArrayIndex = 1; // Incrementable array index

/*
|--------------------------------------------------------------------------
| Export functions
|--------------------------------------------------------------------------
*/

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

export function displayProductsATXInputs() {
    // Get inn, and form ID values
    const innID = document.querySelector('select[name="inn_id"]').value;
    const formID = document.querySelector('select[name="form_id"]').value;

    // Return if any required fields are empty
    if (innID == '' || formID == '') {
        ATXInputsWrapper.innerHTML = '';
        return;
    }

    showSpinner();

    // Prepare data to be sent in the AJAX request
    const data = {
        'inn_id': innID,
        'form_id': formID,
    };

    // Send a POST request to the server to get atx inputs
    axios.post(GET_PRODUCTS_ATX_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            ATXInputsWrapper.innerHTML = response.data;
        })
        .finally(function () {
            hideSpinner();
        });
}

export function addDynamicRowsListItemOnProductsCreate() {
    showSpinner();

    const data = {
        'inputs_index': formDynamicInputsArrayIndex,
    };

    axios.post(GET_PRODUCTS_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const row = createElementFromHTML(response.data);
            formDynamicRowsList.appendChild(row);
            initializeSpecificFormatableInputs();
        })
        .finally(function () {
            hideSpinner();
            formDynamicInputsArrayIndex++;
        });
}

export function updateProcessContractedValue(evt) {
    showSpinner();

    const chb = evt.target;
    const processID = chb.dataset.processId;

    const data = {
        'contracted_in_asp': chb.checked,
        'process_id': processID,
    };

    axios.post(UPDATE_PROCESSES_CONTRACTED_IN_ASP_VALUE_POST_URL, data, {
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
        'registered_in_asp': chb.checked,
        'process_id': processID,
    };

    axios.post(UPDATE_PROCESSES_REGISTERED_IN_ASP_VALUE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .finally(function () {
            // Hide any loading spinner after the request is complete
            hideSpinner();
        });
}

export function toggleProcessReadyForOrderStatus(evt) {
    showSpinner();

    const chb = evt.target;
    const processID = chb.dataset.processId;

    const data = {
        'mark_as_ready_for_order': chb.checked,
        'process_id': processID,
    };

    axios.post(UPDATE_PROCESSES_READY_FOR_ORDER_STATUS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const success = response.data.success;
            const message = response.data.message;
            const isReadyForOrder = response.data.is_ready_for_order;

            chb.checked = isReadyForOrder;

            if (!success) {
                alert(message);
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
            initializeSelectizes();

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
            initializeSelectizes();

            // Initialize new simditors
            initializeSimditors();
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
            initializeSelectizes();
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

export function toggleASPTableCountryMAHs(event) {
    const toggler = event.target;
    const isOpened = toggler.dataset.opened === "true";

    // Toggle button 'opened'
    toggler.dataset.opened = !isOpened;
    toggler.textContent = isOpened ? 'visibility_off' : 'visibility';

    // Toggle MAH rowss visibility
    const countryCode = toggler.dataset.countryCode;
    const mahRows = ASPTable.querySelectorAll('tr[data-country-code="' + countryCode + '"]');
    mahRows.forEach(mahRow => {
        mahRow.style.display = isOpened ? 'none' : 'table-row';
    });
}

export function initializeTableAccordion() {
    // Load state from sessionStorage
    const hiddenGroups = JSON.parse(sessionStorage.getItem('hiddenGroups') || '[]');

    // Apply hidden state to saved groups
    hiddenGroups.forEach(groupId => {
        document.querySelectorAll('.group-' + groupId)
            .forEach(col => col.classList.add('hidden'));
    });

    document.querySelectorAll('.group-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const groupId = this.dataset.group;

            document.querySelectorAll('.group-' + groupId)
                .forEach(col => col.classList.toggle('hidden'));

            // Update state in sessionStorage
            const isHidden = document.querySelector('.group-' + groupId).classList.contains('hidden');

            let state = JSON.parse(sessionStorage.getItem('hiddenGroups') || '[]');

            if (isHidden) {
                if (!state.includes(groupId)) state.push(groupId);
            } else {
                state = state.filter(id => id !== groupId);
            }

            sessionStorage.setItem('hiddenGroups', JSON.stringify(state));
        });
    });
}
