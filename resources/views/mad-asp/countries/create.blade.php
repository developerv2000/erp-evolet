@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' ' . __('country') . ' — ' . __('ASP'),
    'pageName' => 'mad-asp-countries-create',
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
                    ['link' => route('mad-asp.countries.index', $record->year), 'text' => __('Countries')],
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
        @include('mad-asp.countries.partials.create-form')
    </div>

@endsection
