<header class="header">
    <div class="header__inner">
        {{-- Logo --}}
        <div class="header__logo-wrapper">
            <x-misc.material-symbol-outlined class="header__leftbar-toggler unselectable" icon="menu" title="Переключить меню" />

            <a class="header__logo-link" href="/">
                <h4 class="header__logo-text">EVOLET</h4>
            </a>
        </div>

        {{-- Menu --}}
        <div class="header__menu">
            {{-- Theme toggler --}}
            <form class="theme-toggler-form" action="{{ route('settings.toggle-theme') }}" method="POST">
                @csrf
                @method('PATCH')

                <x-misc.button
                    style="transparent"
                    class="theme-toggler-form__button"
                    title="Сменить тему"
                    icon="{{ request()->user()->settings['preferred_theme'] == 'light' ? 'dark_mode' : 'light_mode' }}"
                    filled-icon="true">
                </x-misc.button>
            </form>

            {{-- Profile dropdown --}}
            <x-dropdowns.profile-dropdown />
        </div>
    </div>
</header>
