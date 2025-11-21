<x-tables.template.main-template :records="$records">
    {{-- thead --}}
    <x-slot:thead-rows>
        <tr>
            <th width="100">ID</th>
            <th width="100">ATX</th>
            <th width="100">{{ __('Product class') }}</th>
            <th width="200">{{ __('Generic') }}</th>
            <th width="100">{{ __('Form') }}</th>
            <th width="100">{{ __('Dosage') }}</th>
            <th width="100">{{ __('Pack') }}</th>
            <th width="200">{{ __('Manufacturer') }}</th>
            <th width="130">{{ __('Shelf life') }}</th>
            <th width="100">{{ __('Moq') }}</th>
            <th width="120">{{ __('MAH') }}</th>

            @foreach ($countries as $country)
                <th width="100">{{ $country->code }}</th>
                <th width="140">{{ __('TM Eng') }}</th>
                <th width="140">{{ __('TM Rus') }}</th>
            @endforeach

        </tr>
    </x-slot:thead-rows>

    {{-- tbody --}}
    <x-slot:tbody-rows>
    @foreach ($records as $record)
        @foreach ($record->grouped_processes as $groupedProcesses)
            <tr @class(['tr--even' => $loop->parent->even])>
                <td>{{ $record->id }}</td>
                <td>{{ $record->atx?->short_name }}</td>
                <td>{{ $record->class->name }}</td>
                <td>{{ $record->inn->name }}</td>
                <td>{{ $record->form->name }}</td>
                <td>{{ $record->dosage }}</td>
                <td>{{ $record->pack }}</td>
                <td>{{ $record->manufacturer->name }}</td>
                <td>{{ $record->shelfLife->name }}</td>
                <td>{{ $record->moq }}</td>

                <td>{{ $groupedProcesses->first()->MAH?->name }}</td>

                @foreach ($countries as $country)
                    @php
                        $match = $groupedProcesses->firstWhere('country_id', $country->id);
                    @endphp

                    <td>{{ $match?->status->name }}</td>
                    <td>{{ $match?->trademark_en }}</td>
                    <td>{{ $match?->trademark_ru }}</td>
                @endforeach
            </tr>
        @endforeach
    @endforeach

    </x-slot:tbody-rows>
</x-tables.template.main-template>
