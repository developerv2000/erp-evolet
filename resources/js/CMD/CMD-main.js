/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './CMD-functions';
import { debounce } from '../utilities';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Orders table
const ordersTable = document.querySelector('.cmd-orders-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-sent-to-confirmation-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersIsSentToConfirmationAttribute(evt);
    });
});

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-sent-to-manufacturer-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersIsSentToManufacturerAttribute(evt);
    });
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function init() {
}

init();
