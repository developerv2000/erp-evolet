<x-filter.layout>
    <x-form.inputs.request-based-input
        labelText="Year"
        inputName="year"
        type="number"
        min="1" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Analyst"
        inputName="analyst_user_id[]"
        :options="$analystUsers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id[]"
        :options="$bdmUsers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByName" />

    <x-form.inputs.request-based-input
        labelText="Who met"
        inputName="who_met" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['id']" />
</x-filter.layout>
