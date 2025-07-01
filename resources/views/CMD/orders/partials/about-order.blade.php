<div class="about-order-block styled-box">
    <h2 class="main-title">{{ __('About order') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Order') }}:</td>
                <td>{{ $record->title }}</td>
            </tr>

            <tr>
                <td>{{ __('Receive date') }}:</td>
                <td>{{ $record->receive_date->isoformat('DD MMM Y') }}</td>
            </tr>

            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $record->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $record->country->code }}</td>
            </tr>
        </tbody>
    </table>
</div>
