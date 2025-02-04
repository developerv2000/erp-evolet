<div class="mad-kpi__active-manufacturers-table-wrapper">
    {{-- Toolbar --}}
    <div class="toolbar toolbar--joined toolbar--for-table">
        {{-- blade-formatter-disable --}}
        @php
            $crumbs = [
                ['link' => null, 'text' => 'Количество активных производителей по месяцам']
            ];
        @endphp
        {{-- blade-formatter-enable --}}

        <x-layouts.breadcrumbs :crumbs="$crumbs" />
    </div>

    {{-- Table --}}
    <x-tables.template.main-template :records="null" :includePagination="false">
        {{-- thead titles --}}
        <x-slot:thead-rows>
            <tr>
                @foreach ($kpi['months'] as $month)
                    <th>{{ __($month['name']) }}</th>
                @endforeach

                <th>{{ __('Total') }}</th>
            </tr>
        </x-slot:thead-rows>

        {{-- tbody rows --}}
        <x-slot:tbody-rows>
            <tr>
                @foreach ($kpi['months'] as $month)
                    <td>
                        <a href="{{ $month['active_manufacturers_link'] }}">
                            {{ $month['active_manufacturers_count'] }}
                        </a>
                    </td>
                @endforeach

                <td>{{ $kpi['yearlyActiveManufacturers'] }}</td>
            </tr>
        </x-slot:tbody-rows>
    </x-tables.template.main-template>
</div>
