@props([
    'labelText', // Label text for the input field.
    'model', // Model instance being edited to populate the selected option.
    'field', // Model attribute to select as default.
    'options', // Options to be displayed in the select field.
    'inputName' => $field . '[]', // Name for the input field.
    'taggable' => false, // Whether user can and new options or not
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

@php
    // Retrieve selected values, preferring old input data or the model's field values.
    $selectedValues = old(rtrim($inputName, '[]'), $model->{$field});
@endphp

<x-form.groups.wrapped-label-group
    :labelText="$labelText"
    :errorFieldName="$inputName"
    :validationErrorKey="$validationErrorKey"
    :isRequired="$isRequired">

    <select
        {{ $attributes->merge(['class' => 'multiple-selectize' . ($taggable ? ' multiple-selectize--taggable' : '')]) }}
        name="{{ $inputName }}"
        multiple
        @if ($isRequired) required @endif>

        {{-- Loop through the options and generate each option tag --}}
        @foreach ($options as $option)
            <option value="{{ $option }}" @selected(in_array($option, $selectedValues))>
                {{ $option }}
            </option>
        @endforeach
    </select>

</x-form.groups.wrapped-label-group>
