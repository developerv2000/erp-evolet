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
const TOGGLE_ORDERS_PRODUCTION_IS_STARTED_ATTRIBUTE_POST_URL = '/cmd/orders/toggle-production-is-started-attribute';

// Order products
const TOGGLE_ORDER_PRODUCTS_PRODUCTION_IS_FINISHED_ATTRIBUTE_POST_URL = '/cmd/orders/products/toggle-production-is-finished-attribute';
const TOGGLE_ORDER_PRODUCTS_IS_READY_FOR_SHIMPENT_ATTRIBUTE_POST_URL = '/cmd/orders/products/toggle-is-ready-for-shipment-attribute';

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

export function toggleOrdersProductionIsStartedAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDERS_PRODUCTION_IS_STARTED_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.productionIsStarted) {
                const td = target.closest('td');
                td.innerHTML = response.data.productionStartDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleOrdersProductsProductionIsFinishedAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDER_PRODUCTS_PRODUCTION_IS_FINISHED_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.productionIsFinished) {
                const td = target.closest('td');
                td.innerHTML = response.data.productionEndDate;
            }
        })
        .finally(function () {
            hideSpinner();
        });
}

export function toggleOrdersProductsIsReadyForShipmentAttribute(evt) {
    showSpinner();

    const target = evt.currentTarget;

    const data = {
        'record_id': target.dataset.recordId,
        'action': target.dataset.actionType,
    };

    axios.post(TOGGLE_ORDER_PRODUCTS_IS_READY_FOR_SHIMPENT_ATTRIBUTE_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.data.isReadyForShipment) {
                const td = target.closest('td');
                td.innerHTML = response.data.readinessForShipmentDate;
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
