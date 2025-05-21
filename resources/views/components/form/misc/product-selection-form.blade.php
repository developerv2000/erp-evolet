@props(['model'])

<form
    class="product-selection-form"
    action="{{ route('mad.product-selection.export-as-excel') }}"
    method="POST"
    data-on-submit="disable-form-submit-button">

    @csrf
    <input type="hidden" name="model" value="{{ $model }}">

    <x-misc.button
        class="toolbar__button"
        style="shadowed"
        icon="download"
        type="submit">ВП
    </x-misc.button>
</form>
