@canany(['view-DD-orders'])
    <div class="leftbar__section leftbar__section--DD">
        <p class="leftbar__section-title">{{ __('DD') }}</p>

        <nav class="leftbar__nav">
            {{-- Orders --}}
            @can('view-DD-orders')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs(
                            'dd.orders.*'),
                    ])
                    href="{{ route('dd.orders.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="package_2" />
                    <span class="leftbar__nav-link-text">{{ __('Orders') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
