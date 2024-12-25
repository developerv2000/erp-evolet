@props(['formAction', 'forceDelete'])

<x-misc.modal class="multiple-delete-modal" title="Удаление записей">
    <x-slot:body>
        <form
            class="multiple-delete-form"
            id="multiple-delete-form"
            action="{{ $formAction }}"
            method="POST"
            data-before-submit="appends-inputs"
            data-inputs-selector=".main-table .td__checkbox"
            data-on-submit="show-spinner">

            @csrf
            @method('DELETE')

            {{-- Force delete on trash pages --}}
            @if ($forceDelete)
                <input type="hidden" name="force_delete" value="1">
            @endif

            <p>Вы уверены, что хотите удалить отмеченные записи?</p>
            <p>Также, удалятся все связанные с ним записи!</p>

            {{-- Used to hold appended checkbox inputs before submiting form by JS --}}
            <div class="form__hidden-appended-inputs-container"></div>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">Отмена</x-misc.button>
        <x-misc.button style="danger" type="submit" form="multiple-delete-form">Удалить</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
