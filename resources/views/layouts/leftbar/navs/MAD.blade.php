@canany(['view-MAD-EPP', 'view-MAD-KVPP', 'view-MAD-IVP', 'view-MAD-VPS', 'view-MAD-meetings'])
    <div class="leftbar__section leftbar__section--MAD">
        <p class="leftbar__section-title">MAD</p>

        <nav class="leftbar__nav">
            {{-- EPP --}}
            @can('view-MAD-EPP')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('manufacturers.*'),
                    ])
                    href="{{ route('manufacturers.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="view_list" />
                    <span class="leftbar__nav-link-text">{{ __('EPP') }}</span>
                </a>
            @endcan

            {{-- KVPP --}}
            @can('view-MAD-KVPP')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('product-searches.*'),
                    ])
                    href="{{ route('product-searches.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="content_paste_search" />
                    <span class="leftbar__nav-link-text">{{ __('KVPP') }}</span>
                </a>
            @endcan

            {{-- IVP --}}
            @can('view-MAD-IVP')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('products.*'),
                    ])
                    href="{{ route('products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="pill" />
                    <span class="leftbar__nav-link-text">{{ __('IVP') }}</span>
                </a>
            @endcan

            {{-- VPS --}}
            @can('view-MAD-VPS')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('processes.*'),
                    ])
                    href="{{ route('processes.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="stacks" />
                    <span class="leftbar__nav-link-text">{{ __('VPS') }}</span>
                </a>
            @endcan

            {{-- Meetings --}}
            @can('view-MAD-Meetings')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('meetings.*'),
                    ])
                    href="{{ route('meetings.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="calendar_month" />
                    <span class="leftbar__nav-link-text">{{ __('Meetings') }}</span>
                </a>
            @endcan

            {{-- KPI --}}
            @can('view-MAD-KPI')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('mad-kpi.index'),
                    ])
                    href="{{ route('mad-kpi.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="bar_chart" />
                    <span class="leftbar__nav-link-text">{{ __('KPI') }}</span>
                </a>
            @endcan

            {{-- ASP --}}
            @can('view-MAD-ASP')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('mad-asp.index'),
                    ])
                    href="{{ route('mad-asp.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="pie_chart" />
                    <span class="leftbar__nav-link-text">{{ __('ASP') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
