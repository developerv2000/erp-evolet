<x-tables.template.main-template :records="$MAHs" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>
            <th width="40"><x-tables.partials.th.edit /></th>

            <th width="60">{{ __('Name') }}</th>

            @foreach ($months as $month)
                <th width="120">{{ __($month['name']) . ' EU Кк' }}</th>
                <th width="120">{{ __($month['name']) . ' IN Кк' }}</th>
            @endforeach
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($MAHs as $mah)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$mah->id" /></td>
                <td><x-tables.partials.td.edit :link="route('mad.asp.mahs.edit', ['record' => $record->year, 'country' => $country->id, 'mah' => $mah->id])" /></td>
                <td>{{ $mah->name }}</td>

                @foreach ($months as $month)
                    <td>{{ $mah->pivot[$month['name'] . '_europe_contract_plan'] }}</td>
                    <td>{{ $mah->pivot[$month['name'] . '_india_contract_plan'] }}</td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
