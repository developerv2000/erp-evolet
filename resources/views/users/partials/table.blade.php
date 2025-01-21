<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="90">{{ __('Photo') }}</th>

        <th width="40">
            <x-tables.partials.th.edit />
        </th>

        <th width="180">
            <x-tables.partials.th.order-link text="Name" order-by="name" />
        </th>

        <th width="180">
            <x-tables.partials.th.order-link text="Email" order-by="email" />
        </th>

        <th width="110">
            <x-tables.partials.th.order-link text="Department" order-by="department_id" />
        </th>

        <th width="130">{{ __('Roles') }}</th>
        <th width="280">{{ __('Permissions') }}</th>
        <th width="120">{{ __('Responsible') }}</th>

        <th width="138">
            <x-tables.partials.th.order-link text="Date of creation" order-by="created_at" />
        </th>

        <th width="166">
            <x-tables.partials.th.order-link text="Update date" order-by="168" />
        </th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td>
                    <img class="users-index__photo" src="{{ $record->photo_asset_url }}">
                </td>

                <td><x-tables.partials.td.edit :link="route('users.edit', $record->id)" /></td>

                <td>{{ $record->name }}</td>

                <td>
                    <a class="main-link" href="mailto:{{ $record->email }}">{{ $record->email }}</a>
                </td>

                <td>{{ $record->department->abbreviation }}</td>

                <td>
                    @foreach ($record->roles as $role)
                        {{ $role->name }} <br>
                    @endforeach
                </td>

                <td>
                    @foreach ($record->permissions as $permission)
                        {{ $permission->name }} <br>
                    @endforeach
                </td>

                <td>
                    @foreach ($record->responsibleCountries as $country)
                        {{ $country->code }} <br>
                    @endforeach
                </td>

                <td>{{ $record->created_at->isoformat('DD MMM Y') }}</td>
                <td>{{ $record->updated_at->isoformat('DD MMM Y') }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
