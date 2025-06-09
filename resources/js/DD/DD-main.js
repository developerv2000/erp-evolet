/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './DD-functions';
import { debounce } from '../utilities';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Orders
const ordersEdit = document.querySelector('.dd-orders-edit');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

function initializeOrdersEditForm() {
    if (!ordersEdit) {
        return;
    }

    const layoutStatusSelect = ordersEdit.querySelector('select[name="new_layout"]');
    layoutStatusSelect.selectize.on('change', (value) => functions.handleOrdersEditLayoutStatusChange(value));
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function init() {
    initializeOrdersEditForm();
}

init();
