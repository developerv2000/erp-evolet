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

// Invoices
const TOGGLE_INVOICES_IS_ACCEPTED_BY_FINANCIER_ATTRIBUTE_POST_URL = '/prd/orders/invoices/toggle-is-accepted-by-financier-attribute';
const TOGGLE_INVOICES_PAYMENT_IS_COMPLETED_ATTRIBUTE_POST_URL = '/prd/orders/invoices/toggle-payment-is-completed-attribute';

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

export function toggleInvoicesIsAcceptedByFinancierAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_INVOICES_IS_ACCEPTED_BY_FINANCIER_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isAcceptedByFinancier) {
                const td = target.closest('td');
                td.innerHTML = response.data.acceptedByFinancierDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleInvoicesPaymentIsCompletedAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_INVOICES_PAYMENT_IS_COMPLETED_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.paymentIsCompleted) {
                const td = target.closest('td');
                td.innerHTML = response.data.paymentCompletedDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}
