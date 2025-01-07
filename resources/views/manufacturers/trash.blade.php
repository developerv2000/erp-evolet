@extends('layouts.app', [
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
                    ['link' => route('manufacturers.index'), 'text' => __('EPP')],
                    ['link' => route('manufacturers.trash'), 'text' => __('Trash')],
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
                    icon="settings"
                    data-click-action="show-modal"
                    data-modal-selector=".edit-table-columns-modal">{{ __('Columns') }}
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
        @include('manufacturers.table.layout', ['trashedRecords' => true])
    </div>

    {{-- Modals --}}
    <x-modals.edit-table-columns
        form-action="{{ route('settings.update-table-columns', 'MAD_EPP_table_columns') }}"
        :columns="$allTableColumns" />

    @can('delete-from-trash')
        <x-modals.multiple-delete
            form-action="{{ route('manufacturers.destroy') }}"
            :forceDelete="true" />
    @endcan

    @can('edit-MAD-EPP')
        <x-modals.multiple-restore
            form-action="{{ route('manufacturers.restore') }}" />

        <x-modals.target-restore />
    @endcan
@endsection

@section('rightbar')
    @include('manufacturers.partials.filter')
@endsection
