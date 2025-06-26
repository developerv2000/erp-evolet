<x-tables.template.main-template class="dd-orders-table" :records="$records">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>

            @foreach ($visibleTableColumns as $column)
                <th width="{{ $column['width'] }}">
                    @include('DD.orders.table.thead-columns')
                </th>
            @endforeach
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

                @foreach ($visibleTableColumns as $column)
                    <td>
                        @include('DD.orders.table.tbody-row-columns')
                    </td>
                @endforeach
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
