<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>
            <th width="40"><x-tables.partials.th.edit /></th>

            <th>{{ __('Name') }}</th>

            {{-- Parentable models --}}
            @if (in_array('parent_id', $model['attributes']))
                <th>{{ __('Parent') }}</th>
            @endif

            {{-- Code for Country model --}}
            @if (in_array('code', $model['attributes']))
                <th>{{ __('Code') }}</th>
            @endif

            <th>{{ __('Usage count') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

                <td>
                    <x-tables.partials.td.edit :link="route('misc-models.edit', ['model' => $model['name'], 'id' => $record->id])" />
                </td>

                <td>{{ $record->name }}</td>

                {{-- Parentable models --}}
                @if (in_array('parent_id', $model['attributes']))
                    <td>{{ $record->parent?->name }}</td>
                @endif

                {{-- Code for Country model --}}
                @if (in_array('code', $model['attributes']))
                    <td>{{ $record->code }}</td>
                @endif

                <td>{{ $record->usage_count }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
