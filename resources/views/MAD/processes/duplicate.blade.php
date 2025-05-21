@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('VPS'),
    'pageName' => 'processes-duplicate',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                        ...$record->generateBreadcrumbs(),
                        ['link' => null, 'text' => __('Duplicate')]
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
                    icon="done_all">{{ __('Duplicate') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Duplicate form --}}
        @include('MAD.processes.partials.about-product')
        @include('MAD.processes.partials.duplicate-form')
    </div>
@endsection
