/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './PLPD-functions';
import { debounce } from '../utilities';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Order forms
const orderCreateOrEditForm = document.querySelector('.plpd-orders-create-form, .plpd-orders-edit-form');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeOrdersCreateOrEditForm() {
    if (!orderCreateOrEditForm) {
        return;
    }

    // Attach change event listeners to Manufacturer and Country dropdowns
    const selects = orderCreateOrEditForm.querySelectorAll('select[name="manufacturer_id"], select[name="country_id"]');

    for (const select of selects) {
        select.selectize.on('change', () => functions.updateProcessSelectOnOrderFormChange());
    }

    // Attach change event listener to Process dropdown
    const processSelect = orderCreateOrEditForm.querySelector('select[name="process_id"]');
    processSelect.selectize.on('change', () => functions.updateMAHSelectOnOrderFormChange());
}

function init() {
    // Orders
    initializeOrdersCreateOrEditForm();
}

init();
