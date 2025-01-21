<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="270">{{ __('Name') }}</th>
        <th width="100">{{ __('Abbreviation') }}</th>
        <th width="160">{{ __('Roles') }}</th>
        <th width="110">{{ __('Users') }}</th>
        <th>{{ __('Permissions') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->name }}</td>
                <td>{{ $record->abbreviation }}</td>

                <td>
                    @foreach ($record->roles as $role)
                        <a class="main-link" href="{{ route('roles.index', ['id[]' => $role->id]) }}">{{ $role->name }}</a> <br>
                    @endforeach
                </td>

                <td>
                    <a class="main-link" href="{{ route('users.index', ['department_id[]' => $record->id]) }}">
                        {{ $record->users_count }}
                    </a>
                </td>

                <td>
                    @foreach ($record->permissions as $permission)
                        {{ $permission->name }} |
                    @endforeach
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
