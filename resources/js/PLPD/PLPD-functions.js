/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { refreshSelectizeOptions } from "../utilities";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Orders
const GET_READY_FOR_ORDER_PROCESSES_OF_MANUFACTURER_POST_URL = '/plpd/orders/get-ready-for-order-processes-of-manufacturer';

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

/*
|--------------------------------------------------------------------------
| Export functions
|--------------------------------------------------------------------------
*/

export function validateOrderCreateOrEditFormOnChange() {
    // Get manufacturer, and country ID values
    const manufacturerID = document.querySelector('select[name="manufacturer_id"]').value;
    const countryID = document.querySelector('select[name="country_id"]').value;

    // Return if any required fields are empty
    if (manufacturerID == '' || countryID == '') {
        return;
    }

    // Get updatable selects
    const processSelect = document.querySelector('select[name="process_id"]').selectize;
    const mahSelect = document.querySelector('select[name="marketing_authorization_holder_id"]').selectize;

    showSpinner();

    // Send ajax request and get requested data
    const data = {
        'manufacturer_id': manufacturerID,
        'country_id': countryID,
    };

    axios.post(GET_READY_FOR_ORDER_PROCESSES_OF_MANUFACTURER_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.productsFound) {
                // Update products and MAHs selects
                const readyForOrderProcesses = response.data.readyForOrderProcesses;
                const MAHs = response.data.MAHs;

                refreshSelectizeOptions(processSelect, readyForOrderProcesses, null, 'full_trademark_en', 'id', false);
                refreshSelectizeOptions(mahSelect, MAHs, null, 'name', 'id', false);
            } else {
                // Empty products and MAHs selects and alert user
                refreshSelectizeOptions(processSelect, [], null, 'full_trademark_en', 'id', false);
                refreshSelectizeOptions(mahSelect, [], null, 'name', 'id', false);

                alert(response.data.message);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
