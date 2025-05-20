<x-tables.template.main-template :records="$records">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>

            @if ($trashedRecords)
                <th width="130"><x-tables.partials.th.deleted-at /></th>
                <th width="40"><x-tables.partials.th.restore /></th>
            @endif

            @foreach ($visibleTableColumns as $column)
                <th width="{{ $column['width'] }}">
                    @include('meetings.table.thead-columns')
                </th>
            @endforeach
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

                @if ($trashedRecords)
                    <td>{{ $record->deleted_at->isoFormat('DD MMM Y') }}</td>
                    <td><x-tables.partials.td.restore :form-action="route('meetings.restore')" :record-id="$record->id" /></td>
                @endif

                @foreach ($visibleTableColumns as $column)
                    <td>
                        @include('meetings.table.tbody-row-columns')
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
