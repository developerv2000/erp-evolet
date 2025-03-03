/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../custom-components/script";
import { debounce } from "./utilities";
import { SELECTIZE_DEFAULT_OPTIONS } from "./plugins";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const GET_MAD_PRODUCTS_DEPENDENCIES_POST_URL = '/products/get-smart-filter-dependencies';
const GET_MAD_PROCESSES_DEPENDENCIES_POST_URL = '/processes/get-smart-filter-dependencies';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// MAD products filter
const productsPage = document.querySelector('.products-index');

if (productsPage) {
    var manufacturersSelect = productsPage.querySelector('select[name="manufacturer_id[]"]').selectize;
    var innsSelect = productsPage.querySelector('select[name="inn_id[]"]').selectize;
    var formsSelect = productsPage.querySelector('select[name="form_id[]"]').selectize;
    var dosageInput = productsPage.querySelector('input[name="dosage"]');
    var packInput = productsPage.querySelector('input[name="pack"]');
}

// MAD processes filter
const processesPage = document.querySelector('.processes-index');

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

function updateMadProductsFilterInputs() {
    showSpinner();

    const data = {
        manufacturer_id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
        inn_id: innsSelect.getValue().length ? innsSelect.getValue() : null,
        form_id: formsSelect.getValue().length ? formsSelect.getValue() : null,
        dosage: dosageInput.value,
        pack: packInput.value,
    };

    axios.post(GET_MAD_PRODUCTS_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { manufacturers, inns, productForms } = response.data;

            updateSelectize(manufacturersSelect, manufacturers);
            updateSelectize(innsSelect, inns);
            updateSelectize(formsSelect, productForms);
        })
        .finally(hideSpinner);
}

function updateMadProcessesFilterInputs() {
    showSpinner();

    const data = {
        manufacturer_id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
        inn_id: innsSelect.getValue().length ? innsSelect.getValue() : null,
        form_id: formsSelect.getValue().length ? formsSelect.getValue() : null,
        country_id: countriesSelect.getValue().length ? countriesSelect.getValue() : null,
        status_id: statusesSelect.getValue().length ? statusesSelect.getValue() : null,
        dosage: dosageInput.value,
    };

    axios.post(GET_MAD_PROCESSES_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { manufacturers, inns, productForms, countriesOrderedByProcessesCount, statuses } = response.data;

            updateSelectize(manufacturersSelect, manufacturers);
            updateSelectize(innsSelect, inns);
            updateSelectize(formsSelect, productForms);
            updateSelectize(countriesSelect, countriesOrderedByProcessesCount, 'code');
            updateSelectize(statusesSelect, statuses);
        })
        .finally(hideSpinner);
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function updateSelectize(selectize, itemsObject, labelField = 'name', valueField = 'id') {
    const items = Object.values(itemsObject); // Convert object to array
    const currentValues = selectize.getValue();

    // Unbind change event temporarily
    selectize.off('change');

    selectize.clearOptions();

    items.forEach(item => {
        selectize.addOption({
            [SELECTIZE_DEFAULT_OPTIONS.valueField]: item[valueField],
            [SELECTIZE_DEFAULT_OPTIONS.labelField]: item[labelField]
        });
    });

    const validValues = currentValues.filter(value => items.some(item => item[valueField] == value));
    selectize.setValue(validValues, true); // true = avoid triggering 'change' event

    // Rebind change event
    selectize.on('change', () => updateMadProcessesFilterInputs());
};

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeMadProductsFilter() {
    if (!productsPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [manufacturersSelect, innsSelect, formsSelect];

    for (const select of selects) {
        select.on('change', () => updateMadProductsFilterInputs());
    }

    // Attach change event listeners to required smart inputs
    const inputs = [dosageInput, packInput];

    for (const input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateMadProductsFilterInputs();
        }, 1500));
    }
}

function initializeMadProcessesFilter() {
    if (!processesPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [manufacturersSelect, innsSelect, formsSelect, countriesSelect, statusesSelect];

    for (const select of selects) {
        select.on('change', () => updateMadProcessesFilterInputs());
    }

    // Attach change event listeners to required smart inputs
    const inputs = [dosageInput];

    for (const input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateMadProcessesFilterInputs();
        }, 1500));
    }
}

export default function initializeAll() {
    initializeMadProductsFilter();
    initializeMadProcessesFilter();
}
