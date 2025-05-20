<x-filter.layout>
    <x-form.inputs.request-based-input
        labelText="Year"
        inputName="year"
        type="number" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Months"
        inputName="months[]"
        :options="$months->pluck('name')" />

    @can('view-MAD-KPI-of-all-analysts')
        <x-form.selects.selectize.id-based-single-select.request-based-select
            labelText="Analyst"
            inputName="analyst_user_id"
            :options="$analystUsers" />
    @endcan

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id"
        :options="$bdmUsers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Search country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Region"
        inputName="region"
        :options="$regions" />

    @can('view-MAD-extended-KPI-version')
        <x-form.selects.selectize.boolean-select.request-based-select
            labelText="Extensive version"
            inputName="extensive_version" />
    @endcan
</x-filter.layout>
