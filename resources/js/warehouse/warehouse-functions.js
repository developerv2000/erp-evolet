/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { createElementFromHTML } from '../utilities';

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const formDynamicRowsList = document.querySelector('.form__dynamic-rows-list');

// IVP
const GET_BATCHES_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL = '/warehouse/product-batches/get-dynamic-rows-list-item-inputs';

/*
|--------------------------------------------------------------------------
| Variables
|--------------------------------------------------------------------------
*/

let formDynamicInputsArrayIndex = 1; // Incrementable array index

/*
|--------------------------------------------------------------------------
| Export functions
|--------------------------------------------------------------------------
*/

export function addDynamicRowsListItemOnBatchesCreate() {
    showSpinner();

    const data = {
        'inputs_index': formDynamicInputsArrayIndex,
    };

    axios.post(GET_BATCHES_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const row = createElementFromHTML(response.data);
            formDynamicRowsList.appendChild(row);
        })
        .finally(function () {
            hideSpinner();
            formDynamicInputsArrayIndex++;
        });
}
