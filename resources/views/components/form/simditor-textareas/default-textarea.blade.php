@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'initialValue' => null, // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
    'rows' => 5, // Rows count of the input field
])

{{-- Important: Standard label group is used instead of Wrapped label group --}}

<x-form.groups.standard-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <textarea
        {{ $attributes->merge(['class' => 'textarea simditor']) }}
        name="{{ $inputName }}"
        rows={{ $rows }}
        @if ($isRequired) required @endif>{{ old($inputName, $initialValue) }}</textarea>
</x-form.groups.standard-label-group>
