<x-tables.template.main-template class="mad-asp-table" :records="$record->countries" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        {{-- thead row 1 --}}
        <tr>
            <th class="mad-asp-table__th--country-name">{{ __('Cтр') }}</th>
            <th class="mad-asp-table__th--mah-name">{{ __('PC') }}</th>
            <th width="420" colspan="5">{{ __('Plan') }} {{ $record->year }}</th>

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
        {{-- Summary row --}}
        <tr>
            <td class="mad-asp-table__td--year"><strong>{{ $record->year }}</strong></td>
            <td class="mad-asp-table__td--mah-name"></td>

            {{-- Summary year --}}
            <td>{{ $record->year_contract_plan }}</td>
            <td>{{ $record->year_contract_fact }}</td>
            <td>{{ $record->year_contract_fact_percentage }} %</td>
            <td>{{ $record->year_register_fact }}</td>
            <td>{{ $record->year_register_fact_percentage }} %</td>

            {{-- Summary Quarters 1 - 4 --}}
            @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                @if ($displayQuarters)
                    <td>{{ $record->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                    <td>{{ $record->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                    <td>{{ $record->{'quarter_' . $quarter . '_register_fact'} }}</td>
                @endif

                {{-- Summary months 1 - 12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                        <td>{{ $record->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>
                        <td>{{ $record->{$months[$monthIndex]['name'] . '_contract_fact'} }}</td>
                        <td>{{ $record->{$months[$monthIndex]['name'] . '_register_fact'} }}</td>
                    @endfor
                @endif
            @endfor
        </tr>

        {{-- Country rows --}}
        @foreach ($record->countries as $country)
            <tr class="mad-asp-table__divider"></tr> {{-- Empty space as divider --}}

            <tr>
                <td class="mad-asp-table__td--country-name"><strong>{{ $country->code }}</strong></td>
                <td class="mad-asp-table__td--mah-name"></td>

                {{-- Country year --}}
                <td>{{ $country->year_contract_plan }}</td>
                <td>{{ $country->year_contract_fact }}</td>
                <td>{{ $country->year_contract_fact_percentage }} %</td>
                <td>{{ $country->year_register_fact }}</td>
                <td>{{ $country->year_register_fact_percentage }} %</td>

                {{-- Country Quarters 1 - 4 --}}
                @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                    @if ($displayQuarters)
                        <td>{{ $country->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                        <td>{{ $country->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                        <td>{{ $country->{'quarter_' . $quarter . '_register_fact'} }}</td>
                    @endif

                    {{-- Country Months 1 - 12 --}}
                    @if ($displayMonths)
                        @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                            <td>{{ $country->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>
                            <td>{{ $country->{$months[$monthIndex]['name'] . '_contract_fact'} }}</td>
                            <td>{{ $country->{$months[$monthIndex]['name'] . '_register_fact'} }}</td>
                        @endfor
                    @endif
                @endfor
            </tr>

            {{-- MAH rows --}}
            @foreach ($country->MAHs as $mah)
                <tr>
                    <td class="mad-asp-table__td--country-name">{{ $country->code }}</td>
                    <td class="mad-asp-table__td--mah-name">{{ $mah->name }}</td>

                    {{-- MAH year --}}
                    <td>{{ $mah->year_contract_plan }}</td>
                    <td>{{ $mah->year_contract_fact }}</td>
                    <td>{{ $mah->year_contract_fact_percentage }} %</td>
                    <td>{{ $mah->year_register_fact }}</td>
                    <td>{{ $mah->year_register_fact_percentage }} %</td>

                    {{-- MAH Quarters 1 - 4 --}}
                    @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                        @if ($displayQuarters)
                            <td>{{ $mah->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                            <td>{{ $mah->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                            <td>{{ $mah->{'quarter_' . $quarter . '_register_fact'} }}</td>
                        @endif

                        {{-- MAH Monthes 1 - 12 --}}
                        @if ($displayMonths)
                            @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                                <td>{{ $mah->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>

                                <td>
                                    <a href="{{ $mah->{$months[$monthIndex]['name'] . '_contract_fact_link'} }}">
                                        {{ $mah->{$months[$monthIndex]['name'] . '_contract_fact'} }}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ $mah->{$months[$monthIndex]['name'] . '_register_fact_link'} }}">
                                        {{ $mah->{$months[$monthIndex]['name'] . '_register_fact'} }}
                                    </a>
                                </td>
                            @endfor
                        @endif
                    @endfor
                </tr>
            @endforeach
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
