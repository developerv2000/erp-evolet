<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="MAH"
        inputName="marketing_authorization_holder_id[]"
        :options="$MAHs" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Brand Eng"
        inputName="trademark_en"
        :options="$enTrademarks" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Brand Rus"
        inputName="trademark_ru"
        :options="$ruTrademarks" />

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

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id"
        :options="$bdmUsers" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['created_at', 'updated_at']" />
</x-filter.layout>
