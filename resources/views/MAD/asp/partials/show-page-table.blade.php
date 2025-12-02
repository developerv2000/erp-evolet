<x-tables.template.main-template class="mad-asp-table main-table--colorful" :records="$record->countries" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        {{-- thead row 1 --}}
        <tr>
            <th class="mad-asp-table__th--country-name"></th>
            <th class="mad-asp-table__th--mah-name"></th>
            <th width="420" colspan="5">{{ __('Plan') }} {{ $record->year }}</th>

            @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                {{-- Quarters 1-4 --}}
                @if ($displayQuarters)
                    <th width="270" colspan="3" class="group-toggle" data-group="quarter-{{ $quarter }}">
                        {{ __('Quarter') }} {{ $quarter }}
                    </th>
                @endif

                {{-- Monthes 1-12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++)
                        <th width="246" colspan="3" class="sub-col group-quarter-{{ $quarter }}">
                            <div class="sub-col-inner">{{ __($months[$monthIndex++]['name']) }}</div>
                        </th>
                    @endfor
                @endif
            @endfor
        </tr>

        {{-- thead row 2 --}}
        <tr>
            <th class="mad-asp-table__th--country-name">{{ __('Cтр') }}</th>
            <th class="mad-asp-table__th--mah-name">{{ __('PC') }}</th>

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
                        <th class="sub-col group-quarter-{{ $quarter }}"><div class="sub-col-inner">Кк {{ __('plan') }}</div></th>
                        <th class="sub-col group-quarter-{{ $quarter }}"><div class="sub-col-inner">Кк {{ __('fact') }}</div></th>
                        <th class="sub-col group-quarter-{{ $quarter }}"><div class="sub-col-inner">НПР {{ __('fact') }}</div></th>
                    @endfor
                @endif
            @endfor
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @php
            // Same for any filters
            $summaryClass = 'backgrounded-text--1';

            // If months are also displayed same color for all quarters
            $quarterClass = 'backgrounded-text--2';
            // Else different colors for each quarter
            $quarterClasses = [
                'backgrounded-text--2',
                'backgrounded-text--3',
                'backgrounded-text--4',
                'backgrounded-text--5',
            ];

            // Define the 3 repeating month color classes
            $monthClasses = [
                'backgrounded-text--3',
                'backgrounded-text--4',
                'backgrounded-text--5',
            ];
        @endphp

        {{-- Summary row --}}
        <tr class="mad-asp-table__tbody-summary-row">
            <td class="mad-asp-table__tbody-td--year" colspan="2"><strong>{{ $record->year }}</strong></td>

            {{-- Summary year --}}
            <td class="{{ $summaryClass }}">{{ $record->year_contract_plan }}</td>
            <td class="{{ $summaryClass }}">{{ $record->year_contract_fact }}</td>
            <td class="{{ $summaryClass }}">{{ $record->year_contract_fact_percentage }} %</td>
            <td class="{{ $summaryClass }}">{{ $record->year_register_fact }}</td>
            <td class="{{ $summaryClass }}">{{ $record->year_register_fact_percentage }} %</td>

            {{-- Summary Quarters 1 - 4 --}}
            @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                @if ($displayQuarters)
                    {{-- Change quarter bg color if months are hidden --}}
                    @unless ($displayMonths)
                        @php
                            $quarterClass = $quarterClasses[$quarter % 4]; // Cycle through colors
                        @endphp
                    @endunless

                    <td class="{{ $quarterClass }}">{{ $record->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                    <td class="{{ $quarterClass }}">{{ $record->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                    <td class="{{ $quarterClass }}">{{ $record->{'quarter_' . $quarter . '_register_fact'} }}</td>
                @endif

                {{-- Summary months 1 - 12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                        @php
                            $monthClass = $monthClasses[$monthIndex % 3]; // Cycle through colors
                        @endphp

                        <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $record->{$months[$monthIndex]['name'] . '_contract_plan'} }}</div></td>
                        <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $record->{$months[$monthIndex]['name'] . '_contract_fact'} }}</div></td>
                        <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $record->{$months[$monthIndex]['name'] . '_register_fact'} }}</div></td>
                    @endfor
                @endif
            @endfor
        </tr>

        {{-- Country rows --}}
        @foreach ($record->countries as $country)
            <tr class="mad-asp-table__tbody-main-country-row">
                <td class="mad-asp-table__tbody-td--country-name">{{ $country->code }}</td>

                {{-- MAHs visibility toggler --}}
                <td class="mad-asp-table__tbody-td--mah-name">
                    <x-misc.material-symbol
                        class="mad-asp-table__tbody-country-mahs-toggler"
                        icon="visibility_off"
                        :filled="true"
                        title="{{ __('Toggle MAHs visibility') }}"
                        :data-country-code="$country->code"
                        data-opened="false" />
                </td>

                {{-- Country year --}}
                <td class="{{ $summaryClass }}">{{ $country->year_contract_plan }}</td>
                <td class="{{ $summaryClass }}">{{ $country->year_contract_fact }}</td>
                <td class="{{ $summaryClass }}">{{ $country->year_contract_fact_percentage }} %</td>
                <td class="{{ $summaryClass }}">{{ $country->year_register_fact }}</td>
                <td class="{{ $summaryClass }}">{{ $country->year_register_fact_percentage }} %</td>

                {{-- Country Quarters 1 - 4 --}}
                @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                    @if ($displayQuarters)
                        {{-- Change quarter bg color if months are hidden --}}
                        @unless ($displayMonths)
                            @php
                                $quarterClass = $quarterClasses[$quarter % 4]; // Cycle through colors
                            @endphp
                        @endunless

                        <td class="{{ $quarterClass }}">{{ $country->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                        <td class="{{ $quarterClass }}">{{ $country->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                        <td class="{{ $quarterClass }}">{{ $country->{'quarter_' . $quarter . '_register_fact'} }}</td>
                    @endif

                    {{-- Country Months 1 - 12 --}}
                    @if ($displayMonths)
                        @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                            @php
                                $monthClass = $monthClasses[$monthIndex % 3]; // Cycle through colors
                            @endphp

                            <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $country->{$months[$monthIndex]['name'] . '_contract_plan'} }}</div></td>
                            <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $country->{$months[$monthIndex]['name'] . '_contract_fact'} }}</div></td>
                            <td class="sub-col group-quarter-{{ $quarter }} {{ $monthClass }}"><div class="sub-col-inner">{{ $country->{$months[$monthIndex]['name'] . '_register_fact'} }}</div></td>
                        @endfor
                    @endif
                @endfor
            </tr>

            {{-- MAH rows --}}
            @foreach ($country->MAHs as $mah)
                <tr data-country-code="{{ $country->code }}" style="display: none">
                    <td class="mad-asp-table__tbody-td--country-name">{{ $country->code }}</td>
                    <td class="mad-asp-table__tbody-td--mah-name">{{ $mah->name }}</td>

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
                                <td class="sub-col group-quarter-{{ $quarter }}">
                                    <div class="sub-col-inner">{{ $mah->{$months[$monthIndex]['name'] . '_contract_plan'} }}</div>
                                </td>

                                <td class="sub-col group-quarter-{{ $quarter }}">
                                    <div class="sub-col-inner">
                                        <a class="main-link" href="{{ $mah->{$months[$monthIndex]['name'] . '_contract_fact_link'} }}">
                                            {{ $mah->{$months[$monthIndex]['name'] . '_contract_fact'} }}
                                        </a>
                                    </div>
                                </td>

                                <td class="sub-col group-quarter-{{ $quarter }}">
                                    <div class="sub-col-inner">
                                        <a class="main-link" href="{{ $mah->{$months[$monthIndex]['name'] . '_register_fact_link'} }}">
                                            {{ $mah->{$months[$monthIndex]['name'] . '_register_fact'} }}
                                        </a>
                                    </div>
                                </td>
                            @endfor
                        @endif
                    @endfor
                </tr>
            @endforeach
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
