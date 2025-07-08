<x-filter.layout>
    <x-form.inputs.request-based-input
        labelText="Receive date"
        inputName="receive_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Payment type"
        inputName="payment_type_id"
        :options="$paymentTypes" />

    <x-form.inputs.request-based-input
        labelText="Sent for payment date"
        inputName="sent_for_payment_date"
        class="date-range-picker-input"
        autocomplete="off" />

    <x-form.inputs.request-based-input
        labelText="Invoice №"
        inputName="number" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Order"
        inputName="order_id[]"
        :options="$ordersWithName" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
