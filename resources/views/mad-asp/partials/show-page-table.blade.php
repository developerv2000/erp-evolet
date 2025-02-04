<x-tables.template.main-template class="mad-asp-table" :records="$record->countries" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        {{-- thead row 1 --}}
        <tr>
            <th class="mad-asp-table__th--country-name">{{ __('Cтр') }}</th>
            <th class="mad-asp-table__th--mah-name">{{ __('PC') }}</th>
            <th colspan="5">{{ __('Plan') }} {{ $record->year }}</th>

            @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                {{-- Quarters 1-4 --}}
                @if ($displayQuarters)
                    <th width="270" colspan="3">{{ __('Quarter') }} {{ $quarter }}</th>
                @endif

                {{-- Monthes 1-12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++)
                        <th width="240" colspan="3">{{ __($months[$monthIndex++]['name']) }}</th>
                    @endfor
                @endif
            @endfor
        </tr>

        {{-- thead row 2 --}}
        <tr>
            <th class="mad-asp-table__th--country-name"></th>
            <th class="mad-asp-table__th--mah-name"></th>

            {{-- Plan for the year --}}
            <th>Кк {{ __('plan') }}</th>
            <th>Кк {{ __('fact') }}</th>
            <th>Кк %</th>
            <th>НПР {{ __('fact') }}</th>
            <th>НПР %</th>

            @for ($quarter = 1; $quarter <= 4; $quarter++)
                {{-- Quarters 1 - 4 --}}
                @if ($displayQuarters)
                    <th>Кк {{ __('plan') }}</th>
                    <th>Кк {{ __('fact') }}</th>
                    <th>НПР {{ __('fact') }}</th>
                @endif

                {{-- Monthes 1 - 12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++)
                        {{-- January --}}
                        <th>Кк {{ __('plan') }}</th>
                        <th>Кк {{ __('fact') }}</th>
                        <th>НПР {{ __('fact') }}</th>
                    @endfor
                @endif
            @endfor
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($record->countries as $country)
            <tr>
                <td class="mad-asp-table__td--country-name"><strong>{{ $country->code }}</strong></td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
