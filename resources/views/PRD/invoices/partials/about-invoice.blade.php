<div class="about-invoice-block styled-box">
    <h2 class="main-title">{{ __('About invoice') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Order') }}:</td>
                <td>{{ $record->order->title }}</td>
            </tr>

            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $record->order->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $record->order->country->code }}</td>
            </tr>

            <tr>
                <td>{{ __('Payment type') }}:</td>
                <td>{{ $record->paymentType->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Receive date') }}:</td>
                <td>{{ $record->receive_date->isoformat('DD MMM Y') }}</td>
            </tr>

            <tr>
                <td>{{ __('Sent for payment date') }}:</td>
                <td>{{ $record->sent_for_payment_date->isoformat('DD MMM Y') }}</td>
            </tr>
        </tbody>
    </table>
</div>
