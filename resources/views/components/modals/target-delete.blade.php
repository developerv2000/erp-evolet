@props(['forceDelete'])

<x-misc.modal class="target-delete-modal" title="{{ __('Удаление записи') }}">
    <x-slot:body>
        <form
            class="target-delete-form"
            id="target-delete-form"
            action="#" {{-- Updates by JS --}}
            method="POST"
            data-on-submit="show-spinner">

            @csrf
            @method('DELETE')

            <input type="hidden" name="id"> {{-- Value updates by JS --}}

            {{-- Force delete on trash pages --}}
            @if ($forceDelete)
                <input type="hidden" name="force_delete" value="1">
            @endif

            <p>{{ __('Вы уверены, что хотите удалить запись') }}?</p>
            <p>{{ __('Также, удалятся все связанные с ним записи') }}!</p>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">{{ __('Отмена') }}</x-misc.button>
        <x-misc.button style="danger" type="submit" form="target-delete-form">{{ __('Удалить') }}</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
