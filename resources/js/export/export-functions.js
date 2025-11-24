/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { refreshSelectizeOptions } from "../utilities";
import { createElementFromHTML } from "../utilities";
import { initializeSelectizes } from "../plugins";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Assemblages
const GET_ASSEMBLAGES_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL = '/export/assemblages/get-dynamic-rows-list-item-inputs';
const GET_ASSEMBLAGES_MATCHED_BACHES_POST_URL = '/export/assemblages/get-matched-batches-on-create';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const formDynamicRowsList = document.querySelector('.form__dynamic-rows-list');

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

export function addDynamicRowsListItemOnAssemblagesCreate() {
    showSpinner();

    const inputIndex = formDynamicInputsArrayIndex;

    // Send ajax request
    const data = {
        'inputs_index': inputIndex,
    };

    axios.post(GET_ASSEMBLAGES_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Insert new row
            const row = createElementFromHTML(response.data);
            formDynamicRowsList.appendChild(row);

            // Initialize new selectizes
            initializeSelectizes();

            // Attach event listener to new added Manufacturer and MAH selectizes
            const manufacturerSelect =
                formDynamicRowsList.querySelector(`select[name='batches[${inputIndex}][manufacturer_id]']`);

            const mahSelect =
                formDynamicRowsList.querySelector(`select[name='batches[${inputIndex}][marketing_authorization_holder_id]']`);

            manufacturerSelect.selectize.on('change', (value) => updateBatchSelectOnAssemblagesCreateFormChange(manufacturerSelect, mahSelect, inputIndex));
            mahSelect.selectize.on('change', (value) => updateBatchSelectOnAssemblagesCreateFormChange(manufacturerSelect, mahSelect, inputIndex));

            // Increment array index
            formDynamicInputsArrayIndex++;
        })
        .finally(function () {
            hideSpinner();
        });
}

/*
|--------------------------------------------------------------------------
| Private functions
|--------------------------------------------------------------------------
*/

function updateBatchSelectOnAssemblagesCreateFormChange(manufacturerSelect, mahSelect, inputIndex) {
    const batchesSelectize = document.querySelector(`select[name='batches[${inputIndex}][batch_id]']`).selectize;

    if (!manufacturerSelect.value || !mahSelect.value) {
        // Clear Batches selectize
        refreshSelectizeOptions(batchesSelectize, [], null, 'series_with_trademark_en', 'id', false);
        return;
    }

    showSpinner();

    const data = {
        'manufacturer_id': manufacturerSelect.value,
        'marketing_authorization_holder_id': mahSelect.value,
    };

    axios.post(GET_ASSEMBLAGES_MATCHED_BACHES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Update Batches selectize
            refreshSelectizeOptions(batchesSelectize, response.data.matchedBatches, null, 'series_with_full_trademark_en', 'id', false);

            // Alert if no matched batches
            if (response.data.matchedBatches.length === 0) {
                alert(response.data.emptyMessage);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
