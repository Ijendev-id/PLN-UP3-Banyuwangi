@extends('layouts.pln.adminlte.app')

@section('title', 'Riwayat Perubahan Gardu')

@section('content')
@php
  // pastikan Carbon pakai locale Indonesia
  \Carbon\Carbon::setLocale(app()->getLocale() ?? 'id');
@endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0">
          Riwayat Perubahan Gardu
          @if($gardu)
            <small class="text-muted">— KD: <span class="badge badge-info" style="font-size:100%">{{ $gardu->kd_gardu }}</span></small>
          @else
            <small class="text-muted">— ID: {{ $idDataGardu }}</small>
          @endif
        </h1>
      </div>
      <div class="col-sm-4 text-right">
        <a href="{{ route('gardu.history.index') }}" class="btn btn-outline-secondary">← Kembali ke Rekap</a>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Info gardu sekarang --}}
    <div class="card">
      <div class="card-body">
        @php
  // Ambil log terakhir (pastikan $histories sudah di-order DESC by created_at di controller)
  $lastHist = (method_exists($histories ?? null, 'first')) ? $histories->first() : null;

  // Format sama seperti di rekap (Y-m-d H:i:s), ambil dari log terakhir; fallback ke updated_at gardu
  $lastAt = $lastHist
    ? \Carbon\Carbon::parse($lastHist->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s')
    : ($gardu && $gardu->updated_at
        ? \Carbon\Carbon::parse($gardu->updated_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s')
        : '—');

  $lastLabel = $lastHist ? 'diambil dari log terakhir' : 'diambil dari data saat ini';
@endphp

@if($gardu)
  <div class="row">
    <div class="col-md-3"><strong>KD Gardu</strong>: {{ $gardu->kd_gardu }}</div>
    <div class="col-md-5">
      <strong>Waktu Pengukuran Terakhir</strong>: {{ $lastAt }}
      <span class="text-muted small">({{ $lastLabel }})</span>
    </div>
  </div>
@else
  <em>Data gardu saat ini tidak ditemukan. Menampilkan riwayat dari log.</em>
@endif
      </div>
    </div>

    {{-- Tabel riwayat --}}
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width:60px;">#</th>
              <th style="width:180px;">Waktu</th>
              <th style="width:120px;">Aksi</th>
              <th>Diubah Oleh</th>
              <th>Keterangan</th>
              <th style="width:160px;">Snapshot</th>
            </tr>
          </thead>
          <tbody>
            @php
              $offset = method_exists($histories ?? null, 'firstItem') ? $histories->firstItem() : 0;

              // daftar field yang ditampilkan & dibandingkan
              $fields = [
                'kd_gardu' => 'KD Gardu',
                'kd_pylg' => 'Penyulang',
                'gardu_induk' => 'Gardu Induk',
                'alamat' => 'Alamat',
                'desa' => 'Desa',
                'kd_trf_gi' => 'KD TRF GI',
                'daya_trafo' => 'Daya Trafo',
                'merek_trafo' => 'Merek Trafo',
                'no_seri' => 'No Seri',
                'tahun' => 'Tahun',
                'beban_kva_trafo' => 'Beban KVA',
                'persentase_beban' => 'Persentase Beban',
                'hubungan' => 'Hubungan',
                'impedansi' => 'Impedansi',
                'tegangan_tm' => 'Tegangan TM',
                'tegangan_tr' => 'Tegangan TR',
                'frekuensi' => 'Frekuensi',
                'section_lbs' => 'Section LBS',
                'fasa' => 'Fasa',
                'nilai_sdk_utama' => 'Nilai SDK Utama',
                'nilai_primer' => 'Nilai Primer',
                'tap_no' => 'Tap No',
                'tap_kv' => 'Tap KV',
                'rekondisi_preman' => 'Rekondisi/Preman',
                'bengkel' => 'Bengkel',

                // field trafo 2 & 3 bila ada di skema
                'merek_trafo_2' => 'Merek Trafo 2',
                'no_seri_2' => 'No Seri 2',
                'tahun_2' => 'Tahun 2',
                'berat_total_2' => 'Berat Total 2',
                'berat_minyak_2' => 'Berat Minyak 2',
                'merek_trafo_3' => 'Merek Trafo 3',
                'no_seri_3' => 'No Seri 3',
                'tahun_3' => 'Tahun 3',
                'berat_total_3' => 'Berat Total 3',
                'berat_minyak_3' => 'Berat Minyak 3',
              ];
            @endphp

            @forelse($histories as $i => $h)
              @php
                // Waktu baris (format Indonesia)
                $wkt = $h->created_at
                  ? \Carbon\Carbon::parse($h->created_at)
                      ->timezone(config('app.timezone'))
                      ->translatedFormat('d F Y H:i')
                  : '-';

                $badge = $h->aksi === 'create' ? 'success' : ($h->aksi === 'update' ? 'warning' : 'danger');
                $rowId = 'snapRow'.$h->id;

                // snapshot data_lama
                $snap = [];
                try {
                  $snap = is_array($h->data_lama) ? $h->data_lama : json_decode($h->data_lama ?? '{}', true);
                  $snap = $snap ?: [];
                } catch(\Throwable $e) { $snap = []; }
              @endphp

              {{-- ROW UTAMA --}}
              <tr>
                <td>{{ $offset + $i }}</td>
                <td>{{ $wkt }}</td>
                <td><span class="badge badge-{{ $badge }}">{{ $h->aksi }}</span></td>
                <td>{{ $h->diubah_oleh ?? '-' }}</td>
                <td>{{ $h->keterangan ?? '-' }}</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary" type="button"
                          data-toggle="collapse" data-target="#{{ $rowId }}">
                    Lihat Snapshot
                  </button>
                </td>
              </tr>

              {{-- ROW TAMBAHAN: FULL-WIDTH SNAPSHOT & PERBANDINGAN --}}
              <tr id="{{ $rowId }}" class="collapse">
                <td colspan="6">
                  <div class="p-3 border rounded bg-light">
                    <div class="mt-3 table-responsive">
                      <div class="alert alert-info mb-2">
                        Perbandingan <strong>Data Lama</strong> vs <strong>Data Baru</strong>
                      </div>
                      @if(!$gardu)
                        <div class="alert alert-warning mb-2">
                          Data saat ini tidak tersedia sehingga kolom <strong>Data Baru</strong> tidak bisa dibandingkan.
                        </div>
                      @endif

                      <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                          <tr>
                            <th style="width: 220px;">Field</th>
                            <th>Data Lama (Snapshot)</th>
                            <th>Data Baru (Saat Ini)</th>
                            <th style="width: 120px;">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($fields as $key => $label)
                            @php
                              $old = $snap[$key] ?? null;
                              $new = $gardu ? ($gardu->{$key} ?? null) : null;

                              // rapikan whitespace string
                              $oldStr = is_string($old) ? trim($old) : (is_null($old) ? '' : (string)$old);
                              $newStr = is_string($new) ? trim($new) : (is_null($new) ? '' : (string)$new);

                              $changed = ($gardu) ? ($oldStr !== $newStr) : false;
                            @endphp
                            <tr @if($changed) class="table-warning" @endif>
                              <td><strong>{{ $label }}</strong> <code class="text-muted d-none d-md-inline">{{ $key }}</code></td>
                              <td>{{ $oldStr === '' ? '—' : $oldStr }}</td>
                              <td>
                                @if(!$gardu)
                                  —
                                @else
                                  @if($changed)
                                    <span class="font-weight-bold">{{ $newStr === '' ? '—' : $newStr }}</span>
                                  @else
                                    {{ $newStr === '' ? '—' : $newStr }}
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if(!$gardu)
                                  <span class="badge badge-secondary">unknown</span>
                                @else
                                  @if($changed)
                                    <span class="badge badge-danger">berubah</span>
                                  @else
                                    <span class="badge badge-success">sama</span>
                                  @endif
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>

                    {{-- Tombol lihat raw JSON --}}
                    <div class="mt-2">
                      <button class="btn btn-xs btn-outline-dark" type="button" data-toggle="collapse" data-target="#raw{{ $h->id }}">
                        Lihat Raw JSON
                      </button>
                      <div id="raw{{ $h->id }}" class="collapse mt-2">
                        <pre class="mb-0" style="white-space:pre-wrap">{{ is_string($h->data_lama) ? $h->data_lama : json_encode($h->data_lama, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                      </div>
                    </div>

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada riwayat untuk gardu ini</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if (method_exists($histories ?? null, 'hasPages') && $histories->hasPages())
        <div class="card-footer clearfix">
          {{ $histories->appends(['per_page'=>request('per_page',20)])->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
