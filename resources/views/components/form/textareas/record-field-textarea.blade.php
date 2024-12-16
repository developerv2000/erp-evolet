@props([
    'labelText', // Label text for the input field.
    'model', // Model instance for retrieving field values.
    'field', // Model attribute to display in the input.
    'inputName' => $field, // Name for the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
    'rows' => 5, // Rows count of the input field
])

<x-form.groups.default-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <textarea
        {{ $attributes->merge(['class' => 'textarea']) }}
        name="{{ $inputName }}"
        rows={{ $rows }}
        @if ($isRequired) required @endif>{{ old($inputName, $model->{$field}) }}</textarea>
</x-form.groups.default-group>
