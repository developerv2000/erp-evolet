@props(['style' => 'main', 'icon' => null, 'filledIcon' => false])

<button {{ $attributes->merge(['class' => 'button button--' . $style]) }}>
    @if ($icon)
        <x-misc.material-symbol-outlined class="button__icon" :icon="$icon" :filled="$filledIcon" />
    @endif

    <span class="button__text">{{ $slot }}</span>
</button>
