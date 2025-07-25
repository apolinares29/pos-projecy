<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('components.notifications')
    @yield('styles')
</head>
<body class="bg-gray-50">
    @yield('content')
    @yield('scripts')
</body>
</html> 