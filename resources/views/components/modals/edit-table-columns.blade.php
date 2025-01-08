@props(['formAction', 'columns'])

<x-misc.modal class="edit-table-columns-modal" title="{{ __('Edit columns') }}">
    <x-slot:body>
        <form
            class="edit-table-columns-form"
            id="edit-table-columns-form"
            action="{{ $formAction }}"
            method="POST">

            @csrf
            @method('PATCH')

            <p>{{ __('Drag and drop columns for sorting!') }}</p>

            <x-modals.partials.sortable-table-columns :columns="$columns" />
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">{{ __('Cancel') }}</x-misc.button>
        <x-misc.button style="main" type="submit" form="edit-table-columns-form">{{ __('Update') }}</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
