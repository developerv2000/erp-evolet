<x-form-templates.edit-template class="plpd-order-products-edit-form" :action="route('plpd.order-products.update', $record->id)">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Product') }}</h2>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="TM Eng"
                field="temporary_process_id"
                :model="$record"
                :options="$readyForOrderProcesses"
                optionCaptionField="full_trademark_en_with_id"
                :initial-value="$record->process->id"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="process_id"
                :model="$record"
                :options="$processWithItSimilarRecords"
                optionCaptionField="mah_name_with_id"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Quantity"
                field="quantity"
                :model="$record"
                type="number"
                min="0"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Serialization type"
                field="serialization_type_id"
                :model="$record"
                :options="$serializationTypes"
                :isRequired="true" />
        </div>

        {{-- CMD part --}}
        @if ($record->order->is_sent_to_confirmation)
            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Price"
                    field="price"
                    :model="$record"
                    type="number"
                    step="0.01"
                    min="0.00"
                    :isRequired="true" />

                <div class="form-group"></div>
                <div class="form-group"></div>
            </div>
        @endif
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
