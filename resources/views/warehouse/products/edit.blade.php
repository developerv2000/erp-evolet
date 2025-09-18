@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('Warehouse'),
    'pageName' => 'warehouse-products-edit',
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
                    ['link' => null, 'text' => $record->title]
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
                    form="edit-form"
                    icon="done_all">{{ __('Update') }}
                </x-misc.button>
            </div>
        </div>

        {{-- About order --}}
        {{-- @include('ELD.order-products.partials.about-order', ['order' => $record->order]) --}}

        {{-- Edit form --}}
        @include('warehouse.products.partials.edit-form')
    </div>

@endsection
