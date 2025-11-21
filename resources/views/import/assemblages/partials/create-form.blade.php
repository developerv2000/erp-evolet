<x-form-templates.create-template class="plpd-orders-create-form" :action="route('plpd.orders.store')">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Order') }}</h2>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Manufacturer"
                inputName="manufacturer_id"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Receive date"
                inputName="receive_date"
                type="date"
                :isRequired="true" />
        </div>
    </div>

    {{-- Multiple dynamic products holder --}}
    <div class="form__block">
        <x-form.misc.dynamic-rows title="{{ __('Products') }}" />
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
