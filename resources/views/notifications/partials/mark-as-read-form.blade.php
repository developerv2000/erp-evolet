<form
    action="{{ route('notifications.mark-as-read') }}"
    method="POST"
    data-before-submit="appends-inputs"
    data-inputs-selector=".main-table .td__checkbox"
    data-on-submit="show-spinner">

    @csrf
    @method('PATCH')

    {{-- Used to hold appended checkbox inputs before submiting form by JS --}}
    <div class="form__hidden-appended-inputs-container"></div>

    <x-misc.button
        style="shadowed"
        icon="check"
        class="toolbar__button">
        {{ __('Mark selected as read') }}
    </x-misc.button>
</form>
