<div class="about-order-block styled-box">
    <h2 class="main-title">{{ __('About order') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Order') }}:</td>
                <td>{{ $order->title }}</td>
            </tr>

            <tr>
                <td>{{ __('Receive date') }}:</td>
                <td>{{ $order->receive_date }}</td>
            </tr>

            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $order->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $order->manufacturer->country->code }}</td>
            </tr>
        </tbody>
    </table>
</div>
