<x-tables.template.main-template :records="$records" :include-pagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            @foreach ($visibleTableColumns as $column)
                <th width="{{ $column['width'] }}">
                    @include('processes.table.thead-columns')
                </th>
            @endforeach
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                @foreach ($visibleTableColumns as $column)
                    <td>
                        @include('processes.table.tbody-row-columns')
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
