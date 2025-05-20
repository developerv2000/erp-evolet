<div class="about-product-block styled-box">
    <h2 class="main-title">{{ __('About product') }}</h2>

    <table class="secondary-table">
        <tbody>
            <tr>
                <td>{{ __('Manufacturer') }}:</td>
                <td>{{ $product->manufacturer->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Manufacturer country') }}:</td>
                <td>{{ $product->manufacturer->country->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Generic') }}:</td>
                <td>{{ $product->inn->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Form') }}:</td>
                <td>{{ $product->form->name }}</td>
            </tr>

            <tr>
                <td>{{ __('Dosage') }}:</td>
                <td>{{ $product->dosage }}</td>
            </tr>

            <tr>
                <td>{{ __('Pack') }}:</td>
                <td>{{ $product->pack }}</td>
            </tr>

            <tr>
                <td>{{ __('Analyst') }}:</td>
                <td>{{ $product->manufacturer->analyst->name }}</td>
            </tr>

            <tr>
                <td>{{ __('BDM') }}:</td>
                <td>{{ $product->manufacturer->bdm->name }}</td>
            </tr>

            @if (request()->routeIs('processes.edit'))
                <tr>
                    <td>{{ __('Search country') }}:</td>
                    <td>{{ $record->searchCountry->name }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
