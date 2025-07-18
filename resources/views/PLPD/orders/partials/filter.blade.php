<x-filter.layout>
    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="PO №"
        inputName="name[]"
        :options="$orderNames" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id"
        :options="$bdmUsers" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Status"
        inputName="status"
        :options="$statusOptions" />

    <x-form.inputs.request-based-input
        labelText="Receive date"
        inputName="receive_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    <x-form.inputs.request-based-input
        labelText="Sent to BDM"
        inputName="sent_to_bdm_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.inputs.request-based-input
        labelText="Sent to manufacturer"
        inputName="sent_to_manufacturer_date"
        class="date-range-picker-input"
        autocomplete="off" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
