/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './MAD-functions';
import { debounce } from '../utilities';
import initializeSmartFilters from './MAD-smart-filters';
import './MAD-charts'; // initialize echarts
import './OSS-main';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// IVP forms
const productsCreateForm = document.querySelector('.mad-products-create-form');
const productsEditForm = document.querySelector('.mad-products-edit-form');

// KVPP forms
const productSearchesCreateForm = document.querySelector('.mad-product-searches-create-form');

// VPS forms
const processesCreateForm = document.querySelector('.mad-processes-create-form');
const processesEditForm = document.querySelector('.mad-processes-edit-form');
const processesDuplicateForm = document.querySelector('.mad-processes-duplicate-form');

// VPS checkboxes
const toggleProcessContractedValueChbs = document.querySelectorAll('[data-on-toggle="toggle-mad-process-contracted-in-asp-boolean"]');
const toggleProcessRegisteredValueChbs = document.querySelectorAll('[data-on-toggle="toggle-mad-process-registered-in-asp-boolean"]');
const toggleProcessReadyForOrderStatusChbs = document.querySelectorAll('[data-on-toggle="toggle-mad-process-ready-for-order-status"]');

// ASP table
const ASPTableCountryMAHsToggler = document.querySelectorAll('.mad-asp-table__tbody-country-mahs-toggler');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

toggleProcessContractedValueChbs.forEach((cbexkbox) => {
    cbexkbox.addEventListener('change', (evt) => functions.updateProcessContractedValue(evt));
});

toggleProcessRegisteredValueChbs.forEach((cbexkbox) => {
    cbexkbox.addEventListener('change', (evt) => functions.updateProcessRegisteredValue(evt));
});

toggleProcessReadyForOrderStatusChbs.forEach((cbexkbox) => {
    cbexkbox.addEventListener('change', (evt) => functions.toggleProcessReadyForOrderStatus(evt));
});

ASPTableCountryMAHsToggler.forEach((toggler) => {
    toggler.addEventListener('click', (evt) => functions.toggleASPTableCountryMAHs(evt));
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeProductsCreateForm() {
    if (!productsCreateForm) {
        return;
    }

    // Attach change event listeners to dropdowns, to display similar records & atx inputs
    const singleFunctionalSelects = productsCreateForm.querySelectorAll('select[name="manufacturer_id"]'); // only similar products
    const multipleFunctionalSelects = productsCreateForm.querySelectorAll('select[name="inn_id"], select[name="form_id"]'); // similar products & atx

    for (const select of singleFunctionalSelects) {
        select.selectize.on('change', () => functions.displayProductsSimilarRecords());
    }

    for (const select of multipleFunctionalSelects) {
        select.selectize.on('change', () => {
            functions.displayProductsSimilarRecords();
            functions.displayProductsATXInputs();
        });
    }

    // Attach click event listener to dynamic rows list add item button
    const addRowButton = productsCreateForm.querySelector('.form__dynamic-rows-list-add-item-button');
    addRowButton.addEventListener('click', () => functions.addDynamicRowsListItemOnProductsCreate());
}

function initializeProductsEditForm() {
    if (!productsEditForm) {
        return;
    }

    // Attach change event listeners to dropdowns, to display atx inputs
    const selects = productsEditForm.querySelectorAll('select[name="inn_id"], select[name="form_id"]');

    for (const select of selects) {
        select.selectize.on('change', () => functions.displayProductsATXInputs());
    }
}

function initializeProcessesCreateForm() {
    if (!processesCreateForm) {
        return;
    }

    // Select the dropdowns for status and countries
    const statusSelect = processesCreateForm.querySelector('select[name="status_id"]');
    const countriesSelect = processesCreateForm.querySelector('select[name="country_ids[]"]');

    // Attach change event listeners to selects
    statusSelect.selectize.on('change', (value) => functions.updateProcessCreateStageInputs(value));
    countriesSelect.selectize.on('change', (values) => functions.updateProcessCreateForecastInputs(values));
}

function initializeProcessesEditForm() {
    if (!processesEditForm) {
        return;
    }

    // Select the status dropdown and attach event listener
    const statusSelect = processesEditForm.querySelector('select[name="status_id"]');
    statusSelect.selectize.on('change', (value) => functions.updateProcessEditStageInputs(value));
}

function initializeProcessesDuplicateForm() {
    if (!processesDuplicateForm) {
        return;
    }

    // Select the status dropdown and attach event listener
    const statusSelect = processesDuplicateForm.querySelector('select[name="status_id"]');
    statusSelect.selectize.on('change', (value) => functions.updateProcessDuplicateStageInputs(value));
}

function initializeProductSearchesCreateForm() {
    if (!productSearchesCreateForm) {
        return;
    }

    // Select the dropdowns for country, inn, and form
    const selects = productSearchesCreateForm.querySelectorAll('select[name="country_id"], select[name="inn_id"], select[name="form_id"]');

    // Attach change event listeners to all select dropdowns
    for (const select of selects) {
        select.selectize.on('change', (value) => functions.displayProductSearchesSimilarRecords());
    }

    // Select inputs for dosage and pack
    const inputs = productSearchesCreateForm.querySelectorAll('input[name="dosage"], input[name="pack"]');

    // Attach change event listeners to all inputs
    for (let input of inputs) {
        // IMPORTANT: Delay 1000 is used because input values are also formatted via debounce
        input.addEventListener('input', debounce(() => {
            functions.displayProductSearchesSimilarRecords();
        }, 1000));
    }
}

function init() {
    // IVP
    initializeProductsCreateForm();
    initializeProductsEditForm();

    // VPS
    initializeProcessesCreateForm();
    initializeProcessesEditForm();
    initializeProcessesDuplicateForm();

    // KVPP
    initializeProductSearchesCreateForm();

    // Smart filters
    initializeSmartFilters();
}

init();
