@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' — ' . __('Order products'),
    'pageName' => 'plpd-order-products-create',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('plpd.orders.index', ['id[]' => $order->id]), 'text' => __('Order') . ' #' . $order->id],
                    ['link' => route('plpd.order-products.index'), 'text' => __('Products')],
                    ['link' => null, 'text' => __('Create new record')]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    type="submit"
                    form="create-form"
                    icon="done_all">{{ __('Store') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Create form --}}
        @if ($readyForOrderProcesses->isEmpty())
            <div class="errors styled-box">
                <p class="errors__title main-title">{{ __('Error') }}!</p>

                <ol class="errors__list">
                    <li class="errors__list-item">{{ __('There are no available products for given order') }}!</li>
                </ol>
            </div>
        @else
            @include('PLPD.order-products.partials.create-form')
        @endif
    </div>
@endsection
