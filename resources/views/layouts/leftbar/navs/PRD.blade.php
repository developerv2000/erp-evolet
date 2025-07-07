@canany(['view-PRD-orders', 'view-PRD-order-products', 'view-PRD-invoices'])
    <div class="leftbar__section leftbar__section--PRD">
        <p class="leftbar__section-title">{{ __('PRD') }}</p>

        <nav class="leftbar__nav">
            {{-- Order invoices --}}
            @can('view-PRD-invoices')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('prd.invoices.*'),
                    ])
                    href="{{ route('prd.invoices.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="receipt_long" />
                    <span class="leftbar__nav-link-text">{{ __('Invoices') }}</span>
                </a>
            @endcan

            {{-- Orders --}}
            @can('view-PRD-orders')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('prd.orders.index'),
                    ])
                    href="{{ route('prd.orders.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="package_2" />
                    <span class="leftbar__nav-link-text">{{ __('Orders') }}</span>
                </a>
            @endcan

            {{-- Order products --}}
            @can('view-PRD-order-products')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs(
                            'prd.order-products.index'),
                    ])
                    href="{{ route('prd.order-products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Products') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
