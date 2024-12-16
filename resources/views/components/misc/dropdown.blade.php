@props(['id', 'buttonStyle' => 'main', 'includeArrow' => false])

<div {{ $attributes->merge(['class' => 'dropdown' . $includeArrow ? ' dropdown--arrowed' : '']) }}>
    <button class="button button--{{ $buttonStyle }} dropdown__button" aria-expanded="false" aria-controls="{{ $id }}">
        {{ $button }}

        @if ($includeArrow)
            <x-misc.material-symbol-outlined class="dropdown__icon" icon="arrow_drop_down" />
        @endif
    </button>

    <ul id="{{ $id }}" class="dropdown__content" role="menu">
        {{ $content }}
    </ul>
</div>
