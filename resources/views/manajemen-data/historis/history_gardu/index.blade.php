@extends('layouts.pln.adminlte.app')

@section('title', 'Historis Data Gardu')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Historis Data Gardu</h1>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Flash message --}}
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter & PerPage --}}
    <div class="card card-primary">
      <div class="card-body">
        <form method="GET" action="{{ route('gardu.history.index') }}" class="form-inline">
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

          <a href="{{ route('gardu.index') }}" class="btn btn-outline-secondary ml-auto">
            Kembali ke Data Gardu
          </a>
        </form>
      </div>
    </div>

    {{-- Tabel History --}}
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Waktu Riwayat</th>
              <th>Aksi</th>
              <th>Diubah Oleh</th>
              <th>Keterangan</th>
              <th>ID Data Gardu</th>
              <th>KD Gardu (snap)</th>
              <th>Gardu Induk (snap)</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
          @php
            $offset = method_exists($histories ?? null, 'firstItem') ? $histories->firstItem() : 0;
          @endphp

          @forelse($histories as $i => $h)
            @php
              // Parse data_lama (snapshot)
              $old = [];
              try {
                $old = is_array($h->data_lama) ? $h->data_lama : json_decode($h->data_lama ?? '{}', true);
                $old = $old ?: [];
              } catch (\Throwable $e) {
                $old = [];
              }

              $rowId   = 'histRow'.$h->id;
              $rawId   = 'rawJson'.$h->id;
              $kd      = $old['kd_gardu']       ?? '-';
              $gi      = $old['gardu_induk']    ?? '-';
              $wktHist = optional($h->created_at)->format('Y-m-d H:i:s') ?? '-';

              $badge = $h->aksi === 'create' ? 'success' : ($h->aksi === 'update' ? 'warning' : 'danger');
            @endphp

            <tr>
              <td>{{ $offset + $i + 1 }}</td>
              <td>{{ $wktHist }}</td>
              <td><span class="badge badge-{{ $badge }}">{{ $h->aksi }}</span></td>
              <td>{{ $h->diubah_oleh }}</td>
              <td>{{ $h->keterangan ?? '-' }}</td>
              <td>{{ $h->id_data_gardu ?? '-' }}</td>
              <td><span class="badge badge-info">{{ $kd }}</span></td>
              <td>{{ $gi }}</td>
              <td>
                <button class="btn btn-sm btn-primary" type="button"
                        data-toggle="collapse" data-target="#{{ $rowId }}"
                        aria-expanded="false" aria-controls="{{ $rowId }}">
                  Lihat Detail
                </button>
              </td>
            </tr>

            {{-- DETAIL COLLAPSE --}}
            <tr class="collapse" id="{{ $rowId }}">
              <td colspan="9">
                <div class="p-2 border rounded bg-light">

                  {{-- Snapshot heading --}}
                  <div class="row">
                    <div class="col-12">
                      <strong>Snapshot dari <code>data_lama</code></strong>
                    </div>
                  </div>

                  {{-- Identitas & Meta --}}
                  <div class="row mt-2">
                    <div class="col-md-3">id (snap): <strong>{{ $old['id'] ?? '-' }}</strong></div>
                    <div class="col-md-3">kd_gardu: <strong>{{ $kd }}</strong></div>
                    <div class="col-md-3">gardu_induk: <strong>{{ $gi }}</strong></div>
                    <div class="col-md-3">penyulang/kd_pylg: <strong>{{ $old['kd_pylg'] ?? ($old['penyulang'] ?? '-') }}</strong></div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-md-3">kd_trf_gi: <strong>{{ $old['kd_trf_gi'] ?? '-' }}</strong></div>
                    <div class="col-md-9">alamat: <strong>{{ $old['alamat'] ?? '-' }}</strong></div>
                  </div>

                  {{-- Spesifikasi Trafo --}}
                  <div class="row mt-3">
                    <div class="col-12"><u>Spesifikasi Trafo (snap)</u></div>
                    <div class="col-md-3">daya_trafo: <strong>{{ $old['daya_trafo'] ?? '-' }}</strong></div>
                    <div class="col-md-3">merek_trafo: <strong>{{ $old['merek_trafo'] ?? '-' }}</strong></div>
                    <div class="col-md-3">no_seri: <strong>{{ $old['no_seri'] ?? '-' }}</strong></div>
                    <div class="col-md-3">tahun: <strong>{{ $old['tahun'] ?? '-' }}</strong></div>
                  </div>

                  {{-- Beban --}}
                  <div class="row mt-2">
                    <div class="col-12"><u>Beban (snap)</u></div>
                    <div class="col-md-3">beban_kva_trafo: <strong>{{ $old['beban_kva_trafo'] ?? '-' }}</strong></div>
                    <div class="col-md-3">persentase_beban: <strong>{{ $old['persentase_beban'] ?? '-' }}</strong></div>
                  </div>

                  {{-- created_at & updated_at snapshot --}}
                  <div class="row mt-3">
                    <div class="col-md-4">created_at (snap): <strong>{{ $old['created_at'] ?? '-' }}</strong></div>
                    <div class="col-md-4">updated_at (snap): <strong>{{ $old['updated_at'] ?? '-' }}</strong></div>
                    <div class="col-md-4">aksi (log): <strong>{{ $h->aksi }}</strong></div>
                  </div>

                  {{-- Raw JSON toggle --}}
                  <div class="row mt-3">
                    <div class="col-12">
                      <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#{{ $rawId }}">
                        Lihat Raw JSON
                      </button>
                      <div id="{{ $rawId }}" class="collapse mt-2">
                        <pre class="mb-0" style="white-space:pre-wrap">{{ is_string($h->data_lama) ? $h->data_lama : json_encode($h->data_lama, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center">Belum ada riwayat</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if (method_exists($histories ?? null, 'hasPages') && $histories->hasPages())
        <div class="card-footer clearfix">
          {{ $histories->appends(['q'=>request('q'),'per_page'=>request('per_page',20)])->links() }}
        </div>
      @endif
    </div>

  </div>
</section>
@endsection
