<x-filter.layout>
    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="PO №"
        inputName="name[]"
        :options="$orderNames" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['created_at', 'updated_at']" />
</x-filter.layout>
