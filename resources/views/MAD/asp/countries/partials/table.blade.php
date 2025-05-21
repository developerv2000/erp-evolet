<x-tables.template.main-template :records="$record->countries" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>

            <th>{{ __('Name') }}</th>
            <th>{{ __('MAH') }}</th>
            <th>{{ __('Edit') }} {{ __('MAH') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($record->countries as $country)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$country->id" /></td>
                <td>{{ $country->code }}</td>

                <td>
                    @foreach ($country->MAHs as $mah)
                        <a
                            class="main-link"
                            href="{{ route('mad.asp.mahs.edit', ['record' => $record->year, 'country' => $country->id, 'mah' => $mah->id]) }}">
                            {{ $mah->name }}
                        </a>
                    @endforeach
                </td>

                <td>
                    <a class="main-link" href="{{ route('mad.asp.mahs.index', ['record' => $record->year, 'country' => $country->id]) }}">
                        {{ $country->MAHs->count() }} {{ __('MAH') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
