<x-misc.dropdown id="profile-dropdown" class="profile-dropdown" button-style="transparent">
    <x-slot:button>
        <img class="profile-dropdown__ava" src="{{ auth()->user()->photo_asset_url }}" alt="ava">
    </x-slot:button>

    <x-slot:content>
        <a class="dropdown__content-link" href="{{ route('profile.edit') }}">
            <x-misc.material-symbol icon="account_circle" filled="true" />
            {{ __('My profile') }}
        </a>

        <form class="dropdown__content-form" action="{{ route('logout') }}" method="POST">
            @csrf

            <button class="dropdown__content-button">
                <x-misc.material-symbol icon="logout" filled="true" />
                {{ __('Logout') }}
            </button>
        </form>
    </x-slot:content>
</x-misc.dropdown>
