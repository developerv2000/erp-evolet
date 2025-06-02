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
const GET_AVAILABLE_MAHS_OF_READY_FOR_ORDER_PROCESS_POST_URL = '/plpd/orders/get-available-mahs-of-ready-for-order-process';
const TOGGLE_ORDERS_IS_SENT_TO_BDM_ATTRIBUTE_POST_URL = '/plpd/orders/toggle-is-sent-to-bdm-attribute';

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

export function updateProcessSelectOnOrderFormChange() {
    // Get manufacturer, and country ID values
    const manufacturerID = document.querySelector('select[name="manufacturer_id"]').value;
    const countryID = document.querySelector('select[name="country_id"]').value;

    // Return if any required fields are empty
    if (manufacturerID == '' || countryID == '') {
        return;
    }

    // Get updatable select
    const processSelect = document.querySelector('select[name="process_id"]').selectize;

    showSpinner();

    // Send ajax request
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
            if (response.data.readyForOrderProcesses.length > 0) {
                // Update processes select
                const readyForOrderProcesses = response.data.readyForOrderProcesses;
                refreshSelectizeOptions(processSelect, readyForOrderProcesses, updateMAHSelectOnOrderFormChange, 'full_trademark_en', 'id', false);
            } else {
                // Empty processes select and alert user
                refreshSelectizeOptions(processSelect, [], updateMAHSelectOnOrderFormChange, 'full_trademark_en', 'id', false);
                alert(response.data.notFoundmessage);
            }
        })
        .finally(function () {
            // Update MAHs select
            updateMAHSelectOnOrderFormChange();
            hideSpinner();
        });
}

export function updateMAHSelectOnOrderFormChange() {
    const processSelect = document.querySelector('select[name="process_id"]');
    const mahSelect = document.querySelector('select[name="marketing_authorization_holder_id"]').selectize;

    if (processSelect.value == '') {
        // Empty MAHs select, because this function is also called by updateProcessSelectOnOrderFormChange(),
        // while executing function, processesSelect value can be empty
        refreshSelectizeOptions(mahSelect, [], null, 'name', 'id', false);

        return;
    }

    showSpinner();

    const data = {
        'process_id': processSelect.value,
    };

    axios.post(GET_AVAILABLE_MAHS_OF_READY_FOR_ORDER_PROCESS_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const MAHs = response.data.MAHs;
            refreshSelectizeOptions(mahSelect, MAHs, null, 'name', 'id', false);
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleOrdersIsSentToBDMAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDERS_IS_SENT_TO_BDM_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isSentToBdm) {
                const td = target.closest('td');
                td.innerHTML = response.data.sentToBdmDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
