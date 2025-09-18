<x-form-templates.create-template class="warehouse-product-batches-create-form" :action="route('warehouse.product-batches.store')">
    <input type="hidden" name="order_product_id" value="{{ $product->id }}">

    <div class="form__block">
        <h1 class="main-title main-title--marginless">
            {{ __('Remaining quantity for batches') }}:
            <strong>{{ $product->remaining_quantity_for_batches }}</strong>
        </h1>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Series"
                inputName="series"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Quantity"
                inputName="quantity"
                :isRequired="true"
                type="number"
                :max="$product->remaining_quantity_for_batches" />
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
    </div>
</x-form-templates.create-template>
