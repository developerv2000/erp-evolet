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
const TOGGLE_ORDERS_IS_SENT_TO_CONFIRMATION_ATTRIBUTE_POST_URL = '/cmd/orders/toggle-is-sent-to-confirmation-attribute';

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
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
