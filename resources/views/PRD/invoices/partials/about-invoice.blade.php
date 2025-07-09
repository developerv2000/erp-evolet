<div class="about-invoice-block styled-box">
    <h2 class="main-title">{{ __('About invoice') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td width="50%">{{ __('Order') }}:</td>
                <td width="50%">{{ $record->order->title }}</td>
            </tr>

            <tr>
                <td width="50%">{{ __('Manufacturer') }}:</td>
                <td width="50%">{{ $record->order->manufacturer->name }}</td>
            </tr>

            <tr>
                <td width="50%">{{ __('Country') }}:</td>
                <td width="50%">{{ $record->order->country->code }}</td>
            </tr>

            <tr>
                <td width="50%">{{ __('Payment type') }}:</td>
                <td width="50%">{{ $record->paymentType->name }}</td>
            </tr>

            <tr>
                <td width="50%">{{ __('Receive date') }}:</td>
                <td width="50%">{{ $record->receive_date->isoformat('DD MMM Y') }}</td>
            </tr>

            <tr>
                <td width="50%">{{ __('Sent for payment date') }}:</td>
                <td width="50%">{{ $record->sent_for_payment_date->isoformat('DD MMM Y') }}</td>
            </tr>
        </tbody>
    </table>
</div>
