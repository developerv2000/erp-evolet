/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import { hideSpinner, showSpinner } from "../custom-components/script";
import { debounce } from "./utilities";

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

// MAD products.index filter
const GET_DEPENDENCIES_POST_URL = '/products/get-smart-filter-dependencies';

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

const productsPage = document.querySelector('.products-index');

if (productsPage) {
    var manufacturersSelect = productsPage.querySelector('select[name="manufacturer_id[]"]').selectize;
    var innsSelect = productsPage.querySelector('select[name="inn_id[]"]').selectize;
    var formsSelect = productsPage.querySelector('select[name="form_id[]"]').selectize;
    var dosageInput = productsPage.querySelector('input[name="dosage"]');
    var packInput = productsPage.querySelector('input[name="pack"]');
}

/*
|--------------------------------------------------------------------------
| Asynchronous filter update functions
|--------------------------------------------------------------------------
*/

function updateMadProductsFilterInputs() {
    showSpinner();

    const data = {
        manufacturer_id: manufacturersSelect.getValue().length ? manufacturersSelect.getValue() : null,
        inn_id: innsSelect.getValue().length ? innsSelect.getValue() : null,
        form_id: formsSelect.getValue().length ? formsSelect.getValue() : null,
        dosage: dosageInput.value,
        pack: packInput.value,
    };

    axios.post(GET_DEPENDENCIES_POST_URL, data, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            const { manufacturers, inns, productForms } = response.data;

            const updateSelectize = (selectize, itemsObject, labelField = 'name', valueField = 'id') => {
                const items = Object.values(itemsObject); // Convert object to array

                const currentValues = selectize.getValue();

                // Unbind change event temporarily
                selectize.off('change');

                selectize.clearOptions();

                items.forEach(item => {
                    selectize.addOption({
                        [valueField]: item[valueField],
                        [labelField]: item[labelField]
                    });
                });

                const validValues = currentValues.filter(value => items.some(item => item[valueField] == value));
                selectize.setValue(validValues, true); // true = avoid triggering 'change' event

                // Rebind change event
                selectize.on('change', () => updateMadProductsFilterInputs());
            };

            updateSelectize(manufacturersSelect, manufacturers);
            updateSelectize(innsSelect, inns);
            updateSelectize(formsSelect, productForms);
        })
        .finally(hideSpinner);
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

function initializeMadProductsFilter() {
    if (!productsPage) return;

    // Attach change event listeners to smart select dropdowns
    const selects = [manufacturersSelect, innsSelect, formsSelect];

    for (const select of selects) {
        select.on('change', () => updateMadProductsFilterInputs());
    }

    // Attach change event listeners to required smart inputs
    const inputs = [dosageInput, packInput];

    for (const input of inputs) {
        input.addEventListener('input', debounce(() => {
            updateMadProductsFilterInputs();
        }, 1500));
    }
}

export function initializeAll() {
    initializeMadProductsFilter();
}
