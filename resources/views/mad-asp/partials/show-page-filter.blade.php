<x-filter.layout>
    <x-form.selects.selectize.single-select.request-based-select
        labelText="Plan for"
        inputName="region"
        :options="$regions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Countries"
        inputName="country_ids[]"
        optionCaptionField="code"
        :options="$countriesOrderedByProcessesCount" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="PC"
        inputName="marketing_authorization_holder_id[]"
        :options="$MAHs" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Display"
        inputName="display_options[]"
        :options="$displayOptions" />
</x-filter.layout>
