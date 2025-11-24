@canany(['view-export-assemblages', 'view-export-batches'])
    <div class="leftbar__section leftbar__section--export">
        <p class="leftbar__section-title">{{ __('Export') }}</p>

        <nav class="leftbar__nav">
            {{-- Asseblages --}}
            @can('view-export-assemblages')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('export.assemblages.*'),
                    ])
                    href="{{ route('export.assemblages.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="box" />
                    <span class="leftbar__nav-link-text">{{ __('Assemblages') }}</span>
                </a>
            @endcan

            {{-- Batches --}}
            @can('view-export-batches')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('export.batches.*'),
                    ])
                    href="{{ route('export.batches.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="medication" />
                    <span class="leftbar__nav-link-text">{{ __('Batches') }}</span>
                </a>
            @endcan

            {{-- Invoices --}}
            @can('view-export-invoices')
                <a
                    @class([
                        'leftbar__nav-link',
                        'leftbar__nav-link--active' => request()->routeIs('export.invoices.*'),
                    ])
                    href="{{ route('export.invoices.index') }}">

                    <x-misc.material-symbol class="leftbar__nav-link-icon" icon="receipt_long" />
                    <span class="leftbar__nav-link-text">{{ __('Invoices') }}</span>
                </a>
            @endcan
        </nav>
    </div>
@endcanany
