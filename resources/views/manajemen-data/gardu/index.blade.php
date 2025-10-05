@extends('layouts.pln.adminlte.app')

@section('title', 'Manajemen Gardu')

@section('content')
{{-- include css khusus tabel (opsional, kalau layout belum memuat) --}}
<link rel="stylesheet" href="{{ asset('asset_halaman_desa/css/crud.css') }}">

<div class="container-xl">
  <div class="table-responsive">
    <div class="table-wrapper">
      <div class="table-title">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center">
                {{-- Tombol CREATE --}}
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('gardu.create') }}" title="Tambah Gardu"
                        class="mr-3 d-inline-flex justify-content-center align-items-center"
                        style="width:64px;height:64px;background:#ffeb3b;border-radius:14px;color:#000;text-decoration:none;box-shadow:0 6px 18px rgba(0,0,0,.15);">
                        <i class="fa fa-plus" style="font-size:28px;line-height:1;"></i>
                    </a>
                @endif

                {{-- Tombol CETAK / PRINT (ganti routenya sesuai punyamu) --}}
                {{-- <a href="" title="Cetak / Export" target="_blank"
                    class="d-inline-flex justify-content-center align-items-center"
                    style="width:64px;height:64px;background:#ffeb3b;border-radius:14px;color:#000;text-decoration:none;box-shadow:0 6px 18px rgba(0,0,0,.15);">
                    <i class="fa fa-print" style="font-size:26px;line-height:1;"></i>
                </a> --}}
            </div>
            <br>
            <p>

          {{-- SEARCH --}}
            <div class="col-md-4 mt-3 mt-md-0">
                <form action="{{ url('manajemen-data/gardu') }}" method="GET"
                    class="d-flex align-items-center justify-content-end">

                    {{-- pertahankan filter saat search --}}
                    <input type="hidden" name="gardu_induk" value="{{ request('gardu_induk') }}">
                    <input type="hidden" name="kd_trf_gi"   value="{{ request('kd_trf_gi')   }}">

                    {{-- tombol search (ikon kaca pembesar) --}}
                    <button type="submit" title="Cari"
                            class="mr-2 d-inline-flex justify-content-center align-items-center"
                            style="width:48px;height:48px;background:#ffeb3b;border-radius:12px;border:0;box-shadow:0 6px 18px rgba(0,0,0,.15);">
                        <i class="fa fa-search" style="font-size:20px;color:#000;line-height:1;"></i>
                    </button>

                    {{-- kolom input putih bulat --}}
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="form-control"
                        placeholder="Search ..."
                        style="height:48px;border-radius:14px;padding:0 14px;">

                    <a href="{{ url('manajemen-data/gardu') }}" class="btn btn-outline-secondary ml-2"
                    style="height:48px;line-height:34px;border-radius:12px;">Reset</a>
                </form>
            </div>
        </div>
      </div>
      {{-- FLASH MESSAGE (banner Bootstrap) --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

@if (session('info'))
  <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
    <i class="fas fa-info-circle mr-2"></i>
    {{ session('info') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

      {{-- TABEL --}}
      <table class="table table-striped table-hover table-bordered">
        <thead>
          <tr>
            <th>Gardu Induk</th>
            <th>Trafo GI</th>
            <th>Jumlah Trafo</th>
            <th>Penyulang</th>
            <th>Kode Gardu</th>
            <th>Daya Trafo</th>
            <th>Alamat</th>
            <th>Desa</th>
            <th>Tahun</th>
            <th>Beban kVA Trafo</th>
            <th>Presentase Beban</th>
            <th style="width:140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($gardus as $i => $g)
          <tr>
            <td>{{ $g->gardu_induk }}</td>
            <td>{{ $g->kd_trf_gi }}</td>
            <td>{{ $g->jml_trafo }}</td>
            <td>{{ $g->kd_pylg }}</td>
            <td>{{ $g->kd_gardu }}</td>
            <td>{{ $g->daya_trafo }}</td>
            <td>{{ $g->alamat }}</td>
            <td>{{ $g->desa}}</td>
            <td>{{ $g->tahun }}</td>
            <td>{{ $g->beban_kva_trafo }}</td>
            <td>{{ $g->persentase_beban }}</td>
            <td>
    {{-- Detail (JSON) --}}
    <a href="{{ url('manajemen-data/gardu/'.$g->id) }}"
       class="view" title="Detail (JSON)" data-toggle="tooltip">
        <i class="fa fa-eye"></i>
    </a>

    {{-- Tampilkan Edit & Delete hanya untuk admin/petugas --}}
    @if(auth()->check() && in_array(auth()->user()->role, ['admin']))
        {{-- Edit --}}
        <a href="{{ route('gardu.edit', $g->id) }}"
           class="edit ml-2" title="Ubah" data-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>

        {{-- Delete --}}
        <form action="{{ url('manajemen-data/gardu/'.$g->id) }}"
              method="POST" class="d-inline delete-form ml-1">
            @csrf @method('DELETE')
            <button type="button" class="delete-btn"
                    title="Hapus" data-toggle="tooltip"
                    style="border:none; background:none;">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endif

    {{-- <br>
    @if(auth()->check() && in_array(auth()->user()->role, ['admin','petugas']))
        <a href="{{ route('omt-pengukuran.create', ['kd_gardu' => $g->kd_gardu]) }}"
           class="btn btn-sm btn-warning ml-2" title="Input Pengukuran">
            Pengukuran
        </a>
    @endif
    <br>

    @if(auth()->check() && in_array(auth()->user()->role, ['admin','petugas']))
        <a href="{{ route('pemeliharaan.create', ['kd_gardu' => $g->kd_gardu]) }}"
           class="btn btn-sm btn-info ml-2" title="Form Pemeliharaan">
            Pemeliharaan
        </a>
    @endif --}}
    </td>
          </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center">
                <div class="alert alert-warning mb-0">
                    <strong>Data tidak ditemukan!</strong> Belum ada data gardu yang tersedia.
                </div>
                </td>
            </tr>
            @endforelse
        </tbody>
      </table>

      {{-- PAGINATION --}}
      <div class="d-flex justify-content-center">
        {{ $gardus->onEachSide(2)->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert konfirmasi hapus (butuh sweetalert2 di layout atau tambahkan CDN di bawah) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  // tooltip bootstrap (jika bootstrap js aktif di layout)
  if (window.$ && $.fn.tooltip) {
    $('[data-toggle="tooltip"]').tooltip();
  }

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const form = this.closest('.delete-form');
      if (window.Swal) {
        Swal.fire({
          title: "Apakah Anda yakin?",
          text: "Data yang dihapus tidak dapat dikembalikan!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Ya, hapus!"
        }).then((res) => { if (res.isConfirmed) form.submit(); });
      } else {
        // fallback default confirm
        if (confirm('Yakin hapus data ini?')) form.submit();
      }
    });
  });
});
</script>

{{-- CDN SweetAlert2 (aktifkan jika layout belum memuat) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
@endsection
