@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'options', // Options to be displayed in the select field.
    'optionCaptionField' => 'name', // Attribute of each option used as the display caption.
    'initialValues' => [], // Initial values of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

@php
    // Set the currently selected option value, preferring old input or the initial value.
    $selectedValues = old(rtrim($inputName, '[]'), $initialValues);
@endphp

<x-form.groups.wrapped-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <select
        {{ $attributes->merge(['class' => 'multiple-selectize']) }}
        name="{{ $inputName }}"
        multiple
        @if ($isRequired) required @endif>

        {{-- Loop through the options and generate each option tag --}}
        @foreach ($options as $option)
            <option value="{{ $option->id }}" @selected(in_array($option->id, $selectedValues))>
                {{ $option->{$optionCaptionField} }}
            </option>
        @endforeach
    </select>

</x-form.groups.wrapped-label-group>
