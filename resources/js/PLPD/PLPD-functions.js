/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { refreshSelectizeOptions } from "../utilities";
import { updateOrderStatus } from "../shared";
import { createElementFromHTML } from "../utilities";
import { initializeSelectizes } from "../plugins";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Orders
const GET_READY_FOR_ORDER_PROCESSES_OF_MANUFACTURER_POST_URL = '/plpd/orders/get-ready-for-order-processes-of-manufacturer';
const GET_PROCESS_WITH_IT_SIMILAR_RECORDS_FOR_ORDER_POST_URL = '/plpd/orders/get-process-with-it-similar-records-for-order';
const TOGGLE_ORDERS_IS_SENT_TO_BDM_ATTRIBUTE_POST_URL = '/plpd/orders/toggle-is-sent-to-bdm-attribute';
const TOGGLE_ORDERS_IS_CONFIRMED_ATTRIBUTE_POST_URL = '/plpd/orders/toggle-is-confirmed-attribute';

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

export function addDynamicRowsListItemOnOrdersCreate() {
    showSpinner();

    // Get manufacturer, and country ID values
    const manufacturerID = document.querySelector('select[name="manufacturer_id"]').value;
    const countryID = document.querySelector('select[name="country_id"]').value;

    // Send ajax request
    const data = {
        'manufacturer_id': manufacturerID,
        'country_id': countryID,
        'inputs_index': formDynamicInputsArrayIndex,
    };

    axios.post(GET_READY_FOR_ORDER_PROCESSES_OF_MANUFACTURER_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.success) {
                // Insert new row
                const row = createElementFromHTML(response.data.row);
                formDynamicRowsList.appendChild(row);

                // Initialize new selectizes
                initializeSelectizes();

                // Attach event listener to new added Trademark selectize
                const trademarkSelect =
                    formDynamicRowsList.querySelector(`select[name='products[${formDynamicInputsArrayIndex}][temporary_process_id]']`);

                const mahInputArrayIndex = formDynamicInputsArrayIndex;
                trademarkSelect.selectize.on('change', (value) => updateMAHSelectOnOrderCreateFormChange(value, mahInputArrayIndex));

                // Disable manufacturer and country selectizes
                document.querySelector('select[name="manufacturer_id"]').selectize.disable();
                document.querySelector('select[name="country_id"]').selectize.disable();
                // Remove disabled attribute from selects so data will be sent to the server
                document.querySelector('select[name="manufacturer_id"]').removeAttribute('disabled');
                document.querySelector('select[name="country_id"]').removeAttribute('disabled');

                // Increment array index
                formDynamicInputsArrayIndex++;
            } else {
                alert(response.data.errorMessage);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function updateMAHSelectOnOrderCreateFormChange(temporaryProcessID, mahInputArrayIndex) {
    showSpinner();

    const data = {
        'process_id': temporaryProcessID,
    };

    axios.post(GET_PROCESS_WITH_IT_SIMILAR_RECORDS_FOR_ORDER_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Update MAH selectize
            const mahSelectize = document.querySelector(`select[name='products[${mahInputArrayIndex}][process_id]']`).selectize;
            const processWithItSimilarRecords = response.data.processWithItSimilarRecords;
            refreshSelectizeOptions(mahSelectize, processWithItSimilarRecords, null, 'mah_name_with_id', 'id', false);
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

                // Update order status
                updateOrderStatus(td, response.data.statusHTML);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleOrdersConfirmedAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDERS_IS_CONFIRMED_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isConfirmed) {
                const td = target.closest('td');
                td.innerHTML = response.data.confirmationDate;

                // Update order status
                updateOrderStatus(td, response.data.statusHTML);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
