@props(['action'])

<form class="export-as-excel-form" action="{{ $action }}" method="POST">
    @csrf

    <x-misc.button
        class="toolbar__button"
        style="shadowed"
        icon="download"
        type="submit">{{ __('Export') }}
    </x-misc.button>
</form>
