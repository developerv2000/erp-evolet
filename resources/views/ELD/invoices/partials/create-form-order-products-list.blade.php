<div class="order-products-list-wrapper styled-box">
    <h2 class="main-title">{{ __('Products') }}</h2>

    <div class="order-products-list">
        @foreach ($availabeOrderProducts as $orderProduct)
            <label class="order-products-list__item">
                <input
                    class="checkbox"
                    type="checkbox"
                    name="order_products[]"
                    value="{{ $orderProduct->id }}"
                    @disabled($paymentType->name == App\Models\InvoicePaymentType::PREPAYMENT_NAME)
                    checked >

                {{ $orderProduct->process->full_trademark_en }}
            </label>
        @endforeach
    </div>
</div>
