@props(['forceDelete'])

<x-misc.modal class="target-delete-modal" title="Удаление записи">
    <x-slot:body>
        <form
            class="target-delete-form"
            id="target-delete-form"
            action="#" {{-- Updates by JS --}}
            method="POST">

            @csrf
            @method('DELETE')

            <input type="hidden" name="id"> {{-- Value updates by JS --}}

            {{-- Force delete on trash pages --}}
            @if ($forceDelete)
                <input type="hidden" name="force_delete" value="1">
            @endif

            <p>Вы уверены, что хотите удалить запись?</p>
            <p>Также, удалятся все связанные с ним записи!</p>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">Отмена</x-misc.button>
        <x-misc.button style="danger" type="submit" form="target-delete-form">Удалить</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
