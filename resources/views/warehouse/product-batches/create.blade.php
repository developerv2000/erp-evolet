@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' — ' . __('Batches'),
    'pageName' => 'warehouse-product-batches-create',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => __('Warehouse')],
                    ['link' => route('warehouse.products.index'), 'text' => __('Products')],
                    ['link' => route('warehouse.product-batches.index'), 'text' => __('Batches')],
                    ['link' => null, 'text' => __('Create new record')],
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
        @include('warehouse.product-batches.partials.create-form')
    </div>
@endsection
