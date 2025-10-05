@extends('layouts.pln.adminlte.app')

@section('title', 'Historis Data Gardu (Rekap per KD Gardu)')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Historis Data Gardu</h1>

    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Flash --}}
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card card-primary">
      <div class="card-body">
        <form method="GET" action="{{ route('gardu.history.index') }}" class="form-inline w-100">
          <div class="form-group mr-2">
            <label for="q" class="mr-2">Cari KD Gardu</label>
            <input type="text" id="q" name="q" class="form-control" placeholder="contoh: 21" value="{{ request('q') }}">
          </div>
          <div class="form-group mr-2">
            <label for="per_page" class="mr-2">Tampil</label>
            <select id="per_page" name="per_page" class="form-control">
              @foreach([10,20,30,50,100] as $pp)
                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp ? 'selected' : '' }}>{{ $pp }}</option>
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
              <th>Gardu Induk</th>
              <th>Alamat</th>
              <th>Total Update</th>
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
              // Parse snapshot terakhir (jaga-jaga kalau string JSON)
              $snap = [];
              try {
                $snap = is_array($r->last_snapshot) ? $r->last_snapshot : json_decode($r->last_snapshot ?? '{}', true);
                $snap = $snap ?: [];
              } catch (\Throwable $e) {
                $snap = [];
              }

              // Nilai yang ditampilkan: prioritaskan data "now", fallback ke snapshot
              $kdNow   = $r->kd_gardu_now ?? ($snap['kd_gardu'] ?? '-');
              $pylg    = $r->kd_pylg_now  ?? ($snap['kd_pylg'] ?? ($snap['penyulang'] ?? '-'));
              $gi      = $r->gi_now       ?? ($snap['gardu_induk'] ?? '-');
              $alamat  = $r->alamat_now   ?? ($snap['alamat'] ?? '-');

              $lastAt  = $r->last_at ? \Carbon\Carbon::parse($r->last_at)->format('Y-m-d H:i:s') : '-';
              $badge   = $r->last_aksi === 'create' ? 'success' : ($r->last_aksi === 'update' ? 'warning' : 'danger');
            @endphp

            <tr>
              <td><span class="badge badge-info" style="font-size:100%">{{ $kdNow }}</span></td>
              <td>{{ $pylg }}</td>
              <td>{{ $gi }}</td>
              <td class="text-truncate" style="max-width:260px">{{ $alamat }}</td>
              <td><span class="badge badge-secondary">{{ $r->total_logs }}</span></td>
              <td>{{ $lastAt }}</td>
              <td><span class="badge badge-{{ $badge }}">{{ $r->last_aksi }}</span></td>
              <td>{{ $r->last_by ?? '-' }}</td>
              <td>{{ $r->last_notes ?? '-' }}</td>
              <td>
                @if(!empty($r->id_data_gardu))
                  <a class="btn btn-sm btn-primary"
                     href="{{ route('gardu.history.show', ['id' => $r->id_data_gardu]) }}">
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

      {{-- Pagination --}}
      @if (method_exists($recaps ?? null, 'hasPages') && $recaps->hasPages())
        <div class="card-footer clearfix">
          {{ $recaps->appends(['q'=>request('q'),'per_page'=>request('per_page',20)])->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
