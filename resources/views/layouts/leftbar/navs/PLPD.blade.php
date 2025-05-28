@canany(['view-PLPD-ready-for-order-processes', 'view-PLPD-orders'])
    <div class="leftbar__section leftbar__section--PLPD">
        <p class="leftbar__section-title">{{ __('PLPD') }}</p>

        <nav class="leftbar__nav">
            {{-- Processes ready for order --}}
            @can('view-PLPD-ready-for-order-processes')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs(
                            'plpd.processes.ready-for-order.*'),
                    ])
                    href="{{ route('plpd.processes.ready-for-order.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="grading" />
                    <span class="leftbar__nav-link-text">{{ __('Ready for order') }}</span>
                </a>
            @endcan

            {{-- Orders --}}
            @can('view-PLPD-orders')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs(
                            'plpd.orders.*'),
                    ])
                    href="{{ route('plpd.orders.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="package_2" />
                    <span class="leftbar__nav-link-text">{{ __('Orders') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
