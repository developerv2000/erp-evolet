/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../../custom-components/script";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const END_SHIPMENT_FROM_MANUFACTURER_POST_URL = '/eld/orders/products/end-shipment-from-manufacturer';

// Invoices
const TOGGLE_INVOICES_IS_SENT_FOR_PAYMENT_ATTRIBUTE_POST_URL = '/cmd/orders/invoices/toggle-is-sent-for-payment-attribute';

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

export function endShipmentFromManufacturerOfOrdersProducts(evt) {
    showSpinner();

    const target = evt.currentTarget;

    axios.post(END_SHIPMENT_FROM_MANUFACTURER_POST_URL + '/' + target.dataset.recordId, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.shipmentFromManufacturerEnded) {
                const td = target.closest('td');
                td.innerHTML = response.data.shipmentFromManufacturerEndDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleInvoicesIsSentForPaymentAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_INVOICES_IS_SENT_FOR_PAYMENT_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isSentForPayment) {
                const td = target.closest('td');
                td.innerHTML = response.data.sentForPaymentDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
