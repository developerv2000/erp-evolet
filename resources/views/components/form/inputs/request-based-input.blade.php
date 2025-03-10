@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'initialValue' => null, // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

<x-form.groups.wrapped-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <input
        {{ $attributes->merge(['class' => 'input'  . (request()->has($inputName) ? ' input--highlight' : '')]) }}
        name="{{ $inputName }}"
        value="{{ request()->input($inputName, $initialValue) }}"
        @if ($isRequired) required @endif>
</x-form.groups.wrapped-label-group>
