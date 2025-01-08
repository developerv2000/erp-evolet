<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/main/favicon.png') }}">

    {{-- blade-formatter-disable-next-line --}}
    <title>@isset($pageTitle){{ $pageTitle }}@else{{ 'ERP — EVOLET' }}@endisset</title>

    {{-- Noindex tags --}}
    <x-misc.noindex-tags />

    @vite(['resources/css/main.css', 'resources/css/themes/dark.css'])
</head>

<body class="body auth-page {{ $pageName }}">
    <main class="main">
        @yield('content')
    </main>
</body>

</html>
