@canany(['view-warehouse-products', 'view-warehouse-product-batches'])
    <div class="leftbar__section leftbar__section--warehouse">
        <p class="leftbar__section-title">{{ __('Warehouse') }}</p>

        <nav class="leftbar__nav">
            {{-- Products --}}
            @can('view-warehouse-products')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('warehouse.products.*'),
                    ])
                    href="{{ route('warehouse.products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Products') }}</span>
                </a>
            @endcan

            {{-- Batches --}}
            @can('view-warehouse-product-batches')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('warehouse.product-batches.*'),
                    ])
                    href="{{ route('warehouse.product-batches.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="receipt_long" />
                    <span class="leftbar__nav-link-text">{{ __('Batches') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
