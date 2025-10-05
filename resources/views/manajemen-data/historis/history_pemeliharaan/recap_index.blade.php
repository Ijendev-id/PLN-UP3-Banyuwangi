@extends('layouts.pln.adminlte.app')

@section('title', 'Historis Pemeliharaan (Rekap per KD Gardu)')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Historis Pemeliharaan</h1>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    {{-- Filter --}}
    <div class="card card-primary">
      <div class="card-body">
        <form method="GET" action="{{ route('pemeliharaan.history.index') }}" class="form-inline w-100">
          <div class="form-group mr-2">
            <label for="q" class="mr-2">Cari KD Gardu</label>
            <input type="text" id="q" name="q" class="form-control" placeholder="contoh: 21" value="{{ request('q') }}">
          </div>
          <div class="form-group mr-2">
            <label for="per_page" class="mr-2">Tampil</label>
            <select id="per_page" name="per_page" class="form-control">
              @foreach([10,20,30,50,100] as $pp)
                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
              @endforeach
            </select>
          </div>
          <button class="btn btn-primary">Terapkan</button>
        </form>
      </div>
    </div>

    {{-- Tabel Rekap --}}
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>KD Gardu</th>
              <th>Penyulang</th>
              <th>Alamat</th>
              <th>Gardu Induk</th>
              <th>Total Log</th>
              <th>Update Terakhir</th>
              <th>Aksi Terakhir</th>
              <th>Diubah Oleh</th>
              <th>Keterangan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
          @forelse($recaps as $r)
            @php
              // snapshot terakhir (jaga-jaga kalau masih disimpan JSON string)
              $snap = [];
              try {
                $snap = is_array($r->last_snapshot) ? $r->last_snapshot : json_decode($r->last_snapshot ?? '{}', true);
                $snap = $snap ?: [];
              } catch (\Throwable $e) { $snap = []; }

              // ambil dari "now" kalau ada, fallback ke snapshot
              $kd     = $r->kd_gardu_now ?? ($snap['kd_gardu'] ?? '-');
              $pylg   = $r->kd_pylg_now  ?? ($snap['kd_pylg'] ?? ($snap['penyulang'] ?? '-'));
              $alamat = $r->alamat_now   ?? ($snap['alamat'] ?? '-');
              $gi     = $r->gi_now       ?? ($snap['gardu_induk'] ?? '-');

              // format sama dengan halaman OMT: Y-m-d H:i:s
              $lastAt = $r->last_at ? \Carbon\Carbon::parse($r->last_at)->format('Y-m-d H:i:s') : '-';

              $badge  = $r->last_aksi === 'create' ? 'success' : ($r->last_aksi === 'update' ? 'warning' : 'danger');
            @endphp

            <tr>
              <td><span class="badge badge-info" style="font-size:100%">{{ $kd }}</span></td>
              <td>{{ $pylg }}</td>
              <td class="text-truncate" style="max-width:260px">{{ $alamat }}</td>
              <td>{{ $gi }}</td>
              <td><span class="badge badge-secondary">{{ $r->total_logs }}</span></td>
              <td>{{ $lastAt }}</td>
              <td><span class="badge badge-{{ $badge }}">{{ $r->last_aksi }}</span></td>
              <td>{{ $r->last_by ?? '-' }}</td>
              <td>{{ $r->last_notes ?? '-' }}</td>
              <td>
                @if(!empty($r->id_pemeliharaan))
                  <a class="btn btn-sm btn-primary"
                     href="{{ route('pemeliharaan.history.show', $r->id_pemeliharaan) }}">
                    Lihat Riwayat
                  </a>
                @else
                  <button class="btn btn-sm btn-secondary" disabled>—</button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center">Belum ada riwayat</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if (method_exists($recaps ?? null, 'hasPages') && $recaps->hasPages())
        <div class="card-footer clearfix">
          {{ $recaps->appends(['q'=>request('q'),'per_page'=>request('per_page',20)])->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
