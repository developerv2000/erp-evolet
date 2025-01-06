@props(['orderBy', 'text'])

<a @class(['active' => request()->order_by == $orderBy]) href="{{ request()->reversed_order_url . '&order_by=' . $orderBy }}" title="{{ $text }}">
    <span>{{ $text }}</span>
    <x-misc.material-symbol icon="expand_all" />
</a>