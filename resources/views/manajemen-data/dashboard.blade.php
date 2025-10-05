@extends('layouts.pln.adminlte.app')

@section('title', 'Dasboard Petugas Desa')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('asset_halaman_desa/img/ban1.png') }}"
                                 alt="Safety Poster 1"
                                 class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('asset_halaman_desa/img/ban2.png') }}"
                                 alt="Safety Poster 2"
                                 class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection
