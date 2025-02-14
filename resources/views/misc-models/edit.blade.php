@extends('layouts.app', [
    'pageTitle' => $record->name . ' — ' . $model['caption'],
    'pageName' => 'misc-models-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => __('Misc')],
                    ['link' => null, 'text' => $model['caption']],
                    ['link' => null, 'text' => $record->name]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    type="submit"
                    form="edit-form"
                    icon="done_all">{{ __('Update') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Edit form --}}
        @include('misc-models.partials.edit-form')
    </div>

@endsection
