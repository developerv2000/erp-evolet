@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' — ' . __('VPS'),
    'pageName' => 'processes-create',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('processes.index'), 'text' => __('VPS')],
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
        @include('processes.partials.about-product')
        @include('processes.partials.create-form')
    </div>

@endsection