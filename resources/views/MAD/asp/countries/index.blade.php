@extends('layouts.app', [
    'pageTitle' => __('Countries') . ' - ' . __('ASP') . ' ' . $record->title,
    'pageName' => 'mad-asp-countries-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ...$record->generateBreadcrumbs(),
                    ['link' => null, 'text' => __('Countries')]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-MAD-ASP')
                    <x-misc.buttoned-link
                        class="toolbar__button"
                        style="shadowed"
                        link="{{ route('mad-asp.countries.create', $record->year) }}"
                        icon="add">{{ __('New') }}
                    </x-misc.buttoned-link>

                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                    </x-misc.button>
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
        @include('mad-asp.countries.partials.table')
    </div>

    {{-- Modals --}}
    @can('edit-MAD-ASP')
        <x-modals.multiple-delete
            form-action="{{ route('mad-asp.countries.destroy', $record->year) }}"
            :forceDelete="false" />
    @endcan
@endsection
