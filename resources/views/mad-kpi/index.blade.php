@extends('layouts.app', [
    'pageTitle' => __('KPI'),
    'pageName' => 'mad-kpi',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        @include('mad-kpi.partials.process-pies')
        @include('mad-kpi.partials.current-processes.table')
        @include('mad-kpi.partials.current-processes.chart')
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
