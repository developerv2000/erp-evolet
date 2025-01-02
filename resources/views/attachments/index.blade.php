@extends('layouts.app', [
    'pageName' => 'attachments-index',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            <x-layouts.breadcrumbs :crumbs="$breadcrumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="delete"
                    data-click-action="show-modal"
                    data-modal-selector=".multiple-delete-modal">{{ __('Delete') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Table --}}
        @include('attachments.partials.table')

        {{-- Modals --}}
        <x-modals.multiple-delete form-action="{{ route('attachments.destroy') }}" :forceDelete="false" />
        <x-modals.target-delete :forceDelete="false" />
    </div>
@endsection
