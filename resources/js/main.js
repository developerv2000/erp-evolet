/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import './bootstrap';
import * as functions from './functions';
import { showSpinner } from '../custom-components/script';
import { debounce } from './utilities';

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Tables
const mainTable = document.querySelector('.main-table');

// Different action buttons
const leftbarToggler = document.querySelector('.header__leftbar-toggler');
const fullscreenButtons = document.querySelectorAll('[data-click-action="request-fullscreen"]');
const targetDeleteModalButtons = document.querySelectorAll('[data-click-action="show-target-delete-modal"]');
const targetRestoreModalButtons = document.querySelectorAll('[data-click-action="show-target-restore-modal"]');

// Forms
const filterForm = document.querySelector('.filter-form');
const appendsInputsBeforeSubmitForms = document.querySelectorAll('[data-before-submit="appends-inputs"]');
const showsSpinnerOnSubmitForms = document.querySelectorAll('[data-on-submit="show-spinner"]');
const exportAsExcelForm = document.querySelector('.export-as-excel-form');
const productsCreateForm = document.querySelector('.products-create-form');
const editTableColumnsForm = document.querySelector('.edit-table-columns-form');

// Inputs
const specificFormatableInputs = document.querySelectorAll('.specific-formatable-input');
const imageInputsWithPreview = document.querySelectorAll('.image-input-group-with-preview__input');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

/**
 * Handle main table click events by delegating of some child element events
 */
mainTable?.addEventListener('click', (evt) => {
    const target = evt.target;

    // Text max lines toggling
    if (target.closest('[data-on-click="toggle-td-text-max-lines"]')) {
        functions.toggleTextMaxLines(target);
        evt.stopPropagation();
    }

    // Select all toggling
    if (evt.target.matches('.th__select-all')) {
        functions.toggleTableCheckboxes(mainTable);
        evt.stopPropagation();
    }
});

leftbarToggler.addEventListener('click', functions.toggleLeftbar);

fullscreenButtons.forEach((button) => {
    const fullscreenTarget = document.querySelector(button.dataset.targetSelector);

    button.addEventListener('click', () => functions.enterFullscreen(fullscreenTarget));
    fullscreenTarget.addEventListener('fullscreenchange', () => functions.toggleFullscreenClass(fullscreenTarget));
});

appendsInputsBeforeSubmitForms.forEach((form) => {
    form.addEventListener('submit', (evt) => functions.appendFormInputsBeforeSubmit(evt));
});

showsSpinnerOnSubmitForms.forEach((form) => {
    form.addEventListener('submit', showSpinner);
});

targetDeleteModalButtons.forEach((button) => {
    button.addEventListener('click', () => functions.showTargetDeleteModal(button));
});

targetRestoreModalButtons.forEach((button) => {
    button.addEventListener('click', () => functions.showTargetRestoreModal(button));
});

filterForm?.addEventListener('submit', (evt) => functions.handleFilterFormSubmit(evt));

imageInputsWithPreview.forEach((input) => {
    input.addEventListener('change', (evt) => functions.displayLocalImage(evt));
});

exportAsExcelForm?.addEventListener('submit', (evt) => functions.disableExportAsExcelFormSubmitButton(evt));

// Validate specific input ('dosage', 'pack', 'INN', etc) values.
specificFormatableInputs.forEach((input) => {
    input.addEventListener('input', debounce((evt) => functions.validateSpecificFormatableInput(evt)));
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeEditTableColumnsForm() {
    if (!editTableColumnsForm) {
        return;
    }

    // Make table columns sortable
    $('.sortable-columns').sortable();

    // Add event listeners for each form width inputs
    editTableColumnsForm.querySelectorAll('.sortable-columns__width-input').forEach(input => {
        input.addEventListener('input', (evt) => functions.handleTableColumnWidthInputUpdate(evt));
    });

    // Add event listener for the form submit
    editTableColumnsForm.addEventListener('submit', (evt) => functions.handleEditTableColumnsSubmit(evt));
}

function initializeProductsCreateForm() {
    if (!productsCreateForm) {
        return;
    }

    // Select the dropdowns for manufacturer, inn, and form
    const selects = productsCreateForm.querySelectorAll('select[name="manufacturer_id"], select[name="inn_id"], select[name="form_id')

    // Attach change event listeners to all select dropdowns
    for (const select of selects) {
        select.selectize.on('change', (value) => functions.displayProductsSimilarRecords());
    }
}

init();

function init() {
    functions.moveFilterActiveInputsToTop(filterForm);
    initializeEditTableColumnsForm();
    initializeProductsCreateForm();
}
