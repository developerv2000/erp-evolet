@extends('layouts.app', [
    'pageTitle' => __('Users') . ' — ERP',
    'pageName' => 'users-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('users.index'), 'text' => __('Users')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.buttoned-link
                    class="toolbar__button"
                    style="shadowed"
                    link="{{ route('users.create') }}"
                    icon="add">{{ __('New') }}
                </x-misc.buttoned-link>

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
        @include('MGMT.users.partials.table')
    </div>
@endsection

@section('rightbar')
    @include('MGMT.users.partials.filter')
@endsection
