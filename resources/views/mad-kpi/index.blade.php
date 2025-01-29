@extends('layouts.app', [
    'pageTitle' => __('KPI'),
    'pageName' => 'mad-kpi',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        <div class="mad-kpi__pies-wrapper">
            @include('mad-kpi.partials.current-processes.pie')
            @include('mad-kpi.partials.maximum-processes.pie')
        </div>

        @include('mad-kpi.partials.current-processes.table')
        @include('mad-kpi.partials.current-processes.graph')

        @include('mad-kpi.partials.maximum-processes.table')
        @include('mad-kpi.partials.maximum-processes.graph')

        @include('mad-kpi.partials.active-manufacturers.table')
        @include('mad-kpi.partials.active-manufacturers.graph')
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
