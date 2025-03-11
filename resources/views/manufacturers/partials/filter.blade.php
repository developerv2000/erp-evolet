<x-filter.layout>
    {{-- Readonly 'active' inputs --}}
    @if ($request->has('has_active_processes_for_specific_month'))
        <input type="hidden" name="has_active_processes_for_specific_month" value="1">

        <x-form.inputs.request-based-input
            labelText="Has active processes for year"
            inputName="has_active_processes_for_year"
            readonly />

        <x-form.inputs.request-based-input
            labelText="Has active processes for month"
            inputName="has_active_processes_for_month"
            readonly />
    @endif

    <x-form.selects.selectize.id-based-single-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Analyst') }}"
        inputName="analyst_user_id"
        :options="$smartFilterDependencies['analystUsers']" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Country') }}"
        inputName="country_id[]"
        :options="$smartFilterDependencies['countriesOrderedByName']" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        class="smart-filter-select"
        labelText="{{ '* ' . __('Manufacturer') }}"
        inputName="id[]"
        :options="$smartFilterDependencies['manufacturers']" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id"
        :options="$bdmUsers" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Region"
        inputName="region"
        :options="$regions" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Category"
        inputName="category_id"
        :options="$categories" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Status"
        inputName="active"
        :trueOptionLabel="__('Active')"
        :falseOptionLabel="__('Stop/pause')" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Product class"
        inputName="productClasses[]"
        :options="$productClasses" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Zones"
        inputName="zones[]"
        :options="$zones" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Important"
        inputName="important" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Has VPS for country"
        inputName="process_country_id[]"
        optionCaptionField="code"
        :options="$countriesOrderedByProcessesCount" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Blacklist"
        inputName="blacklists[]"
        :options="$blacklists" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['id']" />
</x-filter.layout>
