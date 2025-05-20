@extends('layouts.app', [
    'pageTitle' => __('Notifications') . ' — ERP',
    'pageName' => 'notifications-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('notifications.index'), 'text' => __('Notifications')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @include('global.notifications.partials.mark-as-read-form')

                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="close"
                    data-click-action="show-modal"
                    data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                </x-misc.button>

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
        @include('global.notifications.partials.table')
    </div>

    {{-- Modals --}}
    <x-modals.multiple-delete
        form-action="{{ route('notifications.destroy') }}"
        :forceDelete="false" />
@endsection
