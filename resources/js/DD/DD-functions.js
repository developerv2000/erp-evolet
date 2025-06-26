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

/**
 * Update 'date_of_receiving_print_proof_from_manufacturer' inputs visibilty/value,
 * based on 'new_layout' select value, on orders edit form.
 */
export function handleOrderProductsEditLayoutStatusChange(newLayout) {
    const printProofInput = document.querySelector('input[name="date_of_receiving_print_proof_from_manufacturer"]');
    const formGroup = printProofInput.closest('.form-group');

    if (newLayout == 1) {
        formGroup.classList.remove('form-group--hidden-visibility');
    } else {
        formGroup.classList.add('form-group--hidden-visibility');
    }
}
