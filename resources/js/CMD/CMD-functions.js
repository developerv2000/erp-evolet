/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";
import { updateOrderStatus } from "../shared";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// Orders
const TOGGLE_ORDERS_IS_SENT_TO_CONFIRMATION_ATTRIBUTE_POST_URL = '/cmd/orders/toggle-is-sent-to-confirmation-attribute';
const TOGGLE_ORDERS_IS_SENT_TO_MANUFACTURER_ATTRIBUTE_POST_URL = '/cmd/orders/toggle-is-sent-to-manufacturer-attribute';

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

export function toggleOrdersIsSentToConfirmationAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDERS_IS_SENT_TO_CONFIRMATION_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isSentToConfirmation) {
                const td = target.closest('td');
                td.innerHTML = response.data.sentToConfirmationDate;

                // Update order status
                updateOrderStatus(td, response.data.statusHTML);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleOrdersIsSentToManufacturerAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDERS_IS_SENT_TO_MANUFACTURER_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isSentToManufacturer) {
                const td = target.closest('td');
                td.innerHTML = response.data.sentToManufacturerDate;

                // Update order status
                updateOrderStatus(td, response.data.statusHTML);
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
