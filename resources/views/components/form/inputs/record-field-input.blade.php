@props([
    'labelText', // Label text for the input field.
    'model', // Model instance for retrieving field values.
    'field', // Model attribute to display in the input.
    'inputName' => $field, // Name for the input field.
    'initialValue' => $model->{$field}, // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
    'formGroupClass' => null,
])

<x-form.groups.wrapped-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired"
    :class="$formGroupClass">

    <input
        {{ $attributes->merge(['class' => 'input']) }}
        name="{{ $inputName }}"
        value="{{ old($inputName, $initialValue) }}"
        @if ($isRequired) required @endif>
</x-form.groups.wrapped-label-group>
