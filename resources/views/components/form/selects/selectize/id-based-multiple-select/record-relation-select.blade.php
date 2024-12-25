@props([
    'labelText', // Label text for the input field.
    'model', // Model instance being edited to populate the selected option.
    'inputName', // Name for the input field.
    'options', // Options to be displayed in the select field.
    'optionCaptionField' => 'name', // Attribute of each option used as the display caption.
    'relationName' => str_replace('[]', '', $inputName), // Relationship name on the model.
    'validationErrorKey' => null, // Validation error bag key, if any.
    'isRequired' => false, // Determines if the field is required.
])

@php
    // Retrieve selected values, preferring old input data or the model's related IDs.
    $relatedIds = $model->{$relationName}->pluck('id')->toArray();
    $selectedValues = old(rtrim($inputName, '[]'), $relatedIds);
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
