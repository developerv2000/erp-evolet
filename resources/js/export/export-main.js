/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './export-functions';
import { debounce } from '../utilities';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Assemblages forms
const assemblagesCreateForm = document.querySelector('.export-assemblages-create-form');

// Assemblages tables
const assemblagesTable = document.querySelector('.export-assemblages-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

assemblagesTable?.querySelectorAll('button[data-click-action="end-shipment-from-warehouse"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.endShipmentFromWarehouseOfAssemblages(evt);
    });
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeAssemblagesCreateForm() {
    if (!assemblagesCreateForm) {
        return;
    }

    // Attach click event listener to dynamic rows list add item button
    const addRowButton = assemblagesCreateForm.querySelector('.form__dynamic-rows-list-add-item-button');
    addRowButton.addEventListener('click', () => functions.addDynamicRowsListItemOnAssemblagesCreate());
}

// function initializeOrderProductCreateOrEditForm() {
//     if (!orderProductCreateOrEditForm) {
//         return;
//     }

//     const trademarkSelect = orderProductCreateOrEditForm.querySelector('select[name="temporary_process_id"]');
//     trademarkSelect.selectize.on('change', (value) => functions.updateMAHsOnOrderProductFormChange(value));
// }

function init() {
    // Orders
    initializeAssemblagesCreateForm();
}

init();
