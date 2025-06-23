@canany(['view-DD-order-products'])
    <div class="leftbar__section leftbar__section--DD">
        <p class="leftbar__section-title">{{ __('DD') }}</p>

        <nav class="leftbar__nav">
            {{-- Orders --}}
            @can('view-DD-orders')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs(
                            'dd.order-products.*'),
                    ])
                    href="{{ route('dd.order-products.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Products') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
