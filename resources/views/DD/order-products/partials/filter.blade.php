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

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="TM Eng"
        inputName="trademark_en[]"
        :options="$enTrademarks" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="PO №"
        inputName="order_name[]"
        :options="$orderNames" />

    <x-form.inputs.request-based-input
        labelText="Sent to manufacturer"
        inputName="sent_to_manufacturer_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Layout status"
        inputName="new_layout"
        :trueOptionLabel="__('New')"
        :falseOptionLabel="__('No changes')" />

    <x-form.inputs.request-based-input
        labelText="Layout sent date"
        inputName="date_of_sending_new_layout_to_manufacturer"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.inputs.request-based-input
        labelText="Print proof receive date"
        inputName="date_of_receiving_print_proof_from_manufacturer"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.inputs.request-based-input
        labelText="Layout approved date"
        inputName="layout_approved_date"
        class="date-range-picker-input"
        autocomplete="off" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
