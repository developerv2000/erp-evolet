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
const ordersCreateForm = document.querySelector('.plpd-orders-create-form');
const orderProductCreateOrEditForm = document.querySelector('.plpd-order-products-create-form, .plpd-order-products-edit-form');

// Orders table
const ordersTable = document.querySelector('.plpd-orders-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-sent-to-bdm-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersIsSentToBDMAttribute(evt);
    });
});

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-confirmed-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersConfirmedAttribute(evt);
    });
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeOrdersCreateForm() {
    if (!ordersCreateForm) {
        return;
    }

    // Attach click event listener to dynamic rows list add item button
    const addRowButton = ordersCreateForm.querySelector('.form__dynamic-rows-list-add-item-button');
    addRowButton.addEventListener('click', () => functions.addDynamicRowsListItemOnOrdersCreate());
}

function initializeOrderProductCreateOrEditForm() {
    if (!orderProductCreateOrEditForm) {
        return;
    }

    const trademarkSelect = orderProductCreateOrEditForm.querySelector('select[name="temporary_process_id"]');
    trademarkSelect.selectize.on('change', (value) => functions.updateMAHsOnOrderProductFormChange(value));
}

function init() {
    // Orders
    initializeOrdersCreateForm();

    // Order products
    initializeOrderProductCreateOrEditForm();
}

init();
