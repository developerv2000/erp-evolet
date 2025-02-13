@extends('layouts.app', [
    'pageTitle' => __('Misc') . ' — ERP',
    'pageName' => 'misc-models-department-models',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => __('Misc')],
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />
        </div>

        {{-- Table --}}
        @include('misc-models.partials.department-models-table')
    </div>
@endsection
