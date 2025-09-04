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
