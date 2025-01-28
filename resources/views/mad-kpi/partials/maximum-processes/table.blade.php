<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th>{{ __('Status') }}</th>

        @foreach ($kpi['months'] as $month)
            <th>{{ __($month['name']) }}</th>
        @endforeach

        <th>{{ __('Total') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($kpi['generalStatuses'] as $status)
            <tr>
                <td>{{ $status->name }}</td>

                @foreach ($status->months as $month)
                    <td>
                        <a href="{{ $month['maximum_processes_link'] }}">
                            {{ $month['maximum_processes_count'] }}
                        </a>
                    </td>
                @endforeach

                <td>{{ $status->sum_of_monthly_maximum_processes }}</td>
            </tr>
        @endforeach

        {{-- Sum of total statuses --}}
        <tr>
            <td>{{ __('Total') }}</td>

            @foreach ($kpi['months'] as $month)
                <td>{{ $month['sum_of_all_maximum_process'] }}</td>
            @endforeach

            <td>{{ $kpi['yearlyMaximumProcesses'] }}</td>
        </tr>
    </x-slot:tbody-rows>
</x-tables.template.main-template>
