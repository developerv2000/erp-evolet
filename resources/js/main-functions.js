/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showModal, showSpinner } from "../custom-components/script";
import { debounce } from './utilities';

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const TOGGLE_LEFTBAR_PATCH_URL = '/settings/collapsed-leftbar';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const leftbar = document.querySelector('.leftbar');
const targetDeleteModal = document.querySelector('.target-delete-modal');
const targetRestoreModal = document.querySelector('.target-restore-modal');

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

export function disableFormSubmitButton(form) {
    form.querySelector('button[type="submit"]').disabled = true;
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

export function removeClosestFormRow(button) {
    const row = button.closest('.form__row');
    row.parentNode.removeChild(row);
}

export function initializeSpecificFormatableInputs() {
    // Validate specific input ('dosage', 'pack', 'INN', etc) values.
    const uninitializedSpecificFormatableInputs = document.querySelectorAll('.specific-formatable-input:not(.specific-formatable-input--initialized)');

    uninitializedSpecificFormatableInputs.forEach((input) => {
        input.addEventListener('input', debounce((evt) => validateSpecificFormatableInput(evt)));
        input.classList.add('specific-formatable-input--initialized');
    });
}

/*
|--------------------------------------------------------------------------
| Private functions
|--------------------------------------------------------------------------
*/

function mapSortableTableColumnsData(item, index) {
    const column = {};
    column.name = item.dataset.columnName;
    column.order = index + 1;
    column.width = parseInt(item.querySelector('.sortable-columns__width-input').value);
    column.visible = item.querySelector('.switch').checked ? 1 : 0;
    return column;
}

/**
 * Validate specific input ('dosage', 'pack', 'INN', etc) values.
 */
function validateSpecificFormatableInput(evt) {
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
