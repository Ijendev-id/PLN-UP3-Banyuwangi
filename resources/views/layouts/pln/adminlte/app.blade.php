<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - Desa')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('asset_halaman_desa/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    {{-- link icon https://fontawesome.com/icons --}}
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('asset_halaman_desa/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet"
        href="{{ asset('asset_halaman_desa/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('asset_halaman_desa/adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('asset_halaman_desa/adminlte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('asset_halaman_desa/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ asset('asset_halaman_desa/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('asset_halaman_desa/adminlte/plugins/summernote/summernote-bs4.min.css') }}">
    {{-- css crud --}}
    <link rel="stylesheet" href="{{ asset('asset_halaman_desa/css/crud.css') }}">
    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Pusher Tags --}}
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 4 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('asset_halaman_desa/adminlte/dist/img/AdminLTELogo.png') }}"
                alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Header -->
        @include('layouts.pln.adminlte.header')
        <!-- /.Header -->

        @include('layouts.pln.adminlte.navbar')

        <!-- Isi Konten -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('layouts.pln.adminlte.footer')<!-- Include Footer -->
        <!-- / .Footer -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script
        src="{{ asset('asset_halaman_desa/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>
    <!-- Summernote -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}">
    </script>
    <!-- AdminLTE App -->
    <script src="{{ asset('asset_halaman_desa/adminlte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('asset_halaman_desa/adminlte/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('asset_halaman_desa/adminlte/dist/js/pages/dashboard.js') }}"></script>
    {{-- <!-- jquery-validation -->
    <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        $(function() {
            // Summernote
            $("#summernote").summernote();
        });
    </script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>

</html>
