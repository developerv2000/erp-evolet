<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="280">
                <x-tables.partials.th.order-link text="Name" order-by="name" />
            </th>

            <th width="110">
                <x-tables.partials.th.order-link text="Department" order-by="department_id" />
            </th>

            <th width="86">{{ __('Global') }}</th>
            <th width="110">{{ __('Users') }}</th>
            <th width="200">{{ __('Roles') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->name }}</td>
                <td>{{ $record->department?->abbreviation }}</td>

                <td>
                    @if ($record->global)
                        <span class="badge badge--blue">Global</span>
                    @endif
                </td>

                <td>
                    <a class="main-link" href="{{ route('users.index', ['permissions[]' => $record->id]) }}">
                        {{ $record->users_count }}
                    </a>
                </td>

                <td>
                    @foreach ($record->roles as $role)
                        {{ $role->name }} <br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
