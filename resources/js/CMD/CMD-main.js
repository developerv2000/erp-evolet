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

ordersTable?.querySelectorAll('button[data-click-action="toggle-orders-production-is-finished-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleOrdersProductionIsFinishedAttribute(evt);
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
