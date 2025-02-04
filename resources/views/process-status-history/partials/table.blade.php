<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>
            <th width="40"><x-tables.partials.th.edit /></th>

            <th>{{ __('Status') }}</th>
            <th>{{ __('General status') }}</th>
            <th>{{ __('Start date') }}</th>
            <th>{{ __('End date') }}</th>
            <th>{{ __('Duration days') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($process->statusHistory as $history)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$history->id" /></td>

                <td>
                    <x-tables.partials.td.edit :link="route('processes.status-history.edit', ['process' => $process->id, 'record' => $history->id])" />
                </td>

                <td>{{ $history->status->name }}</td>
                <td>{{ $history->status->generalStatus->name }}</td>
                <td>{{ $history->start_date->isoformat('DD MMM Y HH:mm:ss') }}</td>
                <td>{{ $history->end_date?->isoformat('DD MMM Y HH:mm:ss') }}</td>
                <td>{{ $history->duration_days }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
