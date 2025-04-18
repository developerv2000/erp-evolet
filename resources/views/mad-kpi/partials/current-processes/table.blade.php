<div class="mad-kpi__current-processes-table-wrapper">
    {{-- Toolbar --}}
    <div class="toolbar toolbar--joined toolbar--for-table">
        {{-- blade-formatter-disable --}}
        @php
            $crumbs = [
                ['link' => null, 'text' => 'Ключевые показатели по тщательной обработке продуктов по месяцам (уникальный показатель по этапам)']
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
                <th>{{ __('Status') }}</th>

                @foreach ($kpi['months'] as $month)
                    <th>{{ __($month['name']) }}</th>
                @endforeach

                <th>{{ __('Total') }}</th>
            </tr>
        </x-slot:thead-rows>

        {{-- tbody rows --}}
        <x-slot:tbody-rows>
            @foreach ($kpi['generalStatuses'] as $status)
                <tr>
                    <td>{{ $status->name }}</td>

                    @foreach ($status->months as $month)
                        <td>
                            <a href="{{ $month['current_processes_link'] }}">
                                {{ $month['current_processes_count'] }}
                            </a>
                        </td>
                    @endforeach

                    <td>{{ $status->sum_of_monthly_current_processes }}</td>
                </tr>
            @endforeach

            {{-- Sum of total statuses --}}
            <tr>
                <td>{{ __('Total') }}</td>

                @foreach ($kpi['months'] as $month)
                    <td>{{ $month['sum_of_all_current_process'] }}</td>
                @endforeach

                <td>{{ $kpi['yearlyCurrentProcesses'] }}</td>
            </tr>
        </x-slot:tbody-rows>
    </x-tables.template.main-template>
</div>
