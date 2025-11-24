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

// Order forms
const assemblagesCreateForm = document.querySelector('.export-assemblages-create-form');

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
