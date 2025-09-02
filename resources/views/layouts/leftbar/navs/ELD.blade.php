@canany(['view-ELD-order-products', 'view-ELD-invoices'])
    <div class="leftbar__section leftbar__section--ELD">
        <p class="leftbar__section-title">{{ __('ELD') }}</p>

        <nav class="leftbar__nav">
            {{-- Order products --}}
            @can('view-ELD-order-products')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('eld.order-products.*'),
                    ])
                    href="{{ route('eld.order-products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Products') }}</span>
                </a>
            @endcan

            {{-- Order invoices --}}
            @can('view-ELD-invoices')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('eld.invoices.*'),
                    ])
                    href="{{ route('eld.invoices.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="receipt_long" />
                    <span class="leftbar__nav-link-text">{{ __('Invoices') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
