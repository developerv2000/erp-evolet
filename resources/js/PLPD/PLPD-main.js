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

function initializeOrdersCreateForm() {
    if (!orderCreateOrEditForm) {
        return;
    }

    const selects = orderCreateOrEditForm.querySelectorAll('select[name="manufacturer_id"], select[name="country_id"]');

    for (const select of selects) {
        select.selectize.on('change', () => functions.validateOrderCreateOrEditFormOnChange());
    }
}

function init() {
    // Orders
    initializeOrdersCreateForm();
}

init();
