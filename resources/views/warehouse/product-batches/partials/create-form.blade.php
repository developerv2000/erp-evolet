<x-form-templates.create-template class="warehouse-product-batches-create-form" :action="route('warehouse.product-batches.store')">
    <input type="hidden" name="order_product_id" value="{{ $product->id }}">
    <input type="hidden" name="multiple_batches" value="{{ $multipleBatches }}">

    <div class="form__block">
        {{-- Multiple batches --}}
        @if ($multipleBatches)
            <x-form.misc.dynamic-rows
                title="{{ __('Batches') . '. ' . __('Remaining') . ': ' . $product->remaining_quantity_for_batches }}">
                @include('warehouse.product-batches.partials.create-form-dynamic-rows-list-item', ['inputsIndex' => 0])
            </x-form.misc.dynamic-rows>
        @else
            {{-- Close with single batch --}}
            <h1 class="main-title main-title--marginless">
                {{ __('Close with single batch') }}
            </h1>

            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="Series"
                    inputName="series"
                    :isRequired="true" />

                <x-form.inputs.default-input
                    labelText="Quantity"
                    inputName="readonly_quantity"
                    :value="$product->remaining_quantity_for_batches"
                    disabled />
            </div>

            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="Manufacturing date"
                    inputName="manufacturing_date"
                    type="date" />

                <x-form.inputs.default-input
                    labelText="Expiration date"
                    inputName="expiration_date"
                    type="date" />
            </div>
        @endif
    </div>
</x-form-templates.create-template>
