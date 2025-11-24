<x-form-templates.edit-template class="plpd-orders-edit-form" :action="route('plpd.orders.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Manufacturer"
                field="manufacturer_id"
                :model="$record"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Country"
                field="country_id"
                :model="$record"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Receive date"
                field="receive_date"
                :model="$record"
                type="date"
                :initial-value="$record->receive_date->format('Y-m-d')"
                :isRequired="true" />
        </div>
    </div>

    {{-- CMD part --}}
    @if ($record->is_sent_to_confirmation)
        <div class="form__block">
            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="PO №"
                    field="name"
                    :model="$record"
                    :isRequired="true" />

                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Currency"
                    field="currency_id"
                    :model="$record"
                    :options="$currencies"
                    :isRequired="true" />

                <div class="form-group"></div>
            </div>
        </div>
    @endif

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
