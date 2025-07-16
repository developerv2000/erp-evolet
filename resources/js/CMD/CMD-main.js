/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './CMD-functions';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Orders table
const ordersTable = document.querySelector('.cmd-orders-table');

// Order prodcuts
const orderProductsTable = document.querySelector('.cmd-order-products-table');

// Invoices table
const invoicesTable = document.querySelector('.cmd-invoices-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-sent-to-confirmation-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersIsSentToConfirmationAttribute(evt);
    });
});

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-is-sent-to-manufacturer-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersIsSentToManufacturerAttribute(evt);
    });
});

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-production-is-started-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersProductionIsStartedAttribute(evt);
    });
});

orderProductsTable?.querySelectorAll('button[data-click-action="toggle-order-products-production-is-finished-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersProductsProductionIsFinishedAttribute(evt);
    });
});

orderProductsTable?.querySelectorAll('button[data-click-action="toggle-order-products-is-ready-for-shipment-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersProductsIsReadyForShipmentAttribute(evt);
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
