@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('Riga'),
    'pageName' => 'msd-product-batches-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('msd.product-batches.index'), 'text' => __('Riga')],
                    ['link' => null, 'text' => $record->title]
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
        @include('MSD.product-batches.partials.edit-form')
    </div>

@endsection
