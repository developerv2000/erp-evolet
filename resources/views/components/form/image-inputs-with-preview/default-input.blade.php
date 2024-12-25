@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'initialImageSrc' => asset('img/no-image.png'), // Initial image as preview
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

<x-form.groups.standard-label-group
    class="image-input-group-with-preview"
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    {{-- Input field --}}
    <input
        {{ $attributes->merge(['class' => 'input image-input-group-with-preview__input']) }}
        type="file"
        name="{{ $inputName }}"
        value="{{ old($inputName) }}"
        @if ($isRequired) required @endif>

    {{-- Image preview --}}
    <img class="image-input-group-with-preview__image" src="{{ $initialImageSrc }}">
</x-form.groups.standar>
