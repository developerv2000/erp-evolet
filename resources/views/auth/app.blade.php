<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-misc.noindex-tags />

    <link rel="icon" type="image/x-icon" href="{{ asset('img/main/favicon.png') }}">

    <title>{{ __('Login') }}</title>

    @vite(['resources/css/main.css', 'resources/css/themes/dark.css'])
</head>

<body class="body {{ $pageName }}">
    <main class="main">
        @yield('content')
    </main>
</body>

</html>
