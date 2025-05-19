@can('administrate')
    <div class="leftbar__section leftbar__section--managment">
        <p class="leftbar__section-title">Managment</p>

        <nav class="leftbar__nav">
            {{-- Departments --}}
            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs('departments.index'),
                ])
                href="{{ route('departments.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="home_work" />
                <span class="leftbar__nav-link-text">{{ __('Departments') }}</span>
            </a>

            {{-- Roles --}}
            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs('roles.index'),
                ])
                href="{{ route('roles.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="3p" />
                <span class="leftbar__nav-link-text">{{ __('Roles') }}</span>
            </a>

            {{-- Permissions --}}
            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs('permissions.index'),
                ])
                href="{{ route('permissions.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="lock_open" />
                <span class="leftbar__nav-link-text">{{ __('Permissions') }}</span>
            </a>

            {{-- Users --}}
            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs('users.index'),
                ])
                href="{{ route('users.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="face" />
                <span class="leftbar__nav-link-text">{{ __('Users') }}</span>
            </a>
        </nav>
    </div>
@endcan
