@extends('layouts.app', [
    'pageTitle' => __('KPI'),
    'pageName' => 'mad-kpi',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        <div class="mad-kpi__pies-wrapper">
            @include('MAD.kpi.partials.current-processes.pie')
            @include('MAD.kpi.partials.maximum-processes.pie')
        </div>

        @include('MAD.kpi.partials.current-processes.table')
        @include('MAD.kpi.partials.current-processes.graph')
        @include('MAD.kpi.partials.current-processes.map')

        @include('MAD.kpi.partials.maximum-processes.table')
        @include('MAD.kpi.partials.maximum-processes.graph')

        @include('MAD.kpi.partials.active-manufacturers.table')
        @include('MAD.kpi.partials.active-manufacturers.graph')
    </div>
@endsection

@section('rightbar')
    @include('MAD.kpi.partials.filter')
@endsection

@push('scripts')
    {{-- Apache ECharts --}}
    <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>

    <script>
        var kpi = <?php echo json_encode($kpi); ?>;
    </script>
@endpush
