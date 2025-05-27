/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { createElementFromHTML } from '../utilities';
import { initializeSelectizes } from "../plugins";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const formDynamicRowsList = document.querySelector('.form__dynamic-rows-list');

// Orders
const GET_ORDERS_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL = '/plpd/orders/create/get-dynamic-rows-list-item-inputs';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

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

export function addDynamicRowsListItemOnOrdersCreate() {
    // Get manufacturer, and country ID values
    const manufacturerID = document.querySelector('select[name="manufacturer_id"]').value;
    const countryID = document.querySelector('select[name="country_id"]').value;

    // Return if any required fields are empty
    if (manufacturerID == '' || countryID == '') {
        alert('Пожалуйста, выберите производителя и страну.');

        return;
    }

    showSpinner();

    // Send ajax request and get required inputs
    const data = {
        'manufacturer_id': manufacturerID,
        'country_id': countryID,
        'inputs_index': formDynamicInputsArrayIndex,
    };

    axios.post(GET_ORDERS_DYNAMIC_ROWS_LIST_ITEM_INPUTS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.products_found) {
                const row = createElementFromHTML(response.data.inputs);
                formDynamicRowsList.appendChild(row);
                initializeSelectizes();
            } else {
                alert(response.data.message);
            }
        })
        .finally(function () {
            hideSpinner();
            formDynamicInputsArrayIndex++;
        });
}
