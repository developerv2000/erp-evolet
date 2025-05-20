@extends('layouts.app', [
    'pageTitle' => $model['caption'] . ' — ' . __('Misc'),
    'pageName' => 'misc-models-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    {{-- Errors --}}
    @include('layouts.errors')

    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => __('Misc')],
                    ['link' => null, 'text' => $model['caption']],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}

            <div class="toolbar__buttons-wrapper">
                @can('edit-MAD-Misc')
                    <x-misc.buttoned-link
                        class="toolbar__button"
                        style="shadowed"
                        link="{{ route('misc-models.create', $model['name']) }}"
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
        @include('global.misc-models.partials.index-table')
    </div>

    {{-- Modals --}}
    @can('edit-MAD-Misc')
        <x-modals.multiple-delete form-action="{{ route('misc-models.destroy', $model['name']) }}" :forceDelete="false" />
    @endcan
@endsection

@section('rightbar')
    @include('global.misc-models.partials.filter')
@endsection
