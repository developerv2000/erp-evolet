@props(['style' => 'light'])

<a href="/" {{ $attributes->merge(['class' => 'logo']) }}>
    <img class="logo__image" src="{{ asset('img/main/logo-' . $style . '.svg') }}">
</a>
