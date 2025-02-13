@extends('layouts.app', [
    'pageTitle' => $model['caption'] . ' — ERP',
    'pageName' => 'misc-models-index',
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
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />
        </div>

        {{-- Table --}}
        @include('misc-models.partials.index-table')
    </div>
@endsection
