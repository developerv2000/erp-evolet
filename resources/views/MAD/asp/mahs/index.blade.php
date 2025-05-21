@extends('layouts.app', [
    'pageTitle' => __('MAH') . ' - ' . $country->code . ' - ' . __('ASP') . ' ' . $record->title,
    'pageName' => 'mad-asp-mahs-index',
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
                    ['link' => route('mad.asp.countries.index', $record->year), 'text' => __('Countries')],
                    ['link' => null, 'text' => $country->code],
                    ['link' => null, 'text' => __('MAH')],
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
                        link="{{ route('mad.asp.mahs.create', ['record' => $record->year, 'country' => $country->id]) }}"
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
        @include('MAD.asp.mahs.partials.table')
    </div>

    {{-- Modals --}}
    @can('edit-MAD-ASP')
        <x-modals.multiple-delete
            form-action="{{ route('mad.asp.mahs.destroy', ['record' => $record->year, 'country' => $country->id]) }}"
            :forceDelete="false" />
    @endcan
@endsection
