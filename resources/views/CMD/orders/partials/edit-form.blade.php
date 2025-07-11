<x-form-templates.edit-template :action="route('cmd.orders.update', $record->id)">
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Order') }}</h3>

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
                :initial-value="$record->currency_id ?: $defaultSelectedCurrencyID"
                :isRequired="true" />

            @if ($record->is_sent_to_manufacturer)
                <x-form.inputs.default-input
                    labelText="Expected dispatch date"
                    inputName="expected_dispatch_date" />
            @endif
        </div>
    </div>

    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Products') }}</h3>

        @foreach ($record->products as $product)
            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="TM Eng"
                    inputName="readonly_trademark"
                    :initial-value="$product->process->full_trademark_en_with_id"
                    readonly />

                <x-form.inputs.default-input
                    labelText="MAH"
                    inputName="readonly_mah"
                    :initial-value="$product->process->MAH->name"
                    readonly />

                @if (!$record->production_is_started)
                    <x-form.inputs.default-input
                        labelText="Quantity"
                        inputName="readonly_quantity"
                        :initial-value="$product->quantity"
                        readonly />
                @endif

                <x-form.inputs.record-field-input
                    labelText="Price"
                    :field="'products[' . $product->id . '][price]'"
                    :model="$product"
                    :initial-value="$product->price ?: $product->process->agreed_price"
                    type="number"
                    step="0.01"
                    min="0.00"
                    :isRequired="true" />

                @if ($record->production_is_started)
                    <x-form.inputs.record-field-input
                        labelText="Production status"
                        :input-name="'products[' . $product->id . '][production_status]'"
                        field="production_status"
                        :model="$product" />
                @endif
            </div>
        @endforeach
    </div>

    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Comments') }}</h3>
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
