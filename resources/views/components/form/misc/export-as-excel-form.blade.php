@props(['action'])

<form
    class="export-as-excel-form"
    action="{{ $action }}"
    method="POST"
    data-on-submit="disable-form-submit-button">

    @csrf

    <x-misc.button
        class="toolbar__button"
        style="shadowed"
        icon="download"
        type="submit">{{ __('Export') }}
    </x-misc.button>
</form>
