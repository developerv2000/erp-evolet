@props([
    'labelText', // Label for the select input.
    'model', // Model instance being edited to populate the selected option.
    'field', // Model attribute to select as default.
    'options', // Collection of available options for selection.
    'optionCaptionField' => 'name', // Attribute used to display option text.
    'inputName' => $field, // Name for the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // If true, marks the field as required.
])

@php
    // Set the currently selected option value, preferring old input or the model's current value.
    $selectedValue = old($inputName, $model->{$field});
@endphp

<x-form.groups.default-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <select
        {{ $attributes->merge(['class' => 'single-selectize']) }}
        name="{{ $inputName }}"
        @if ($isRequired) required @endif>

        {{-- Empty option for placeholder --}}
        @unless ($isRequired)
            <option></option>
        @endunless

        {{-- Generate options dynamically from the provided collection --}}
        @foreach ($options as $option)
            <option value="{{ $option->id }}" @selected($option->id == $selectedValue)>
                {{ $option->{$optionCaptionField} }}
            </option>
        @endforeach
    </select>

</x-form.groups.default-group>
