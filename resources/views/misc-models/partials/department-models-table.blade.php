<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Records count') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($models as $model)
            <tr>
                <td>
                    <a class="main-link" href="{{ route('misc-models.index', $model['name']) }}">{{ $model['caption'] }}</a>
                </td>

                <td>{{ $model['record_count'] }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
