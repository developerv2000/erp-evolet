@can(['view-MSD-order-products'])
    <div class="leftbar__section leftbar__section--MSD">
        <p class="leftbar__section-title">{{ __('MSD') }}</p>

        <nav class="leftbar__nav">
            {{-- Order products --}}
            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs(
                        'msd.order-products.serialized-by-manufacturer.index'),
                ])
                href="{{ route('msd.order-products.serialized-by-manufacturer.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="warehouse" />
                <span class="leftbar__nav-link-text">{{ __('Factory') }}</span>
            </a>

            <a
                @class([
                    'leftbar__nav-link',
                    'leftbar__nav-link--active' => request()->routeIs(
                        'msd.product-batches.*'),
                ])
                href="{{ route('msd.product-batches.index') }}">

                <x-misc.material-symbol class="leftbar__nav-link-icon" icon="local_shipping" />
                <span class="leftbar__nav-link-text">{{ __('Riga') }}</span>
            </a>
        </nav>
    </div>
@endcanany
