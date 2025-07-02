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
                    ...$order->generateBreadcrumbs('CMD'),
                    ['link' => url()->current(), 'text' => __('Invoices')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->count()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-cmd-invoices')
                    @if ($order->canAttachNewInvoice())
                        <x-misc.buttoned-link
                            class="toolbar__button"
                            style="shadowed"
                            link="{{ route('cmd.invoices.create', $order->id) }}"
                            icon="add">{{ __('New') }}
                        </x-misc.buttoned-link>
                    @endif
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
        @include('CMD.invoices.partials.table')
    </div>
@endsection
