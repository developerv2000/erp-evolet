<x-misc.dropdown id="locale-dropdown" class="locale-dropdown" button-style="transparent">
    <x-slot:button>
        <img src="{{ asset('img/main/flag-' . app()->getLocale() . '.png') }}" alt="{{ app()->getLocale() }}">
        <span>{{ app()->getLocale() }}</span>
    </x-slot:button>

    <x-slot:content>
        <form class="locale-form" action="{{ route('settings.update-locale') }}" method="POST">
            @method('PATCH')
            @csrf

            <button class="button button--transparent dropdown__content-button" name="locale" value="en">
                <img src="{{ asset('img/main/flag-en.png') }}" alt="en">
                <span>English</span>
            </button>

            <button class="button button--transparent dropdown__content-button" name="locale" value="ru">
                <img src="{{ asset('img/main/flag-ru.png') }}" alt="ru">
                <span>Русский</span>
            </button>
        </form>
    </x-slot:content>
</x-misc.dropdown>
