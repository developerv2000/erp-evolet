@props(['recordsFound'])

<div class="similar-records">
    <h3 class="main-title">{{ __('Similar records') }}</h3>

    @if ($recordsFound)
        <div class="similar-records__list">
            {{ $slot }}
        </div>
    @else
        <div class="similar-records__empty-text">
            <x-misc.button class="similar-records__empty-text-icon" style="transparent" icon="done_all" />
            {{ __('No similar records found') }}!
        </div>
    @endif
</div>
