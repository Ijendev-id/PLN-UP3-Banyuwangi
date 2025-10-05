@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manajemen Data Pemeliharaan</h1>

    <form action="{{ url('manajemen-data/pemeliharaan') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="aktivitas" class="form-label">Aktivitas</label>
            <input type="text" name="aktivitas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
</div>
@endsection

