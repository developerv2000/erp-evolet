@extends('layouts.app', [
    'pageTitle' => __('ASP') . ' | ' . $record->title,
    'pageName' => 'mad-asp-show',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            <x-layouts.breadcrumbs :crumbs="$record->generateBreadcrumbs()" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('export-records-as-excel')
                    <x-form.misc.export-as-excel-form action="{{ route('mad-asp.export-as-excel', $record->year) }}" />
                @endcan

                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="fullscreen"
                    data-click-action="request-fullscreen"
                    data-target-selector="{{ '.main-wrapper' }}">{{ __('Fullscreen') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Table --}}
        @include('mad-asp.partials.show-page-table')
    </div>
@endsection

@section('rightbar')
    @include('mad-asp.partials.show-page-filter')
@endsection
