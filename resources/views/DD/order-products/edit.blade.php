@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('Products'),
    'pageName' => 'dd-order-products-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            <x-layouts.breadcrumbs :crumbs="$record->generateBreadcrumbs('DD')" />

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
        @include('DD.order-products.partials.about-order', ['order' => $record->order])

        {{-- Edit form --}}
        @include('DD.order-products.partials.edit-form')
    </div>

@endsection
