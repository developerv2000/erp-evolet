<x-filter.layout>
    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="PO №"
        inputName="order_name[]"
        :options="$orderNames" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.single-select.request-based-select
        labelText="Status"
        inputName="status"
        :options="$statusOptions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    {{-- <x-form.inputs.request-based-input
        :label-text="__('Order') . ' ID'"
        inputName="order_id" />

    <x-form.inputs.request-based-input
        :label-text="__('Process') . ' ID'"
        inputName="process_id" /> --}}

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
