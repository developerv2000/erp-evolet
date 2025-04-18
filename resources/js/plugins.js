export const SELECTIZE_DEFAULT_OPTIONS = {
    valueField: 'id',
    labelField: 'caption',
    searchField: 'caption',
    plugins: ["auto_position", "preserve_on_blur"],
}

export function initializeSelectizes() {
    const SELECTIZE_CLASSES = {
        SINGLE_UNLINKED: 'select.single-selectize:not(.single-selectize--linked):not(.single-selectize--manually-initializable):not(.selectized)',
        SINGLE_LINKED: 'select.single-selectize--linked:not(.single-selectize--manually-initializable):not(.selectized)',
        MULTIPLE_UNTAGGABLE: 'select.multiple-selectize:not(.multiple-selectize--taggable):not(.multiple-selectize--manually-initializable):not(.selectized)',
        MULTIPLE_TAGGABLE: 'select.multiple-selectize--taggable:not(.multiple-selectize--manually-initializable):not(.selectized)',
    };

    // Single unlinked selectize
    $(SELECTIZE_CLASSES.SINGLE_UNLINKED).selectize({
        ...SELECTIZE_DEFAULT_OPTIONS,
    });

    // Single linked selectize
    $(SELECTIZE_CLASSES.SINGLE_LINKED).selectize({
        ...SELECTIZE_DEFAULT_OPTIONS,
        onChange(value) {
            window.location = value;
        },
    });

    // Multiple untaggable selectize
    $(SELECTIZE_CLASSES.MULTIPLE_UNTAGGABLE).selectize({
        ...SELECTIZE_DEFAULT_OPTIONS,
    });

    // Multiple Taggable Selectize
    $(SELECTIZE_CLASSES.MULTIPLE_TAGGABLE).selectize({
        ...SELECTIZE_DEFAULT_OPTIONS,
        create(input, callback) {
            callback({
                id: input,
                caption: input,
            });
        },
        // createOnBlur: true,
    });
}

export function initializeSimditors() {
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
            pasteImage: true,
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

export function initializeDateRangerPickers() {
    $('.date-range-picker-input').daterangepicker({
        autoUpdateInput: false, // Make picker nullable
        showDropdowns: true,
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

export default function initializeAll() {
    initializeSelectizes();
    initializeSimditors();
    initializeDateRangerPickers();
}
