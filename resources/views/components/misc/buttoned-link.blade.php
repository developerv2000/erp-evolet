@props(['link', 'style' => 'main', 'icon' => null, 'filledIcon' => false])

<a {{ $attributes->merge(['class' => 'button button--' . $style]) }} href="{{ $link }}">
    @if ($icon)
        <x-misc.material-symbol class="button__icon" :icon="$icon" :filled="$filledIcon" />
    @endif

    <span class="button__text">{{ $slot }}</span>
</a>
