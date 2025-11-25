<x-form-templates.create-template class="export-assemblages-create-form" :action="route('export.assemblages.store')">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Assemblage') }}</h2>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Assemblage №"
                inputName="number"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Assemblage date"
                inputName="assemblage_date"
                type="date"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Method of shipment"
                inputName="shipment_type_id"
                :options="$shipmentTypes"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />
        </div>
    </div>

    {{-- Multiple dynamic batches holder --}}
    <div class="form__block">
        <x-form.misc.dynamic-rows title="{{ __('Batches') }}" />
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
