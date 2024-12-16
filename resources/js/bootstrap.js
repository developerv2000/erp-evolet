/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import axios from 'axios';
import '../../custom-components/script';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/*
|--------------------------------------------------------------------------
| Plugin initialization Functions
|--------------------------------------------------------------------------
*/

const SELECTIZE_CLASSES = {
    SINGLE_UNLINKED: '.single-selectize:not(.single-selectize--linked):not(.single-selectize--manually-initializable)',
    SINGLE_LINKED: '.single-selectize--linked:not(.single-selectize--manually-initializable)',
    MULTIPLE_UNTAGGABLE: '.multiple-selectize:not(.multiple-selectize--taggable):not(.multiple-selectize--manually-initializable)',
    MULTIPLE_TAGGABLE: '.multiple-selectize--taggable:not(.multiple-selectize--manually-initializable)',
};

/**
 * Initializes the selectize components based on class selectors.
 */
function initializeSelectizes() {
    // Single unlinked selectize
    $(SELECTIZE_CLASSES.SINGLE_UNLINKED).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
    });

    // Single linked selectize
    $(SELECTIZE_CLASSES.SINGLE_LINKED).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
        onChange(value) {
            window.location = value;
        },
    });

    // Multiple untaggable selectize
    $(SELECTIZE_CLASSES.MULTIPLE_UNTAGGABLE).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
    });

    // Multiple Taggable Selectize
    $(SELECTIZE_CLASSES.MULTIPLE_TAGGABLE).selectize({
        plugins: ["auto_position", "preserve_on_blur"],
        create(input, callback) {
            callback({
                value: input,
                text: input,
            });
        },
        // createOnBlur: true,
    });
}

/**
 * Initializes Simditor editors based on class selectors.
 */
function initializeSimditors() {
    Simditor.locale = 'ru-RU';

    const simpleToolbar = ['title', 'bold', 'italic', 'underline', 'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link', 'hr', '|', 'indent', 'outdent', 'alignment'];
    const imageToolbar = [...simpleToolbar, 'image'];

    const simditorConfigs = {
        toolbarFloatOffset: '60px',
        toolbar: simpleToolbar,
        imageButton: 'upload',
    };

    // Unimaged simditor
    document.querySelectorAll('.simditor:not(.simditor--imaged)').forEach(textarea => {
        new Simditor({ ...simditorConfigs, textarea });
    });

    // Imaged simditor
    document.querySelectorAll('.simditor--imaged').forEach(textarea => {
        new Simditor({
            ...simditorConfigs,
            textarea,
            toolbar: imageToolbar,
            upload: {
                url: '/simditor/upload-image',
                fileKey: 'image',
                connectionCount: 10,
                leaveConfirm: 'Пожалуйста дождитесь окончания загрузки изображений на сервер! Вы уверены что хотите закрыть страницу?',
            },
            defaultImage: '/img/dashboard/simditor-default-image.png',
        });
    });
}

function initializeDateRangerPickers() {
    $('.date-range-picker-input').daterangepicker({
        autoUpdateInput: false, // Make picker nullable
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY' // Change default format
        },
    });

    // Set input value with updated format on apply
    $('.date-range-picker-input').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    // Make picker nullable
    $('.date-range-picker-input').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
}

/**
 * Requires refactoring
 */
function initializeNestedsets() {
    // Initialize nestedset container
    $('.nested').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        excludeRoot: true,
        maxLevels: 2,
        isTree: true,
        expandOnHover: 700,
        startCollapsed: true,
        branchClass: 'nested__item--parent',
        leafClass: 'nested__item--leaf',
        collapsedClass: 'nested__item--collapsed',
        expandedClass: 'nested__item--expanded',
        hoveringClass: 'nested__item--hover',
    });

    // Initialize nestedset collapse togglers
    document.querySelectorAll('.nested__item-toggler').forEach((item) => {
        item.addEventListener('click', (evt) => {
            let item = evt.target.closest('.nested__item');
            item.classList.toggle('nested__item--collapsed');
            item.classList.toggle('nested__item--expanded');
        });
    });

    // Initialize delete buttons
    document.querySelectorAll('.nested__item-destroy-btn').forEach((item) => {
        item.addEventListener('click', (evt) => {
            evt.target.closest('.nested__item').remove();
        });
    });
}

/*
|--------------------------------------------------------------------------
| Initializations
|--------------------------------------------------------------------------
*/

/**
 * Initializes all plugin components.
 */
function init() {
    initializeSelectizes();
    initializeSimditors();
    initializeDateRangerPickers();
    initializeNestedsets();
}

init();
