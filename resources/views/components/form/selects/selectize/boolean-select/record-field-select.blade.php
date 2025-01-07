@props([
    'labelText', // Label for the select input.
    'model', // Model instance being edited to populate the selected option.
    'field', // Model attribute to select as default.
    'trueOptionLabel' => __('Yes'),
    'falseOptionLabel' => __('No'),
    'trueOptionValue' => 1,
    'falseOptionValue' => 0,
    'inputName' => $field, // Name for the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // If true, marks the field as required.
])

@php
    // Set the currently selected option value, preferring old input or the model's current value.
    $selectedValue = old($inputName, $model->{$field});
@endphp

<x-form.groups.wrapped-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <select
        {{ $attributes->merge(['class' => 'single-selectize']) }}
        name="{{ $inputName }}"
        @if ($isRequired) required @endif>

        {{-- Empty option for placeholder --}}
        <option></option>

        <option value="{{ $trueOptionValue }}" @selected($selectedValue == $trueOptionValue)>{{ __($trueOptionLabel) }}</option>
        <option value="{{ $falseOptionValue }}" @selected(isset($selectedValue) && $selectedValue == $falseOptionValue)>{{ __($falseOptionLabel) }}</option>
    </select>

</x-form.groups.wrapped-label-group>
