/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './ELD-functions';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Order prodcuts
const orderProductsTable = document.querySelector('.eld-order-products-table');

// Invoices table
const invoicesTable = document.querySelector('.eld-invoices-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

orderProductsTable?.querySelectorAll('button[data-click-action="end-shipment-from-manufacturer"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.endShipmentFromManufacturerOfOrdersProducts(evt);
    });
});

invoicesTable?.querySelectorAll('button[data-click-action="toggle-invoices-is-sent-for-payment-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleInvoicesIsSentForPaymentAttribute(evt);
    });
});

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function init() {
}

init();
