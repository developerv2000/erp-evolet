/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import './bootstrap';
import * as functions from './functions';
import { showSpinner } from '../custom-components/script';

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

// Image inputs with preview
const imageInputsWithPreview = document.querySelectorAll('.image-input-group-with-preview__input');

// Table columns form
const editTableColumnsForm = document.querySelector('.edit-table-columns-form');

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

init();

function init() {
    functions.moveFilterActiveInputsToTop(filterForm);
    initializeEditTableColumnsForm();
}
