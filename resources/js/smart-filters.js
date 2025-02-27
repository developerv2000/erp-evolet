/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { debounce } from "./utilities";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// MAD products.index filter
const MAD_PRODUCTS_MANUFACTURER_SELECTOR = 'select[name="manufacturer_id[]"]';
const MAD_PRODUCTS_INN_SELECTOR = 'select[name="inn_id[]"]';
const MAD_PRODUCTS_FORM_SELECTOR = 'select[name="form_id[]"]';
const MAD_PRODUCTS_DOSAGE_SELECTOR = 'input[name="dosage"]';
const MAD_PRODUCTS_PACK_SELECTOR = 'input[name="pack"]';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const productsPage = document.querySelector('.products-index');

/*
|--------------------------------------------------------------------------
| Asynchronous filter update functions
|--------------------------------------------------------------------------
*/

function updateMadProductsFilterInputs() {
    const manufacturersSelect = productsPage.querySelector(MAD_PRODUCTS_MANUFACTURER_SELECTOR).selectize;
    const innsSelect = productsPage.querySelector(MAD_PRODUCTS_FORM_SELECTOR).selectize;
    const formsSelect = productsPage.querySelector(MAD_PRODUCTS_INN_SELECTOR).selectize;
    const dosageInput = productsPage.querySelector(MAD_PRODUCTS_DOSAGE_SELECTOR);
    const packInput = productsPage.querySelector(MAD_PRODUCTS_PACK_SELECTOR);

    const data = {
        manufacturer_id: manufacturersSelect.getValue(),
        inn_id: innsSelect.getValue(),
        form_id: formsSelect.selectize.getValue(),
        dosage: dosageInput.value,
        pack: packInput.value,
    }

    console.log(data);
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeMadProductsFilter() {
    if (!productsPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = productsPage.querySelectorAll(`${MAD_PRODUCTS_MANUFACTURER_SELECTOR}, ${MAD_PRODUCTS_INN_SELECTOR}, ${MAD_PRODUCTS_FORM_SELECTOR}`);

    for (const select of selects) {
        select.selectize.on('change', () => updateMadProductsFilterInputs());
    }

    // Attach change event listeners to required smart inputs
    const inputs = productsPage.querySelectorAll(`${MAD_PRODUCTS_DOSAGE_SELECTOR}, ${MAD_PRODUCTS_PACK_SELECTOR}`);

    for (let input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateMadProductsFilterInputs();
        }, 800));
    }
}

export function initializeAll() {
    initializeMadProductsFilter();
}
