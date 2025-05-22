/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { debounce } from "../utilities";
import { refreshSelectizeOptions } from "../utilities";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const GET_MANUFACTURERS_DEPENDENCIES_POST_URL = '/mad/manufacturers/get-smart-filter-dependencies';
const GET_PRODUCTS_DEPENDENCIES_POST_URL = '/mad/products/get-smart-filter-dependencies';
const GET_PROCESSES_DEPENDENCIES_POST_URL = '/mad/processes/get-smart-filter-dependencies';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Manufacturers filter
const manufacturersPage = document.querySelector('.mad-manufacturers-index');

if (manufacturersPage) {
    var analystSelect = manufacturersPage.querySelector('select[name="analyst_user_id"]').selectize;
    var countriesSelect = manufacturersPage.querySelector('select[name="country_id[]"]').selectize;
    var manufacturersSelect = manufacturersPage.querySelector('select[name="id[]"]').selectize;
}

// Products filter
const productsPage = document.querySelector('.mad-products-index');

if (productsPage) {
    var manufacturersSelect = productsPage.querySelector('select[name="manufacturer_id[]"]').selectize;
    var innsSelect = productsPage.querySelector('select[name="inn_id[]"]').selectize;
    var formsSelect = productsPage.querySelector('select[name="form_id[]"]').selectize;
    var dosageInput = productsPage.querySelector('input[name="dosage"]');
    var packInput = productsPage.querySelector('input[name="pack"]');
}

// Joined MAD processes & MAD Decision hub filters
const processesPage = document.querySelector('.mad-processes-index, .mad-decision-hub-index');

if (processesPage) {
    var manufacturersSelect = processesPage.querySelector('select[name="manufacturer_id[]"]').selectize;
    var innsSelect = processesPage.querySelector('select[name="inn_id[]"]').selectize;
    var formsSelect = processesPage.querySelector('select[name="form_id[]"]').selectize;
    var countriesSelect = processesPage.querySelector('select[name="country_id[]"]').selectize;
    var statusesSelect = processesPage.querySelector('select[name="status_id[]"]').selectize;
    var dosageInput = processesPage.querySelector('input[name="dosage"]');
}

/*
|--------------------------------------------------------------------------
| Asynchronous filter update functions
|--------------------------------------------------------------------------
*/

function updateManufacturersFilterInputs() {
    showSpinner();

    const data = {
        analyst_user_id: analystSelect.getValue(),
        country_id: countriesSelect.getValue().length ? countriesSelect.getValue() : null,
        id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
    };

    axios.post(GET_MANUFACTURERS_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { analystUsers, countriesOrderedByName, manufacturers } = response.data;

            refreshSelectizeOptions(analystSelect, analystUsers, updateManufacturersFilterInputs, 'name', 'id', false);
            refreshSelectizeOptions(countriesSelect, countriesOrderedByName, updateManufacturersFilterInputs);
            refreshSelectizeOptions(manufacturersSelect, manufacturers, updateManufacturersFilterInputs);
        })
        .finally(hideSpinner);
}

function updateProductsFilterInputs() {
    showSpinner();

    const data = {
        manufacturer_id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
        inn_id: innsSelect.getValue().length ? innsSelect.getValue() : null,
        form_id: formsSelect.getValue().length ? formsSelect.getValue() : null,
        dosage: dosageInput.value,
        pack: packInput.value,
    };

    axios.post(GET_PRODUCTS_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { manufacturers, inns, productForms } = response.data;

            refreshSelectizeOptions(manufacturersSelect, manufacturers, updateProductsFilterInputs);
            refreshSelectizeOptions(innsSelect, inns, updateProductsFilterInputs);
            refreshSelectizeOptions(formsSelect, productForms, updateProductsFilterInputs);
        })
        .finally(hideSpinner);
}

/**
 * Joined MAD VPS & MAD DH !!!
 */
function updateProcessesFilterInputs() {
    showSpinner();

    const data = {
        manufacturer_id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
        inn_id: innsSelect.getValue().length ? innsSelect.getValue() : null,
        form_id: formsSelect.getValue().length ? formsSelect.getValue() : null,
        country_id: countriesSelect.getValue().length ? countriesSelect.getValue() : null,
        status_id: statusesSelect.getValue().length ? statusesSelect.getValue() : null,
        dosage: dosageInput.value,
    };

    axios.post(GET_PROCESSES_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { manufacturers, inns, productForms, countriesOrderedByProcessesCount, statuses } = response.data;

            refreshSelectizeOptions(manufacturersSelect, manufacturers, updateProcessesFilterInputs);
            refreshSelectizeOptions(innsSelect, inns, updateProcessesFilterInputs);
            refreshSelectizeOptions(formsSelect, productForms, updateProcessesFilterInputs);
            refreshSelectizeOptions(countriesSelect, countriesOrderedByProcessesCount, updateProcessesFilterInputs, 'code');
            refreshSelectizeOptions(statusesSelect, statuses, updateProcessesFilterInputs);
        })
        .finally(hideSpinner);
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeManufacturersFilter() {
    if (!manufacturersPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [analystSelect, countriesSelect, manufacturersSelect];

    for (const select of selects) {
        select.on('change', updateManufacturersFilterInputs);
    }
}

function initializeProductsFilter() {
    if (!productsPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [manufacturersSelect, innsSelect, formsSelect];

    for (const select of selects) {
        select.on('change', updateProductsFilterInputs);
    }

    // Attach change event listeners to required smart inputs
    const inputs = [dosageInput, packInput];

    for (const input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateProductsFilterInputs();
        }, 1500));
    }
}

/**
 * Joined MAD VPS & MAD DH
 */
function initializeProcessesFilter() {
    if (!processesPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [manufacturersSelect, innsSelect, formsSelect, countriesSelect, statusesSelect];

    for (const select of selects) {
        select.on('change', updateProcessesFilterInputs);
    }

    // Attach change event listeners to required smart inputs
    const inputs = [dosageInput];

    for (const input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateProcessesFilterInputs();
        }, 1500));
    }
}

export default function initializeAll() {
    initializeManufacturersFilter();
    initializeProductsFilter();
    initializeProcessesFilter(); // Joined MAD VPS & MAD DH
}
