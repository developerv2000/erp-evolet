@extends('layouts.app', [
    'pageName' => 'manufacturers-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => __('EPP')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-MAD-EPP')
                    <x-misc.buttoned-link
                        class="toolbar__button"
                        style="shadowed"
                        link="{{ route('manufacturers.create') }}"
                        icon="add">{{ __('New') }}
                    </x-misc.buttoned-link>

                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="delete"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                    </x-misc.button>
                @endcan

                @can('export-records-as-excel')
                    <x-form.misc.export-as-excel-form action="{{ route('manufacturers.export-as-excel') }}" />
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
        <x-tables.manufacturers-table :records="$records" :visibleTableColumns="$visibleTableColumns" />
    </div>

    {{-- Modals --}}
    @can('edit-MAD-EPP')
        <x-modals.multiple-delete form-action="{{ route('manufacturers.destroy') }}" :forceDelete="false" />
    @endcan
@endsection

@section('rightbar')
    @include('manufacturers.partials.filter')
@endsection
