<x-tables.template.main-template class="decision-hub__table" :records="$records" :include-pagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            @foreach ($visibleTableColumns as $column)
                <th width="{{ $column['width'] }}">
                    @include('decision-hub.table.thead-columns')
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
                        @include('decision-hub.table.tbody-row-columns')
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
