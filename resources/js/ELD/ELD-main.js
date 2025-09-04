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

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function init() {
}

init();
