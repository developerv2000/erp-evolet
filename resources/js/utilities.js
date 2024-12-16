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
