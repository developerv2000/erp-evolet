@props([
    'labelText', // Label text for the input field.
    'inputName', // Name for the input field.
    'trueOptionLabel' => 'Да',
    'falseOptionLabel' => 'Нет',
    'trueOptionValue' => 1,
    'falseOptionValue' => 0,
    'initialValue' => null, // Initial value of the input field.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

@php
    // Set the currently selected option value, preferring old input or the initial value.
    $selectedValue = old($inputName, $initialValue);
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
        @unless ($isRequired)
            <option></option>
        @endunless

        <option value="{{ $trueOptionValue }}" @selected($selectedValue == $trueOptionValue)>{{ __($trueOptionLabel) }}</option>
        <option value="{{ $falseOptionValue }}" @selected(isset($selectedValue) && $selectedValue == $falseOptionValue)>{{ __($falseOptionLabel) }}</option>
    </select>

</x-form.groups.wrapped-label-group>
