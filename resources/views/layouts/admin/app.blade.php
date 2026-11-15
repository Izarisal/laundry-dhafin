<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laundry') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets-admin/img/favicon/favicon-32x32.png') }}">

    @include('layouts.admin.css')
</head>

<body>
    @include('layouts.admin.sidebar')

    <main class="content">
        @include('layouts.admin.header')

        @include('layouts.admin.alerts')

        @yield('content')

        @include('layouts.admin.footer')
    </main>

    @include('layouts.admin.js')
    @stack('scripts')
</body>

</html>
