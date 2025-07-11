@extends('layouts.app', [
    'pageTitle' => __('Invoices'),
    'pageName' => 'cmd-invoices-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('cmd.orders.index'), 'text' => __('Orders')],
                    ['link' => url()->current(), 'text' => __('Invoices')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->count()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-CMD-invoices')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                    </x-misc.button>
                @endcan

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
        @include('CMD.invoices.table.layout', ['trashedRecords' => false])
    </div>

    {{-- Modals --}}
    @can('edit-CMD-invoices')
        <x-modals.multiple-delete
            form-action="{{ route('cmd.invoices.destroy') }}"
            :forceDelete="true" />

        <x-modals.edit-table-columns
            form-action="{{ route('settings.update-table-columns', 'CMD_invoices_table_columns') }}"
            :columns="$allTableColumns" />
    @endcan
@endsection

@section('rightbar')
    @include('CMD.invoices.partials.filter')
@endsection
