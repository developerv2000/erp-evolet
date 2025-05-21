@extends('layouts.app', [
    'pageTitle' => __('ASP') . ' | ' . $record->title,
    'pageName' => 'mad-asp-show',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            <x-layouts.breadcrumbs :crumbs="$record->generateBreadcrumbs()" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('export-records-as-excel')
                    <x-form.misc.export-as-excel-form action="{{ route('mad.asp.export-as-excel', $record->year) }}" />
                @endcan

                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    icon="fullscreen"
                    data-click-action="request-fullscreen"
                    data-target-selector="{{ '.main-wrapper' }}">{{ __('Fullscreen') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Table --}}
        @include('MAD.asp.partials.show-page-table')

        {{-- Graph --}}
        @include('MAD.asp.partials.countries-graph')
    </div>
@endsection

@section('rightbar')
    @include('MAD.asp.partials.show-page-filter')
@endsection

@push('scripts')
    {{-- Apache ECharts --}}
    <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>

    <script>
        var asp = <?php echo json_encode($asp); ?>;
    </script>
@endpush
