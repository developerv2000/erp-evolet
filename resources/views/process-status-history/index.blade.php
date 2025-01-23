@extends('layouts.app', [
    'pageTitle' => __('Status history') . ' — ' . $process->title,
    'pageName' => 'process-status-history-index',
    'mainAutoOverflowed' => false,
])

@section('content')
    {{-- About product --}}
    @include('processes.partials.about-product')

    {{-- Errors --}}
    @include('layouts.errors')

    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                        ...$process->generateBreadcrumbs(),
                        ['link' => null, 'text' => __('Status history')]
                    ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="delete"
                    data-click-action="show-modal"
                    data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Table --}}
        @include('process-status-history.partials.table')

        {{-- Modals --}}
        <x-modals.multiple-delete form-action="{{ route('processes.status-history.destroy', $process->id) }}" :forceDelete="false" />
        <x-modals.target-delete :forceDelete="false" />
    </div>
@endsection
