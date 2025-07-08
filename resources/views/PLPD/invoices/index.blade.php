@extends('layouts.app', [
    'pageTitle' => __('Invoices'),
    'pageName' => 'plpd-invoices-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('plpd.orders.index'), 'text' => __('Orders')],
                    ['link' => url()->current(), 'text' => __('Invoices')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->count()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
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
        @include('PLPD.invoices.table.layout', ['trashedRecords' => false])
    </div>

    {{-- Modals --}}
    @can('edit-plpd-invoices')
        <x-modals.edit-table-columns
            form-action="{{ route('settings.update-table-columns', 'PLPD_invoices_table_columns') }}"
            :columns="$allTableColumns" />
    @endcan
@endsection

@section('rightbar')
    @include('PLPD.invoices.partials.filter')
@endsection
