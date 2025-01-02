@extends('layouts.app', [
    'pageName' => 'manufacturers-create',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('manufacturers.index'), 'text' => __('EPP')],
                    ['link' => null, 'text' => __('Create new')]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.buttoned
                    class="toolbar__button"
                    style="shadowed"
                    type="submit"
                    form="create-form"
                    icon="done_all">{{ __('Store') }}
                </x-misc.buttoned>
            </div>
        </div>

        {{-- Create form --}}
    </div>

@endsection
