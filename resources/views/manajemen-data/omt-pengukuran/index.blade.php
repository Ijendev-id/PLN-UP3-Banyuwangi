@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manajemen Data OMT Pengukuran</h1>

    <form action="{{ url('manajemen-data/omt-pengukuran') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="parameter" class="form-label">Parameter</label>
            <input type="text" name="parameter" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" name="nilai" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
