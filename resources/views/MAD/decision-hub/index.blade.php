@extends('layouts.app', [
    'pageTitle' => 'DH & DSS',
    'pageName' => 'mad-decision-hub-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('mad.decision-hub.index'), 'text' => __('DH & DSS')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->count()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
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
        @if ($errors->any())
            @include('layouts.errors')
        @else
            @include('MAD.decision-hub.table.layout')
        @endif
    </div>

    {{-- Modals --}}
    <x-modals.edit-table-columns
        form-action="{{ route('settings.update-table-columns', 'MAD_DH_table_columns') }}"
        :columns="$allTableColumns" />
@endsection

@section('rightbar')
    @include('MAD.decision-hub.partials.filter')
@endsection
