<x-filter.layout>
    <x-form.inputs.request-based-input
        labelText="Status date"
        inputName="current_status_start_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Search country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Status"
        inputName="status_id[]"
        :options="$statuses" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Status An*"
        inputName="name_for_analysts[]"
        :options="$generalStatusNamesForAnalysts" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="General status"
        inputName="general_status_id[]"
        :options="$generalStatuses" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Generic"
        inputName="inn_id[]"
        :options="$inns" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Form"
        inputName="form_id[]"
        :options="$productForms" />

    <x-form.inputs.request-based-input
        labelText="Dosage"
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
        labelText="Responsible"
        inputName="responsiblePeople[]"
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
        labelText="Brand Eng"
        inputName="trademark_en" />

    <x-form.inputs.request-based-input
        labelText="Brand Rus"
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
        labelText="Contracted on ASP"
        inputName="contracted" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Registered on ASP"
        inputName="registered" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
