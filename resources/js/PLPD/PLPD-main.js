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
    if (!ordersCreateForm) {
        return;
    }

    // Attach click event listener to dynamic rows list add item button
    const addRowButton = ordersCreateForm.querySelector('.form__dynamic-rows-list-add-item-button');
    addRowButton.addEventListener('click', () => functions.addDynamicRowsListItemOnOrdersCreate());
}

function init() {
    // Orders
    initializeOrdersCreateForm();
}

init();
