@props(['forceDelete'])

<x-misc.modal class="target-delete-modal" title="{{ __('Delete record') }}">
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

            <p>{{ __('Are you sure, you want to delete record') }}?</p>
            <p>{{ __('Also, all associated records will be deleted with it') }}!</p>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">{{ __('Cancel') }}</x-misc.button>
        <x-misc.button style="danger" type="submit" form="target-delete-form">{{ __('Delete') }}</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
