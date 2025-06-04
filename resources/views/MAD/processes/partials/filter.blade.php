<x-filter.layout>
    {{-- Readonly 'contracted' inputs --}}
    @if ($request->has('contracted_on_specific_month'))
        <input type="hidden" name="contracted_on_specific_month" value="1">

        <x-form.inputs.request-based-input
            labelText="Contracted on year"
            inputName="contracted_on_year"
            readonly />

        <x-form.inputs.request-based-input
            labelText="Contracted on month"
            inputName="contracted_on_month"
            readonly />
    @endif

    {{-- Readonly 'registered' inputs --}}
    @if ($request->has('registered_on_specific_month'))
        <input type="hidden" name="registered_on_specific_month" value="1">

        <x-form.inputs.request-based-input
            labelText="Registered on year"
            inputName="registered_on_year"
            readonly />

        <x-form.inputs.request-based-input
            labelText="Registered on month"
            inputName="registered_on_month"
            readonly />
    @endif

    {{-- Readonly 'general status history' inputs --}}
    @if ($request->has('has_general_status_history'))
        <input type="hidden" name="has_general_status_history" value="1">

        <x-form.inputs.request-based-input
            labelText="Has general status history for year"
            inputName="has_general_status_for_year"
            readonly />

        <x-form.inputs.request-based-input
            labelText="Has general status history for month"
            inputName="has_general_status_for_month"
            readonly />

        <x-form.inputs.request-based-input
            labelText="Has general status history of ID"
            inputName="has_general_status_id"
            readonly />
    @endif

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

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="General status"
        inputName="general_status_id[]"
        :options="$generalStatuses" />

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

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Responsible"
        inputName="responsible_person_id"
        :options="$responsiblePeople" />

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

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Brand"
        inputName="brand[]"
        :options="$brands" />

    <x-form.inputs.request-based-input
        labelText="TM Eng"
        inputName="trademark_en" />

    <x-form.inputs.request-based-input
        labelText="TM Rus"
        inputName="trademark_ru" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Product class"
        inputName="class_id[]"
        :options="$productClasses" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Manufacturer category"
        inputName="category_id"
        :options="$manufacturerCategories" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Contracted in ASP"
        inputName="contracted_in_asp" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Registered in ASP"
        inputName="registered_in_asp" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
