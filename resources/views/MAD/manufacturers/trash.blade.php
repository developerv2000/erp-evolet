@extends('layouts.app', [
    'pageTitle' => __('Trash') . ' — ' . __('EPP'),
    'pageName' => 'manufacturers-trash',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('mad.manufacturers.index'), 'text' => __('EPP')],
                    ['link' => route('mad.manufacturers.trash'), 'text' => __('Trash')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('delete-from-trash')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Permanently delete selected') }}
                    </x-misc.button>
                @endcan

                @can('edit-MAD-EPP')
                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="history"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-restore-modal">{{ __('Restore selected') }}
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
        @include('MAD.manufacturers.table.layout', ['trashedRecords' => true])
    </div>

    {{-- Modals --}}
    @can('delete-from-trash')
        <x-modals.multiple-delete
            form-action="{{ route('mad.manufacturers.destroy') }}"
            :forceDelete="true" />
    @endcan

    @can('edit-MAD-EPP')
        <x-modals.multiple-restore
            form-action="{{ route('mad.manufacturers.restore') }}" />

        <x-modals.target-restore />
    @endcan
@endsection

@section('rightbar')
    @include('MAD.manufacturers.partials.filter')
@endsection
