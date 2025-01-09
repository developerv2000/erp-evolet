<x-tables.template.main-template :records="null" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="50"><x-tables.partials.th.select-all /></th>
        <th width="40"><x-tables.partials.th.delete /></th>

        <th>{{ __('Filename') }}</th>
        <th>{{ __('File size') }}</th>
        <th>{{ __('Date') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($record->attachments as $attachment)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$attachment->id" /></td>

                <td>
                    <x-tables.partials.td.delete :form-action="route('attachments.destroy')" :record-id="$attachment->id" />
                </td>

                <td>
                    <a class="main-link" href="{{ asset($attachment->file_path) }}" target="_blank">
                        {{ $attachment->filename }}
                    </a>
                </td>

                <td>{{ $attachment->file_size_in_megabytes }} mb</td>
                <td>{{ $attachment->created_at->isoformat('DD MMM Y HH:mm:ss') }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
