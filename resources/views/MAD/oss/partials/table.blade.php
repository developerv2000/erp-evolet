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
            <th class="group-toggle" data-group="manufacturer" width="200">{{ __('Manufacturer') }}</th>
            <th class="sub-col group-manufacturer" width="130"><div class="sub-col-inner">{{ __('Shelf life') }}</div></th>
            <th class="sub-col group-manufacturer" width="100"><div class="sub-col-inner">{{ __('Moq') }}</div></th>

            @foreach ($countries as $country)
                <th class="group-toggle" data-group="{{ $country->id }}" width="100">{{ $country->code }}</th>
                <th class="sub-col group-{{ $country->id }}" width="140"><div class="sub-col-inner">{{ __('TM Eng') }}</div></th>
                <th class="sub-col group-{{ $country->id }}" width="140"><div class="sub-col-inner">{{ __('TM Rus') }}</div></th>
                <th class="sub-col group-{{ $country->id }}" width="120"><div class="sub-col-inner">{{ __('MAH') }}</div></th>
            @endforeach
        </tr>
    </x-slot:thead-rows>

    {{-- tbody --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            @php
                $processes = $record->processes;
                $manufacturers = $processes->pluck('manufacturer.name')->filter()->unique();
            @endphp

            <tr @class(['tr--even' => $loop->even])>
                <td>{{ $record->id }}</td>
                <td>{{ $record->atx?->short_name }}</td>
                <td>{{ $record->class->name }}</td>
                <td>{{ $record->inn->name }}</td>
                <td>{{ $record->form->name }}</td>
                <td>{{ $record->dosage }}</td>
                <td>{{ $record->pack }}</td>
                <td>{{ $record->manufacturer->name }}</td>
                <td class="sub-col group-manufacturer"><div class="sub-col-inner">{{ $record->shelfLife->name }}</div></td>
                <td class="sub-col group-manufacturer"><div class="sub-col-inner">{{ $record->moq }}</div></td>

                @foreach ($countries as $country)
                    @php
                        $countryProcesses = $processes->where('country_id', $country->id);
                        $mahList = $countryProcesses->pluck('MAH.name')->filter()->unique();

                        $renderList = function ($items) {
                            $items = collect($items)->filter();

                            if ($items->count() <= 1) {
                                return e($items->first() ?? '');
                            }

                            return $items->values()->map(fn($item, $i) => $i + 1 . '. ' . e($item))->implode('<br>');
                        };
                    @endphp

                    {{-- Status --}}
                    <td class="sub-col group-{{ $country->id }}"><div class="sub-col-inner">{!! $renderList($countryProcesses->pluck('status.name')) !!}</div></td>

                    {{-- TM Eng --}}
                    <td class="sub-col group-{{ $country->id }}"><div class="sub-col-inner">{!! $renderList($countryProcesses->pluck('trademark_en')) !!}</div></td>

                    {{-- TM Rus --}}
                    <td class="sub-col group-{{ $country->id }}"><div class="sub-col-inner">{!! $renderList($countryProcesses->pluck('trademark_ru')) !!}</div></td>

                    {{-- MAH --}}
                    <td class="sub-col group-{{ $country->id }}"><div class="sub-col-inner">{!! $renderList($mahList) !!}</div></td>

                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
