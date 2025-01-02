<x-misc.modal class="target-restore-modal" title="{{ __('Восстановление записи') }}">
    <x-slot:body>
        <form
            class="target-restore-form"
            id="target-restore-form"
            action="#" {{-- Updates by JS --}}
            method="POST"
            data-on-submit="show-spinner">

            @csrf
            @method('PATCH')

            <input type="hidden" name="id"> {{-- Value updates by JS --}}

            <p>{{ __('Вы уверены, что хотите восстановить запись') }}?</p>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">{{ __('Отмена') }}</x-misc.button>
        <x-misc.button style="success" type="submit" form="target-restore-form">{{ __('Восстановить') }}</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
