@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'options', // Options to be displayed in the select field.
    'optionCaptionField' => 'caption', // Attribute of each option used as the display caption.
    'optionValueField' => 'value', // Attribute of each option used as input value.
    'initialValue' => null, // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

@php
    // Set the currently selected option value, preferring old input or the initial value.
    $selectedValue = old($inputName, $initialValue);
@endphp

<x-form.groups.radio-group
    :class="$attributes['class']"
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    {{-- Loop through the options and generate each radio button elements --}}
    @foreach ($options as $option)
        <label class="radio-group__option-label">
            <input
                class="radio radio-group__option-input"
                type="radio"
                name="{{ $inputName }}"
                @if ($isRequired) required @endif
                value="{{ $option->{$optionValueField} }}"
                @checked($option->{$optionValueField} == $selectedValue)>

            <div class="radio-group__option-caption">{{ $option->{$optionCaptionField} }}</div>
        </label>
    @endforeach
</x-form.groups.radio-group>
