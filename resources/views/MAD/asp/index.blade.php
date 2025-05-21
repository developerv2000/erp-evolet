@extends('layouts.app', [
    'pageTitle' => __('ASP'),
    'pageName' => 'mad-asp-index',
    'mainAutoOverflowed' => true,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar toolbar--joined toolbar--for-table">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('mad.asp.index'), 'text' => __('ASP')],
                    ['link' => null, 'text' => __('Filtered records') . ' — ' . $records->total()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                @can('edit-MAD-ASP')
                    <x-misc.buttoned-link
                        class="toolbar__button"
                        style="shadowed"
                        link="{{ route('mad.asp.create') }}"
                        icon="add">{{ __('New') }}
                    </x-misc.buttoned-link>

                    <x-misc.button
                        class="toolbar__button"
                        style="shadowed"
                        icon="close"
                        data-click-action="show-modal"
                        data-modal-selector=".multiple-delete-modal">{{ __('Delete selected') }}
                    </x-misc.button>
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
        @include('MAD.asp.partials.index-page-table')

        {{-- Graph --}}
        @if ($currentYearASP)
            @include('MAD.asp.partials.countries-graph')
        @endif
    </div>

    {{-- Modals --}}
    @can('edit-MAD-ASP')
        <x-modals.multiple-delete
            form-action="{{ route('mad.asp.destroy') }}"
            :forceDelete="false" />
    @endcan
@endsection

@push('scripts')
    {{-- Apache ECharts --}}
    <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>

    <script>
        var asp = <?php echo json_encode($asp); ?>;
    </script>
@endpush
