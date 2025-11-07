/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './warehouse-functions';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// IVP forms
const batchesCreateForm = document.querySelector('.warehouse-product-batches-create-form');

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeBatchesCreateForm() {
    if (!batchesCreateForm) {
        return;
    }

    // Attach click event listener to dynamic rows list add item button
    const addRowButton = batchesCreateForm.querySelector('.form__dynamic-rows-list-add-item-button');
    addRowButton.addEventListener('click', () => functions.addDynamicRowsListItemOnBatchesCreate());
}

function init() {
    // IVP
    initializeBatchesCreateForm();
}

init();
