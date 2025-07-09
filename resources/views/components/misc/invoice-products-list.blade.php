@props(['orderProducts', 'record', 'disabled' => false])

<div class="invoice-products-list-wrapper styled-box">
    <h2 class="main-title">{{ __('Products') }}</h2>

    <div class="invoice-products-list">
        @foreach ($orderProducts as $orderProduct)
            <label class="invoice-products-list__item">
                <input
                    class="checkbox"
                    type="checkbox"
                    name="order_products[]"
                    value="{{ $orderProduct->id }}"
                    @disabled($disabled)
                    @checked($record->orderProducts->contains('id', $orderProduct->id))>

                {{ $orderProduct->process->full_trademark_en }}
            </label>
        @endforeach
    </div>
</div>
