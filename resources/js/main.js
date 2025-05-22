/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import './bootstrap'; // initialize custom components & plugins
import * as functions from './functions/main-functions';
import { showSpinner } from '../custom-components/script';
import './MAD'; // initialize MAD part
// import './charts';
// import initializeSmartFilters from './smart-filters';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Main table
const mainTable = document.querySelector('.main-table');

// Different action buttons
const leftbarToggler = document.querySelector('.header__leftbar-toggler');
const fullscreenButtons = document.querySelectorAll('[data-click-action="request-fullscreen"]');
const targetDeleteModalButtons = document.querySelectorAll('[data-click-action="show-target-delete-modal"]');
const targetRestoreModalButtons = document.querySelectorAll('[data-click-action="show-target-restore-modal"]');

// Global forms
const mainForms = document.querySelectorAll('.form');
const filterForm = document.querySelector('.filter-form');
const appendsInputsBeforeSubmitForms = document.querySelectorAll('[data-before-submit="appends-inputs"]');
const showsSpinnerOnSubmitForms = document.querySelectorAll('[data-on-submit="show-spinner"]');
const disableSubmitButtonOnSubmitForms = document.querySelectorAll('[data-on-submit="disable-form-submit-button"]');
const editTableColumnsForm = document.querySelector('.edit-table-columns-form');

// Global inputs
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
    if (target.matches('.th__select-all')) {
        functions.toggleTableCheckboxes(mainTable);
        evt.stopPropagation();
    }
});

/**
 * Handle main forms click events by delegating of some child element events
 */
mainForms.forEach((form) => {
    form.addEventListener('click', (evt) => {
        const target = evt.target;

        if (target.matches('.form__row-remove-button-icon')) {
            functions.removeClosestFormRow(target);
            evt.stopPropagation();
        }
    });
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

disableSubmitButtonOnSubmitForms.forEach((form) => {
    form.addEventListener('submit', (evt) => functions.disableFormSubmitButton(evt.target));
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

function init() {
    functions.moveFilterActiveInputsToTop(filterForm);
    functions.initializeSpecificFormatableInputs();
    initializeEditTableColumnsForm();
    // initializeSmartFilters();
}

init();
