@extends('layouts.app', [
    'pageTitle' => __('Create new') . ' ' . __('MAH') . ' — ' . __('ASP'),
    'pageName' => 'mad-asp-mahs-create',
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
                    ['link' => null, 'text' => $country->code],
                    ['link' => route('mad-asp.mahs.index', ['record' => $record->year, 'country' => $country->id]), 'text' => __('MAH')],
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
        @include('mad-asp.mahs.partials.create-form')
    </div>

@endsection
