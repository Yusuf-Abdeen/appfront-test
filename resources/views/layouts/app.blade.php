<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Product Store')</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('styles')
</head>

<body>
    <div class="@yield('container-class', 'container')">
        @yield('content')
    </div>

    @yield('scripts')
</body>

</html>
