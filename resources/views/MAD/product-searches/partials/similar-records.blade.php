<x-misc.similar-records :records-found="$similarRecords->isNotEmpty()">
    @foreach ($similarRecords as $record)
        <div class="similar-records__list-item">
            <a class="similar-records__list-link main-link" href="{{ route('mad.product-searches.index', ['id[]' => $record->id]) }}" target="_blank"># {{ $record->id }}</a>

            <div class="similar-records__list-text">
                <span>{{ __('ID') }}: {{ $record->id }}</span>
                <span>{{ __('Form') }}: {{ $record->form->name }}</span>
                <span>{{ __('Dosage') }}: {{ $record->dosage }}</span>
                <span>{{ __('Pack') }}: {{ $record->pack }}</span>
                <span>{{ __('Country') }}: {{ $record->country->code }}</span>
                <span>{{ __('MAH') }}: {{ $record->MAH->name }}</span>
            </div>
        </div>
    @endforeach
</x-misc.similar-records>
