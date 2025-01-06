/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import axios from 'axios';
import '../custom-components/script';

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

    const simpleToolbar = ['title', 'bold', 'italic', 'underline', 'color', '|', 'ol', 'ul', 'table', '|', 'link', '|', 'indent', 'outdent', 'alignment'];
    const imageToolbar = [...simpleToolbar, 'image'];

    const simditorConfigs = {
        toolbarFloatOffset: '60px',
        toolbar: simpleToolbar,
        imageButton: 'upload',
    };

    // Simple simditor without image upload
    document.querySelectorAll('.simditor:not(.simditor--imaged)').forEach(textarea => {
        new Simditor({ ...simditorConfigs, textarea });
    });

    // Image-uploadable simditor
    document.querySelectorAll('.simditor--image-uploadable').forEach(textarea => {
        new Simditor({
            ...simditorConfigs,
            textarea,
            toolbar: imageToolbar,
            upload: {
                url: '/upload-simditor-image', // route url
                fileKey: 'image', // $request->file('image')
                connectionCount: 10,
                leaveConfirm: 'Пожалуйста дождитесь окончания загрузки изображений на сервер! Вы уверены что хотите закрыть страницу?',
                params: {
                    _token: document.querySelector('meta[name="csrf-token"]').content, // CSRF token
                    folder: textarea.dataset.imageUploadFolder, // Folder to save images
                }
            },
            defaultImage: '/img/form/uploading-image.png', // Default image while uploading
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
}

init();
