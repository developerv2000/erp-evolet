<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>

            <th width="200">{{ __('Date') }}</th>
            <th width="140">{{ __('Status') }}</th>
            <th>{{ __('Text') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>
                <td>{{ $record->created_at->isoformat('DD MMM Y HH:mm:ss') }}</td>

                <td>
                    @if ($record->read_at)
                        {{ __('Read') }}
                    @else
                        <span class="badge badge--pink">{{ __('Unread') }}</span>
                    @endif
                </td>

                <td>
                    @include('notifications.partials.record-text')
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
