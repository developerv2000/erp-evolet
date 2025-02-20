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
                        <th width="246" colspan="3">{{ __($months[$monthIndex++]['name']) }}</th>
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
        @php
            // Same for any filter
            $summaryBgColor = 'var(--theme-table-td-background-color-1)';

            // If months are also displayed same color for all quarters
            $quarterBgColor = 'var(--theme-table-td-background-color-2)';
            // Else different colors for each quarter
            $quarterBgColors = [
                'var(--theme-table-td-background-color-2)',
                'var(--theme-table-td-background-color-3)',
                'var(--theme-table-td-background-color-4)',
                'var(--theme-table-td-background-color-5)',
            ];

            // Define the 3 repeating month background colors
            $monthBgColors = ['var(--theme-table-td-background-color-3)', 'var(--theme-table-td-background-color-4)', 'var(--theme-table-td-background-color-5)'];
        @endphp

        {{-- Summary row --}}
        <tr class="mad-asp-table__tbody-summary-row">
            <td class="mad-asp-table__tbody-td--year" colspan="2"><strong>{{ $record->year }}</strong></td>

            {{-- Summary year --}}
            <td style="background-color: {{ $summaryBgColor }}">{{ $record->year_contract_plan }}</td>
            <td style="background-color: {{ $summaryBgColor }}">{{ $record->year_contract_fact }}</td>
            <td style="background-color: {{ $summaryBgColor }}">{{ $record->year_contract_fact_percentage }} %</td>
            <td style="background-color: {{ $summaryBgColor }}">{{ $record->year_register_fact }}</td>
            <td style="background-color: {{ $summaryBgColor }}">{{ $record->year_register_fact_percentage }} %</td>

            {{-- Summary Quarters 1 - 4 --}}
            @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                @if ($displayQuarters)
                    {{-- Change quarter bg color if months are hidden --}}
                    @unless ($displayMonths)
                        @php
                            $quarterBgColor = $quarterBgColors[$quarter % 4]; // Cycle through colors
                        @endphp
                    @endunless

                    <td style="background-color: {{ $quarterBgColor }}">{{ $record->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                    <td style="background-color: {{ $quarterBgColor }}">{{ $record->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                    <td style="background-color: {{ $quarterBgColor }}">{{ $record->{'quarter_' . $quarter . '_register_fact'} }}</td>
                @endif

                {{-- Summary months 1 - 12 --}}
                @if ($displayMonths)
                    @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                        @php
                            $monthBgColor = $monthBgColors[$monthIndex % 3]; // Cycle through colors
                        @endphp

                        <td style="background-color: {{ $monthBgColor }}">{{ $record->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>
                        <td style="background-color: {{ $monthBgColor }}">{{ $record->{$months[$monthIndex]['name'] . '_contract_fact'} }}</td>
                        <td style="background-color: {{ $monthBgColor }}">{{ $record->{$months[$monthIndex]['name'] . '_register_fact'} }}</td>
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
                <td style="background-color: {{ $summaryBgColor }}">{{ $country->year_contract_plan }}</td>
                <td style="background-color: {{ $summaryBgColor }}">{{ $country->year_contract_fact }}</td>
                <td style="background-color: {{ $summaryBgColor }}">{{ $country->year_contract_fact_percentage }} %</td>
                <td style="background-color: {{ $summaryBgColor }}">{{ $country->year_register_fact }}</td>
                <td style="background-color: {{ $summaryBgColor }}">{{ $country->year_register_fact_percentage }} %</td>

                {{-- Country Quarters 1 - 4 --}}
                @for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++)
                    @if ($displayQuarters)
                        {{-- Change quarter bg color if months are hidden --}}
                        @unless ($displayMonths)
                            @php
                                $quarterBgColor = $quarterBgColors[$quarter % 4]; // Cycle through colors
                            @endphp
                        @endunless

                        <td style="background-color: {{ $quarterBgColor }}">{{ $country->{'quarter_' . $quarter . '_contract_plan'} }}</td>
                        <td style="background-color: {{ $quarterBgColor }}">{{ $country->{'quarter_' . $quarter . '_contract_fact'} }}</td>
                        <td style="background-color: {{ $quarterBgColor }}">{{ $country->{'quarter_' . $quarter . '_register_fact'} }}</td>
                    @endif

                    {{-- Country Months 1 - 12 --}}
                    @if ($displayMonths)
                        @for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++)
                            @php
                                $monthBgColor = $monthBgColors[$monthIndex % 3]; // Cycle through colors
                            @endphp

                            <td style="background-color: {{ $monthBgColor }}">{{ $country->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>
                            <td style="background-color: {{ $monthBgColor }}">{{ $country->{$months[$monthIndex]['name'] . '_contract_fact'} }}</td>
                            <td style="background-color: {{ $monthBgColor }}">{{ $country->{$months[$monthIndex]['name'] . '_register_fact'} }}</td>
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
                                <td>{{ $mah->{$months[$monthIndex]['name'] . '_contract_plan'} }}</td>

                                <td>
                                    <a class="main-link" href="{{ $mah->{$months[$monthIndex]['name'] . '_contract_fact_link'} }}">
                                        {{ $mah->{$months[$monthIndex]['name'] . '_contract_fact'} }}
                                    </a>
                                </td>

                                <td>
                                    <a class="main-link" href="{{ $mah->{$months[$monthIndex]['name'] . '_register_fact_link'} }}">
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
