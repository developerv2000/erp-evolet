@extends('layouts.app', [
    'pageTitle' => __('KPI'),
    'pageName' => 'mad-kpi-index',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('mad-kpi.index'), 'text' => __('KPI')]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />
        </div>

        {{-- Charts and tables --}}
        {{-- @include('mad-kpi.partials.current-processes.pie') --}}
    </div>
@endsection

@section('rightbar')
    @include('mad-kpi.partials.filter')
@endsection

@push('scripts')
    {{-- Apache ECharts --}}
    <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>

    <script>
        var kpi = <?php echo json_encode($kpi); ?>;
    </script>
@endpush
