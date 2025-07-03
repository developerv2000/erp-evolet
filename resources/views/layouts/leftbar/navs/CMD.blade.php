@canany(['view-CMD-orders', 'view-CMD-order-products', 'view-CMD-invoices'])
    <div class="leftbar__section leftbar__section--CMD">
        <p class="leftbar__section-title">{{ __('CMD') }}</p>

        <nav class="leftbar__nav">
            {{-- Orders --}}
            @can('view-CMD-orders')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('cmd.orders.*'),
                    ])
                    href="{{ route('cmd.orders.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="package_2" />
                    <span class="leftbar__nav-link-text">{{ __('Orders') }}</span>
                </a>
            @endcan

            {{-- Order products --}}
            @can('view-CMD-order-products')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('cmd.order-products.*'),
                    ])
                    href="{{ route('cmd.order-products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Products') }}</span>
                </a>
            @endcan

            {{-- Order invoices --}}
            @can('view-CMD-invoices')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('cmd.invoices.*'),
                    ])
                    href="{{ route('cmd.invoices.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="receipt_long" />
                    <span class="leftbar__nav-link-text">{{ __('Invoices') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
