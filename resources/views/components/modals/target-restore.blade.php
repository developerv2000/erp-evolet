<x-misc.modal class="target-restore-modal" title="Восстановление записи">
    <x-slot:body>
        <form
            class="target-restore-form"
            id="target-restore-form"
            action="#" {{-- Updates by JS --}}
            method="POST">

            @csrf
            @method('PATCH')

            <input type="hidden" name="id"> {{-- Value updates by JS --}}

            <p>Вы уверены, что хотите восстановить запись?</p>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">Отмена</x-misc.button>
        <x-misc.button style="success" type="submit" form="target-restore-form">Восстановить</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
