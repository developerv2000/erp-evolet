<x-form-templates.edit-template class="export-assemblages-edit-form" :action="route('export.assemblages.update', $record->id)">
    {{-- PLPD part --}}
    @can('moderate-export-assemblages')
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Main') }}</h2>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Assemblage №"
                    field="number"
                    :model="$record"
                    :isRequired="true" />

                <x-form.inputs.record-field-input
                    labelText="Assemblage date"
                    field="assemblage_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->assemblage_date->format('Y-m-d')"
                    :isRequired="true" />

                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Method of shipment"
                    field="shipment_type_id"
                    :model="$record"
                    :options="$shipmentTypes"
                    :isRequired="true" />

                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Country"
                    field="country_id"
                    :model="$record"
                    :options="$countriesOrderedByProcessesCount"
                    optionCaptionField="code"
                    :isRequired="true" />
            </div>
        </div>
    @endcan

    {{-- ELD part --}}
    @if ($record->assembly_request_date)
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Request acceptance') }}</h2>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Initial assembly date"
                    field="initial_assembly_acceptance_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->initial_assembly_acceptance_date?->format('Y-m-d')" />

                <x-form.inputs.default-input
                    labelText="Initial assembly"
                    inputName="initial_assembly_file"
                    type="file" />

                <x-form.inputs.record-field-input
                    labelText="Final assembly date"
                    field="final_assembly_acceptance_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->final_assembly_acceptance_date?->format('Y-m-d')" />

                <x-form.inputs.default-input
                    labelText="Final assembly"
                    inputName="final_assembly_file"
                    type="file" />
            </div>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="COO date"
                    field="coo_file_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->coo_file_date?->format('Y-m-d')" />

                <x-form.inputs.default-input
                    labelText="COO"
                    inputName="coo_file"
                    type="file" />

                <x-form.inputs.record-field-input
                    labelText="EURO 1 date"
                    field="euro_1_file_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->euro_1_file_date?->format('Y-m-d')" />

                <x-form.inputs.default-input
                    labelText="EURO 1"
                    inputName="euro_1_file"
                    type="file" />
            </div>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Documents provision date"
                    field="documents_provision_date_to_warehouse"
                    :model="$record"
                    type="date"
                    :initial-value="$record->documents_provision_date_to_warehouse?->format('Y-m-d')" />

                <x-form.inputs.default-input
                    labelText="GMP or ISO"
                    inputName="gmp_or_iso_file"
                    type="file" />

                <x-form.inputs.record-field-input
                    labelText="Volume"
                    field="volume"
                    type="number"
                    :model="$record"
                    min="1.00"
                    step="0.01" />

                <x-form.inputs.record-field-input
                    labelText="Packs"
                    field="packs"
                    :model="$record" />
            </div>
        </div>
    @endif

    @if ($record->delivery_to_destination_country_request_date)
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Transportation request') }}</h2>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Rate approved"
                    field="delivery_to_destination_country_rate_approved_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->delivery_to_destination_country_rate_approved_date?->format('Y-m-d')" />

                <x-form.inputs.record-field-input
                    labelText="Forwarder"
                    field="delivery_to_destination_country_forwarder"
                    :model="$record" />

                <x-form.inputs.record-field-input
                    labelText="Rate"
                    field="delivery_to_destination_country_price"
                    type="number"
                    :model="$record"
                    min="1" />

                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Currency"
                    field="delivery_to_destination_country_currency_id"
                    :model="$record"
                    :options="$currencies" />
            </div>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Loading confirmed"
                    field="delivery_to_destination_country_loading_confirmed_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->delivery_to_destination_country_loading_confirmed_date?->format('Y-m-d')" />

                <div class="form-group"></div>
                <div class="form-group"></div>
                <div class="form-group"></div>
            </div>
        </div>
    @endif

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
