@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' — ' . __('Products'),
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
                    ...$order->generateBreadcrumbs('CMD'),
                    ['link' => route('plpd.order-products.index', ['order_id' => $order->id]), 'text' => __('Products')],
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

        {{-- About order --}}
        @include('PLPD.order-products.partials.about-order', ['order' => $order])

        {{-- Create form --}}
        @include('PLPD.order-products.partials.create-form')
    </div>

@endsection
