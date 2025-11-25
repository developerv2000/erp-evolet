<x-filter.layout>
    <x-form.inputs.request-based-input
        labelText="Assemblage №"
        inputName="number" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
