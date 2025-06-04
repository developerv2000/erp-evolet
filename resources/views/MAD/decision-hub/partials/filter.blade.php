<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Generic') }}"
        inputName="inn_id[]"
        :options="$smartFilterDependencies['inns']" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Manufacturer') }}"
        inputName="manufacturer_id[]"
        :options="$smartFilterDependencies['manufacturers']" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Form') }}"
        inputName="form_id[]"
        :options="$smartFilterDependencies['productForms']" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Search country') }}"
        inputName="country_id[]"
        :options="$smartFilterDependencies['countriesOrderedByProcessesCount']"
        optionCaptionField="code" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Status') }}"
        inputName="status_id[]"
        :options="$smartFilterDependencies['statuses']" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Status An*"
        inputName="name_for_analysts[]"
        :options="$generalStatusNamesForAnalysts" />

    <x-form.inputs.request-based-input
        labelText="Status date"
        inputName="active_status_start_date_range"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.inputs.request-based-input
        class="smart-filter-input"
        labelText="{{ '* ' . __('Dosage') }}"
        inputName="dosage" />

    <x-form.inputs.request-based-input
        labelText="Pack"
        inputName="pack" />

    @can('view-MAD-VPS-of-all-analysts')
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
        labelText="Manufacturer country"
        inputName="manufacturer_country_id[]"
        :options="$countriesOrderedByName" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Region"
        inputName="region"
        :options="$regions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="MAH"
        inputName="marketing_authorization_holder_id[]"
        :options="$MAHs" />

    <x-form.inputs.request-based-input
        labelText="TM Eng"
        inputName="trademark_en" />

    <x-form.inputs.request-based-input
        labelText="TM Rus"
        inputName="trademark_ru" />
</x-filter.layout>
