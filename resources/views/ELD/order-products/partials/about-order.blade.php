<div class="about-product-block styled-box">
    <h2 class="main-title">{{ __('About product') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $order->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $order->country->code }}</td>
            </tr>

            <tr>
                <td>{{ __('Brand Eng') }}:</td>
                <td>{{ $record->process->full_trademark_en }}</td>
            </tr>

            <tr>
                <td>{{ __('MAH') }}:</td>
                <td>{{ $record->process->MAH->name }}</td>
            </tr>
        </tbody>
    </table>
</div>
