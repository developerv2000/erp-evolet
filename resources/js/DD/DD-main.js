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
const orderProductsEdit = document.querySelector('.dd-order-products-edit');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

function initializeOrderProductssEditForm() {
    if (!orderProductsEdit) {
        return;
    }

    const layoutStatusSelect = orderProductsEdit.querySelector('select[name="new_layout"]');
    layoutStatusSelect.selectize.on('change', (value) => functions.handleOrderProductsEditLayoutStatusChange(value));
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function init() {
    initializeOrderProductssEditForm();
}

init();
