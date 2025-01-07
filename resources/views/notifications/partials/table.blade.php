<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="50"><x-tables.partials.th.select-all /></th>

        <th width="200">{{ __('Date') }}</th>
        <th width="140">{{ __('Status') }}</th>
        <th>{{ __('Text') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

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
