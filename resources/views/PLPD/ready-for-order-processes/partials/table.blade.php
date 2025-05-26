<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="158">{{ __('BDM') }}</th>

            <th width="62">
                <x-tables.partials.th.id />
            </th>

            <th width="144">
                <x-tables.partials.th.order-link text="Readiness date" order-by="readiness_for_order_date" />
            </th>

            <th width="130">{{ __('Brand Eng') }}</th>
            <th width="130">{{ __('Brand Rus') }}</th>
            <th width="110">{{ __('Form') }}</th>
            <th width="110">{{ __('Dosage') }}</th>
            <th width="110">{{ __('Pack') }}</th>
            <th width="180">{{ __('Manufacturer') }}</th>

            <th width="84">
                <x-tables.partials.th.order-link text="Country" order-by="country_id" />
            </th>

            <th width="102">
                <x-tables.partials.th.order-link text="MAH" order-by="marketing_authorization_holder_id" />
            </th>

            <th width="160">{{ __('Generic') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td>
                    <x-misc.ava
                        image="{{ $record->product->manufacturer->bdm->photo_asset_url }}"
                        title="{{ $record->product->manufacturer->bdm->name }}" />
                </td>

                <td>{{ $record->id }}</td>
                <td>{{ $record->readiness_for_order_date->isoformat('DD MMM Y') }}</td>
                <td>{{ $record->trademark_en }}</td>
                <td>{{ $record->trademark_ru }}</td>
                <td>{{ $record->product->form->name }}</td>
                <td><x-tables.partials.td.max-lines-limited-text :text="$record->product->dosage" /></td>
                <td>{{ $record->product->pack }}</td>
                <td>{{ $record->product->manufacturer->name }}</td>
                <td>{{ $record->searchCountry->code }}</td>
                <td>{{ $record->MAH?->name }}</td>
                <td><x-tables.partials.td.max-lines-limited-text :text="$record->product->inn->name" /></td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
