<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByUsageCount"
        optionCaptionField="code" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Source EU"
        inputName="source_eu" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Source IN"
        inputName="source_in" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Status"
        inputName="status_id"
        :options="$statuses" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Priority"
        inputName="priority_id"
        :options="$priorities" />

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

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="MAH"
        inputName="marketing_authorization_holder_id[]"
        :options="$MAHs" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Portfolio manager"
        inputName="portfolio_manager_id[]"
        :options="$portfolioManagers" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Analyst"
        inputName="analyst_user_id"
        :options="$analystUsers" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
