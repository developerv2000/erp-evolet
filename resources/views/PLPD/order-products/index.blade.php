@extends('layouts.app', [
    'pageTitle' => __('Order products'),
    'pageName' => 'plpd-order-products-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('plpd.order-products.index'), 'text' => __('Order products')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-PLPD-order-products')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                    </x-misc.button>
                @endcan

                {{-- @can('export-records-as-excel')
                    <x-form.misc.export-as-excel-form action="{{ route('plpd.order-products.export-as-excel') }}" />
                @endcan --}}

                <x-misc.buttoned-link
                    class="toolbar__button"
                    style="shadowed"
                    link="{{ route('plpd.order-products.trash') }}"
                    icon="delete">{{ __('Trash') }}
                </x-misc.buttoned-link>

                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="settings"
                    data-click-action="show-modal"
                    data-modal-selector=".edit-table-columns-modal">{{ __('Columns') }}
                </x-misc.button>

                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="fullscreen"
                    data-click-action="request-fullscreen"
                    data-target-selector="{{ '.main-wrapper' }}">{{ __('Fullscreen') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Table --}}
        @include('PLPD.order-products.table.layout', ['trashedRecords' => false])
    </div>

    {{-- Modals --}}
    <x-modals.edit-table-columns
        form-action="{{ route('settings.update-table-columns', 'PLPD_order_products_table_columns') }}"
        :columns="$allTableColumns" />

    @can('edit-PLPD-order-products')
        <x-modals.multiple-delete
            form-action="{{ route('plpd.order-products.destroy') }}"
            :forceDelete="false" />
    @endcan
@endsection

@section('rightbar')
    @include('PLPD.order-products.partials.filter')
@endsection
