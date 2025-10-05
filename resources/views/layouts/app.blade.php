<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Aplikasi') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap via CDN (opsional) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSRF token untuk Ajax/form --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Aplikasi') }}</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ url('manajemen-data/gardu') }}">Gardu</a>
                <a class="nav-link" href="{{ url('manajemen-data/omt-pengukuran') }}">OMT Pengukuran</a>
                <a class="nav-link" href="{{ url('manajemen-data/pemeliharaan') }}">Pemeliharaan</a>
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- Bootstrap JS (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
