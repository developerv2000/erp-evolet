@props(['title'])

<div {{ $attributes->merge(['class' => 'modal']) }}>
    <div class="modal__overlay" data-click-action="hide-visible-modal"></div>

    <div class="modal__inner">
        <div class="modal__box">
            {{-- Header --}}
            <div class="modal__header">
                <p class="modal__title">{{ $title }}</p>

                <x-misc.button class="modal__dismiss-button" style="transparent" icon="close" data-click-action="hide-visible-modal"></x-global.button>
            </div>

            {{-- Body --}}
            <div class="modal__body thin-scrollbar">{{ $body }}</div>

            {{-- Footer --}}
            <div class="modal__footer">{{ $footer }}</div>
        </div>
    </div>
</div>
