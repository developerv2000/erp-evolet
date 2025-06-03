// This helper module is used for shared functions,
// which are used by multiple departments

/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DOM Elements
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Export function
|--------------------------------------------------------------------------
*/

/**
 * Updates the status column of an order row in the table.
 *
 * This helper is used by both PLPD and CMD after a successful AJAX request.
 *
 * @param {HTMLElement} closestTd - Closes 'td' tag of element, that triggered the status update (e.g., a button).
 * @param {string} statusHTML - The raw HTML to inject into the order status container.
 */
export function updateOrderStatus(closestTd, statusHTML) {
    // Find the closest table row to the triggering element
    const orderRow = closestTd.closest('tr');
    // Find the element responsible for displaying the order status
    const statusCell = orderRow.querySelector('.td__order-status');
    // Update the status cell with the provided HTML
    statusCell.innerHTML = statusHTML;
}

