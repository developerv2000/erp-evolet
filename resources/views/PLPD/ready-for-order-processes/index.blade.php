@extends('layouts.app', [
    'pageTitle' => __('Ready for order'),
    'pageName' => 'plpd-ready-for-order-process',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box styled-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('plpd.processes.ready-for-order.index'), 'text' => __('Ready for order')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
            </div>
        </div>

        {{-- Table --}}
        @include('PLPD.ready-for-order-processes.partials.table')
    </div>
@endsection

@section('rightbar')
    @include('PLPD.ready-for-order-processes.partials.filter')
@endsection
