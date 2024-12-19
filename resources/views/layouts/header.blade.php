<header class="header">
    <div class="header__inner">
        {{-- Logo --}}
        <div class="header__logo-wrapper">
            <x-misc.material-symbol class="header__leftbar-toggler unselectable" icon="menu" title="Переключить меню" />

            <a class="header__logo-link" href="/">
                <h4 class="header__logo-text">EVOLET</h4>
            </a>
        </div>

        {{-- Menu --}}
        <div class="header__menu">
            {{-- Notifications --}}
            <a class="header__notifications" href="{{ route('notifications.index') }}">
                @if (request()->user()->unreadNotifications->count() == 0)
                    <x-misc.material-symbol class="header__notifications-icon" icon="notifications" filled="true" />
                @else
                    <x-misc.material-symbol class="header__notifications-icon header__notifications-icon--unread" icon="notifications_unread" filled="true" />
                @endif
            </a>

            {{-- Theme toggler --}}
            <form class="theme-toggler-form" action="{{ route('settings.toggle-theme') }}" method="POST">
                @csrf
                @method('PATCH')

                <x-misc.button
                    style="transparent"
                    class="theme-toggler-form__button"
                    title="{{ __('Switch theme') }}"
                    icon="{{ request()->user()->settings['preferred_theme'] == 'light' ? 'dark_mode' : 'light_mode' }}"
                    filled-icon="true">
                </x-misc.button>
            </form>

            {{-- Locale dropdown --}}
            <x-dropdowns.locale-dropdown />

            {{-- Profile dropdown --}}
            <x-dropdowns.profile-dropdown />
        </div>
    </div>
</header>
