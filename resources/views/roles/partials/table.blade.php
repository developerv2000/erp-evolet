<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="180">{{ __('Name') }}</th>
        <th width="94">{{ __('Department') }}</th>
        <th width="100">{{ __('Global') }}</th>
        <th width="110">{{ __('Users') }}</th>
        <th width="360">{{ __('Description') }}</th>
        <th width="600">{{ __('Permissions') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->name }}</td>
                <td>{{ $record->department?->abbreviation }}</td>

                <td>
                    @if ($record->global)
                        <span class="badge badge--blue">{{ __('Global') }}</span>
                    @endif
                </td>

                <td>
                    <a class="main-link" href="{{ route('users.index', ['department_id[]' => $record->id]) }}">
                        {{ $record->users_count }}
                    </a>
                </td>

                <td>{{ $record->description }}</td>

                <td>
                    @foreach ($record->permissions as $permission)
                        {{ $permission->name }} |
                    @endforeach
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
