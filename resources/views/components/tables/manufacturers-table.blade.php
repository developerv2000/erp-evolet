@props(['records', 'visibleTableColumns', 'trashedRecords' => false])

<x-tables.template.main-template :records="$records">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="50"><x-tables.partials.th.select-all /></th>

        @if ($trashedRecords)
            <th width="130"><x-tables.partials.th.deleted-at /></th>
        @endif

        @foreach ($visibleTableColumns as $column)
            <th width="{{ $column['width'] }}">
                <x-tables.thead-columns.manufacturers :column="$column" />
            </th>
        @endforeach
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

                @if ($trashedRecords)
                    <td>{{ $record->deleted_at->isoFormat('DD MMM Y') }}</td>
                @endif

                @foreach ($visibleTableColumns as $column)
                    <td>
                        <x-tables.tbody-row-columns.manufacturers :record="$record" :column="$column" />
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
