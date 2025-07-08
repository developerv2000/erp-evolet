/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import * as functions from './PRD-functions';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

// Invoices table
const invoicesTable = document.querySelector('.prd-invoices-table');

/*
|--------------------------------------------------------------------------
| Event Listeners
|--------------------------------------------------------------------------
*/

invoicesTable?.querySelectorAll('button[data-click-action="toggle-invoices-is-accepted-by-financier-attribute"]').forEach(button => {
    button.addEventListener('click', (evt) => {
        functions.toggleInvoicesIsAcceptedByFinancierAttribute(evt);
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
