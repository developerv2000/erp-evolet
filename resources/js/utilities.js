import { SELECTIZE_DEFAULT_OPTIONS } from "./plugins";

/**
 * Removes a specific inline style property of an element after a certain duration.
 * @param {HTMLElement} element - The DOM element whose style property should be removed.
 * @param {string} property - The CSS property to remove (e.g., 'height', 'width').
 * @param {number} duration - The duration in milliseconds to wait before removing the property.
 */
export function removeElementStylePropertyDelayed(element, property, duration) {
    setTimeout(() => {
        element.style[property] = '';
    }, duration);
}

/**
 * Creates a debounced function that delays invoking the provided callback until after a specified delay.
 * @param {Function} callback - The function to debounce.
 * @param {number} [timeoutDelay=500] - The delay in milliseconds to wait before invoking the callback.
 * @returns {Function} A debounced version of the callback function.
 */
export function debounce(callback, timeoutDelay = 500) {
    let timeoutId;

    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => callback.apply(this, args), timeoutDelay);
    };
}

/**
 * Creates a new DOM element from the provided HTML string.
 * @param {string} htmlString - The HTML string to parse and create the element from.
 * @returns {HTMLElement} The newly created DOM element.
 */
export function createElementFromHTML(htmlString) {
    const template = document.createElement('template');
    template.innerHTML = htmlString.trim(); // Trim to avoid unwanted whitespace
    return template.content.firstElementChild; // 'firstElementChild' is more accurate
}

/**
 * Refreshes the options in a Selectize instance while preserving valid selected values.
 *
 * Used on smart filters.
 *
 * @param {Object} selectize - The Selectize instance.
 * @param {Object} newItems - Object of items to populate the dropdown.
 * @param {Function|null} onChange - Optional callback to bind on value change.
 * @param {string} labelField - Field to display as label (default: 'name').
 * @param {string} valueField - Field to use as value (default: 'id').
 * @param {boolean} isMultiple - Whether the select is multiple (default: true).
 */
export function refreshSelectizeOptions(selectize, newItems, onChange = null, labelField = 'name', valueField = 'id', isMultiple = true) {
    const items = Object.values(newItems); // Convert input object to array
    const previousSelection = selectize.getValue();

    // Always unbind previous change handler
    selectize.off('change');

    // Clear previous selections and options
    selectize.clear(true); // Clears selection + input
    selectize.clearOptions(); // Clears dropdown options

    // Refresh even if no items to force UI reset
    if (items.length === 0) {
        selectize.refreshOptions(false); // false = don't trigger dropdown open
        return;
    }

    // Add new options
    items.forEach(item => {
        selectize.addOption({
            [SELECTIZE_DEFAULT_OPTIONS.valueField]: item[valueField],
            [SELECTIZE_DEFAULT_OPTIONS.labelField]: item[labelField],
        });
    });

    // Restore valid selections
    if (isMultiple) {
        const validSelections = previousSelection.filter(value =>
            items.some(item => item[valueField] == value)
        );
        selectize.setValue(validSelections, true);
    } else {
        const isValid = items.some(item => item[valueField] == previousSelection);
        selectize.setValue(isValid ? previousSelection : null, true);
    }

    // Bind change handler if provided
    if (typeof onChange === 'function') {
        selectize.on('change', onChange);
    }
}
