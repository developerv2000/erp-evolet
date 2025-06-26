<div class="about-order-block styled-box">
    <h2 class="main-title">{{ __('About order') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $record->process->product->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Country') }}:</td>
                <td>{{ $record->process->searchCountry->code }}</td>
            </tr>

            <tr>
                <td>{{ __('TM Eng') }}:</td>
                <td>{{ $record->process->full_trademark_en }}</td>
            </tr>

            <tr>
                <td>{{ __('TM Rus') }}:</td>
                <td>{{ $record->process->full_trademark_ru }}</td>
            </tr>

            <tr>
                <td>{{ __('MAH') }}:</td>
                <td>{{ $record->process->MAH->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Quantity') }}:</td>
                <td>{{ $record->quantity }}</td>
            </tr>
        </tbody>
    </table>
</div>
