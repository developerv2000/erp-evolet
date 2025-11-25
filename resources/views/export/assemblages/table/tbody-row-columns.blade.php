@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('export.assemblages.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Assemblage №')
        {{ $record->number }}
    @break

    @case('Assemblage date')
        {{ $record->assemblage_date->isoformat('DD MMM Y') }}
    @break

    @case('Method of shipment')
        {{ $record->shipmentType->name }}
    @break

    @case('Country')
        {{ $record->country->code }}
    @break

    @case('Batches')
        <a href="{{ route('export.batches.index', ['assemblage_id' => $record->id]) }}" class="main-link text-lowercase">
            {{ $record->batches_count }} {{ __('Batches') }}
        </a>
    @break

    @case('Assembly request date')
        @if ($record->assembly_request_date)
            {{ $record->assembly_request_date->isoformat('DD MMM Y') }}
        @elseif(auth()->user()->can('moderate-export-assemblages'))
            <form action="{{ route('export.assemblages.request-assembly', $record->id) }}" method="POST">
                @csrf

                <x-misc.button
                    style="transparent"
                    class="button--arrowed-link button--margined-bottom"
                    icon="line_end_arrow_notch">
                    {{ __('Send request') }}
                </x-misc.button>
            </form>
        @endif
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Request accept date')
        @if ($record->assembly_request_accepted_date)
            {{ $record->assembly_request_accepted_date->isoformat('DD MMM Y') }}
        @else
            <form action="{{ route('export.assemblages.accept-assembly-request', $record->id) }}" method="POST">
                @csrf

                <x-misc.button
                    style="transparent"
                    class="button--arrowed-link button--margined-bottom"
                    icon="line_end_arrow_notch">
                    {{ __('Accept') }}
                </x-misc.button>
            </form>
        @endif
    @break

    @case('Initial assembly date')
        {{ $record->initial_assembly_acceptance_date?->isoformat('DD MMM Y') }}
    @break

    @case('Initial assembly')
        @if ($record->initial_assembly_file)
            <a class="main-link" href="{{ $record->initial_assembly_asset_url }}" target="_blank">
                {{ $record->initial_assembly_file }}
            </a>
        @endif
    @break

    @case('Final assembly date')
        {{ $record->final_assembly_acceptance_date?->isoformat('DD MMM Y') }}
    @break

    @case('Final assembly')
        @if ($record->final_assembly_file)
            <a class="main-link" href="{{ $record->final_assembly_asset_url }}" target="_blank">
                {{ $record->final_assembly_file }}
            </a>
        @endif
    @break

    @case('Documents provision date')
        {{ $record->documents_provision_date_to_warehouse?->isoformat('DD MMM Y') }}
    @break

    @case('COO date')
        {{ $record->coo_file_date?->isoformat('DD MMM Y') }}
    @break

    @case('COO')
        @if ($record->coo_file)
            <a class="main-link" href="{{ $record->coo_asset_url }}" target="_blank">
                {{ $record->coo_file }}
            </a>
        @endif
    @break

    @case('EURO 1 date')
        {{ $record->euro_1_file_date?->isoformat('DD MMM Y') }}
    @break

    @case('EURO 1')
        @if ($record->euro_1_file)
            <a class="main-link" href="{{ $record->euro_1_asset_url }}" target="_blank">
                {{ $record->euro_1_file }}
            </a>
        @endif
    @break

    @case('GMP or ISO')
        @if ($record->gmp_or_iso_file)
            <a class="main-link" href="{{ $record->gmp_or_iso_asset_url }}" target="_blank">
                {{ $record->gmp_or_iso_file }}
            </a>
        @endif
    @break

    @case('Volume')
        {{ $record->volume }}
    @break

    @case('Packs')
        {{ $record->packs }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
