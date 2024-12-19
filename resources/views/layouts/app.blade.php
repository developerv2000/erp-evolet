<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/main/favicon.png') }}">

    {{-- blade-formatter-disable-next-line --}}
    <title>@isset($pageTitle){{ $pageTitle . ' — ERP' }}@else{{ 'ERP — EVOLET' }}@endisset</title>

    {{-- Noindex tags --}}
    <x-misc.noindex-tags />

    {{-- All css plugins are included inside main.css --}}
    @vite('resources/css/main.css')

    {{-- Users prefered theme --}}
    @vite('resources/css/themes/' . request()->user()->settings['preferred_theme'] . '.css')
</head>

<body class="body {{ $pageName }}">
    <div class="body__inner">
        @include('layouts.header')

        <div class="main-wrapper">
            @include('layouts.leftbar')

            <main class="main @if ($mainAutoOverflowed) main--auto-overflowed @endif">
                @yield('content')
            </main>

            @hasSection('rightbar')
                @yield('rightbar')
            @endif
        </div>
    </div>

    {{-- Spinner --}}
    <x-misc.spinner />

    {{-- JQuery --}}
    <script src="{{ asset('plugins/jquery-3.6.4.min.js') }}"></script>

    {{-- Selectize --}}
    <script src="{{ asset('plugins/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('plugins/selectize/preserve-on-blur-plugin/preserve-on-blur.js') }}"></script>

    {{-- Moment.js (required in Date range picker) --}}
    <script src="{{ asset('plugins/moment.min.js') }}"></script>

    {{-- JQuery Date range picker --}}
    <script src="{{ asset('plugins/date-range-picker/daterangepicker.min.js') }}"></script>

    {{-- JQuery UI. Required for JQuery Nested Sortable plugin --}}
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    {{-- JQuery Nested Sortable --}}
    <script src="{{ asset('plugins/jquery-nested-sortable.js') }}"></script>

    {{-- Simditor v2.3.28 --}}
    <script src="{{ asset('plugins/simditor/module.js') }}"></script>
    <script src="{{ asset('plugins/simditor/hotkeys.js') }}"></script>
    <script src="{{ asset('plugins/simditor/uploader.js') }}"></script>
    <script src="{{ asset('plugins/simditor/simditor.js') }}"></script>

    @vite('resources/js/main.js')
</body>

</html>
