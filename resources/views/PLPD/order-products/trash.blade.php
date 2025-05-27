@extends('layouts.app', [
    'pageTitle' => __('Trash') . ' — ' . __('Order products'),
    'pageName' => 'PLPD-order-products-trash',
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
                    ['link' => route('plpd.order-products.trash'), 'text' => __('Trash')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('delete-from-trash')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Permanently delete selected') }}
                    </x-misc.button>
                @endcan

                @can('edit-PLPD-order-products')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="history"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-restore-modal">{{ __('Restore selected') }}
                    </x-misc.button>
                @endcan

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
        @include('PLPD.order-products.table.layout', ['trashedRecords' => true])
    </div>

    {{-- Modals --}}
    @can('delete-from-trash')
        <x-modals.multiple-delete
            form-action="{{ route('plpd.order-products.destroy') }}"
            :forceDelete="true" />
    @endcan

    @can('edit-PLPD-order-products')
        <x-modals.multiple-restore
            form-action="{{ route('plpd.order-products.restore') }}" />

        <x-modals.target-restore />
    @endcan
@endsection

@section('rightbar')
    @include('PLPD.order-products.partials.filter')
@endsection
