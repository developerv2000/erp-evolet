@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' — ' . __('Invoices'),
    'pageName' => 'cmd-invoices-create',
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
                    ['link' => route('cmd.invoices.index', ['order_id[]' => $order->id]), 'text' => __('Invoices')],
                    ['link' => null, 'text' => __('Create new')],
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
        @include('CMD.invoices.partials.create-form')
    </div>
@endsection
