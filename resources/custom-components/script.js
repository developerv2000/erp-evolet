/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

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
 * Function to collapse an element with smooth transition.
 * @param {HTMLElement} element - The DOM element to collapse.
 */
function collapseElement(element) {
    element.style.height = element.scrollHeight + 'px';
    element.offsetHeight; // Force repaint
    element.style.height = '0px';
    removeElementStylePropertyDelayed(element, 'height', 300);
}

/**
 * Function to expand an element with smooth transition.
 * @param {HTMLElement} element - The DOM element to expand.
 */
function expandElement(element) {
    element.style.height = element.scrollHeight + 'px';
    removeElementStylePropertyDelayed(element, 'height', 300);
}

// Modal helpers
export function showModal(modal) {
    modal.classList.add('modal--visible');
}

export function hideVisibleModal() {
    document.querySelector('.modal--visible')?.classList.remove('modal--visible');
}

// Spinner helpers
export function showSpinner() {
    document.querySelector('.spinner').classList.add('spinner--visible');
}

export function hideSpinner() {
    document.querySelector('.spinner').classList.remove('spinner--visible');
}

/*
|--------------------------------------------------------------------------
| Dropdown
|--------------------------------------------------------------------------
*/

function initDropdowns() {
    const dropdownButtons = document.querySelectorAll('.dropdown__button');

    dropdownButtons.forEach(button => {
        button.addEventListener('click', (evt) => {
            const dropdown = evt.currentTarget.closest('.dropdown');
            const isExpanded = button.getAttribute('aria-expanded') === 'true';

            dropdown.classList.toggle('dropdown--active');
            button.setAttribute('aria-expanded', !isExpanded);

            evt.stopPropagation();
        });
    });

    document.addEventListener('click', (evt) => {
        document.querySelectorAll('.dropdown--active').forEach(activeDropdown => {
            if (!activeDropdown.contains(evt.target)) {
                const button = activeDropdown.querySelector('.dropdown__button');
                activeDropdown.classList.remove('dropdown--active');
                button.setAttribute('aria-expanded', 'false');
            }
        });
    });
}

/*
|--------------------------------------------------------------------------
| Collapse
|--------------------------------------------------------------------------
*/

function initCollapsibles() {
    const togglers = document.querySelectorAll('[data-click-action="toggle-collapse"]');

    togglers.forEach(toggler => {
        toggler.addEventListener('click', () => {
            const collapse = document.querySelector(toggler.dataset.collapseSelector);
            const isOpen = collapse.classList.contains('collapse--open');

            if (!isOpen) {
                expandElement(collapse);
            } else {
                collapseElement(collapse);
            }

            collapse.classList.toggle('collapse--open');
            toggler.classList.toggle('collapse-button--active');
        });
    });
}

/*
|--------------------------------------------------------------------------
| Accordion
|--------------------------------------------------------------------------
*/

function initAccordions() {
    const accordions = document.querySelectorAll('.accordion');

    accordions.forEach(accordion => {
        const buttons = accordion.querySelectorAll('.accordion__button');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const accordionItem = button.closest('.accordion__item');
                const accordionContent = accordionItem.querySelector('.accordion__content');
                const isOpen = accordionItem.classList.contains('accordion-item--open');

                accordion.querySelectorAll('.accordion-item--open').forEach(openItem => {
                    if (openItem !== accordionItem) {
                        const openContent = openItem.querySelector('.accordion__content');
                        collapseElement(openContent);
                        openItem.classList.remove('accordion-item--open');
                    }
                });

                if (!isOpen) {
                    expandElement(accordionContent);
                    accordionItem.classList.add('accordion-item--open');
                } else {
                    collapseElement(accordionContent);
                    accordionItem.classList.remove('accordion-item--open');
                }
            });
        });
    });
}

/*
|--------------------------------------------------------------------------
| Modal
|--------------------------------------------------------------------------
*/

function initModals() {
    const showButtons = document.querySelectorAll('[data-click-action="show-modal"]');
    const hideButtons = document.querySelectorAll('[data-click-action="hide-visible-modal"]');

    showButtons.forEach((button) => {
        button.addEventListener('click', (evt) => {
            hideVisibleModal();
            showModal(document.querySelector(evt.currentTarget.dataset.modalSelector));
        });
    });

    hideButtons.forEach((button) => {
        button.addEventListener('click', hideVisibleModal);
    });
}

/*
|--------------------------------------------------------------------------
| Initialization
|--------------------------------------------------------------------------
*/

export default function initializeAll() {
    initDropdowns();
    initCollapsibles();
    initAccordions();
    initModals();
}
