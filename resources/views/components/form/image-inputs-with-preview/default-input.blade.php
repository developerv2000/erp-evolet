@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'initialImageSrc' => asset('img/dashboard/default-image.png'), // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

<x-form.groups.image-input-group-with-preview
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :initialImageSrc="$initialImageSrc"
    :isRequired="$isRequired">

    <input
        {{ $attributes->merge(['class' => 'input image-input-group-with-preview__input']) }}
        type="file"
        name="{{ $inputName }}"
        value="{{ old($inputName) }}"
        @if ($isRequired) required @endif>
</x-form.groups.image-input-group-with-preview>
