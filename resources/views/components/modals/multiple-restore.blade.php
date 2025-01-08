@props(['formAction'])

<x-misc.modal class="multiple-restore-modal" title="{{ __('Restore records') }}">
    <x-slot:body>
        <form
            class="multiple-restore-form"
            id="multiple-restore-form"
            action="{{ $formAction }}"
            method="POST"
            data-before-submit="appends-inputs"
            data-inputs-selector=".main-table .td__checkbox"
            data-on-submit="show-spinner">

            @csrf
            @method('PATCH')

            <p>{{ __('Are you sure, you want to restore all selected records') }}?</p>

            {{-- Used to hold appended checkbox inputs before submiting form by JS --}}
            <div class="form__hidden-appended-inputs-container"></div>
        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-misc.button style="cancel" data-click-action="hide-visible-modal">{{ __('Cancel') }}</x-misc.button>
        <x-misc.button style="success" type="submit" form="multiple-restore-form">{{ __('Restore') }}</x-misc.button>
    </x-slot:footer>
</x-misc.modal>
