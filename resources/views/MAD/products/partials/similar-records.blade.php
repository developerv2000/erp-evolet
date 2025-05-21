<div class="similar-records">
    <h3 class="main-title">{{ __('Similar records') }}</h3>

    @if (count($similarRecords))
        <div class="similar-records__list">
            @foreach ($similarRecords as $record)
                <div class="similar-records__list-item">
                    <a class="similar-records__list-link main-link" href="{{ route('mad.products.index', ['id[]' => $record->id]) }}" target="_blank"># {{ $record->id }}</a>

                    <div class="similar-records__list-text">
                        <span>{{ __('Form') }}: {{ $record->form->name }}</span>
                        <span>{{ __('Dosage') }}: {{ $record->dosage }}</span>
                        <span>{{ __('Pack') }}: {{ $record->pack }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="similar-records__empty-text">
            <x-misc.button class="similar-records__empty-text-icon" style="transparent" icon="done_all"></x-misc.button>
            {{ __('No similar records found') }}!
        </div>
    @endif
</div>
