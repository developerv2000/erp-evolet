<div class="about-order-block styled-box">
    <h2 class="main-title">{{ __('About product') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('PO №') }}:</td>
                <td>{{ $order->title }}</td>
            </tr>

            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $order->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $order->country->code }}</td>
            </tr>

            <tr>
                <td>{{ __('TM Eng') }}:</td>
                <td>{{ $record->process->trademark_en }}</td>
            </tr>

            <tr>
                <td>{{ __('TM Rus') }}:</td>
                <td>{{ $record->process->trademark_ru }}</td>
            </tr>

            <tr>
                <td>{{ __('Generic') }}:</td>
                <td>{{ $record->process->product->inn->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Form') }}:</td>
                <td>{{ $record->process->product->form->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Dosage') }}:</td>
                <td>{{ $record->process->product->dosage }}</td>
            </tr>

            <tr>
                <td>{{ __('Pack') }}:</td>
                <td>{{ $record->process->product->pack }}</td>
            </tr>
        </tbody>
    </table>
</div>
