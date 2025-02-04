<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="180">
                <x-tables.partials.th.order-link text="Name" order-by="name" />
            </th>

            <th width="110">
                <x-tables.partials.th.order-link text="Department" order-by="department_id" />
            </th>

            <th width="86">{{ __('Global') }}</th>
            <th width="110">{{ __('Users') }}</th>
            <th width="320">{{ __('Description') }}</th>
            <th width="400">{{ __('Permissions') }}</th>
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
                    <a class="main-link" href="{{ route('users.index', ['roles[]' => $record->id]) }}">
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
