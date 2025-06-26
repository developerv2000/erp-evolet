@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('Products'),
    'pageName' => 'cmd-order-products-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            <x-layouts.breadcrumbs :crumbs="$record->generateBreadcrumbs('CMD')" />

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
        @include('CMD.order-products.partials.about-order', ['order' => $record->order])

        {{-- Edit form --}}
        @include('CMD.order-products.partials.edit-form')
    </div>

@endsection
