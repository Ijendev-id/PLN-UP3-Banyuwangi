@extends('layouts.pln.adminlte.app')

@section('title', 'Riwayat Pengukuran')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0">
          Riwayat Pengukuran
          @if($omt)
            <small class="text-muted">— KD: <span class="badge badge-info" style="font-size:100%">{{ $omt->kd_gardu }}</span></small>
          @else
            <small class="text-muted">— ID OMT: {{ $idOmt }}</small>
          @endif
        </h1>
      </div>
      <div class="col-sm-4 text-right">
        <a href="{{ route('omt-pengukuran.history.index') }}" class="btn btn-outline-secondary">← Kembali ke Rekap</a>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Info OMT sekarang --}}
    <div class="card">
      <div class="card-body">
        @if($omt)
          <div class="row">
            <div class="col-md-3"><strong>KD Gardu</strong>: {{ $omt->kd_gardu }}</div>
            <div class="col-md-3"><strong>Waktu Pengukuran Terakhir</strong>: {{ $omt->updated_at }}</div>

          </div>
        @else
          <em>Data OMT saat ini tidak ditemukan. Menampilkan riwayat dari log.</em>
        @endif
      </div>
    </div>

    {{-- Tabel riwayat --}}
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width:180px;">Waktu Log</th>
              <th style="width:120px;">Aksi</th>
              <th>Diubah Oleh</th>
              <th>Keterangan</th>
              <th style="width:160px;">Snapshot</th>
            </tr>
          </thead>
          <tbody>
            @php
              // field penting utk perbandingan
              $fields = [
                'kd_gardu' => 'KD Gardu',
                'waktu_pengukuran' => 'Waktu Pengukuran',
                // contoh subset: boleh tambah semua field arus/tegangan sesuai kebutuhan
                'ian' => 'IAN', 'iar' => 'IAR', 'ias' => 'IAS', 'iat' => 'IAT',
                'ibn' => 'IBN', 'ibr' => 'IBR', 'ibs' => 'IBS', 'ibt' => 'IBT',
                'icn' => 'ICN', 'icr' => 'ICR', 'ics' => 'ICS', 'ict' => 'ICT',
                'idn' => 'IDN', 'idr' => 'IDR', 'ids' => 'IDS', 'idt' => 'IDT',
                'vrn' => 'VRN', 'vrs' => 'VRS', 'vsn' => 'VSN',
                'vst' => 'VST', 'vtn' => 'VTN', 'vtr' => 'VTR',
                'iun' => 'IUN', 'iur' => 'IUR', 'ius' => 'IUS', 'iut' => 'IUT',
                'beban_kva_trafo' => 'Beban KVA Trafo',
                'persentase_beban' => 'Persentase Beban',
              ];
            @endphp

            @forelse($histories as $h)
              @php
                $wkt   = optional($h->created_at)->format('Y-m-d H:i:s') ?? '-';
                $badge = $h->aksi === 'create' ? 'success' : ($h->aksi === 'update' ? 'warning' : 'danger');
                $rowId = 'snapRow'.$h->id;

                $old = [];
                try { $old = json_decode($h->data_lama ?? '{}', true) ?: []; } catch(\Throwable $e) { $old = []; }

                // data_baru (kalau kamu simpan; pada controller update kamu SUDAH menyimpan data_baru)
                $new = [];
                try { $new = json_decode($h->data_baru ?? '{}', true) ?: []; } catch(\Throwable $e) { $new = []; }

                // fallback: kalau data_baru kosong, bandingkan ke kondisi OMT saat ini
                $fallbackToCurrent = empty($new) && !empty($omt);
              @endphp

              {{-- ROW UTAMA --}}
              <tr>
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

              {{-- ROW TAMBAHAN: full width snapshot & compare --}}
              <tr id="{{ $rowId }}" class="collapse">
                <td colspan="5">
                  <div class="p-3 border rounded bg-light">

                    {{-- meta ringkas --}}
                    <div class="row">

                    </div>

                    {{-- perbandingan --}}
                    <div class="mt-3 table-responsive">
                      <div class="alert alert-info mb-2">
                          Perbandingan <strong> Data Lama </strong> vs <strong>Data Baru </strong>
                        </div>


                      <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                          <tr>
                            <th style="width: 220px;">Field</th>
                            <th>Data Lama (Snapshot)</th>
                            <th>Data Baru</th>
                            <th style="width: 120px;">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($fields as $key => $label)
                            @php
                              $oldVal = $old[$key] ?? null;

                              if (!empty($new)) {
                                $newVal = $new[$key] ?? null;
                              } elseif ($fallbackToCurrent) {
                                $newVal = $omt->{$key} ?? null;
                              } else {
                                $newVal = null;
                              }

                              $oldStr = is_string($oldVal) ? trim($oldVal) : (is_null($oldVal) ? '' : (string)$oldVal);
                              $newStr = is_string($newVal) ? trim($newVal) : (is_null($newVal) ? '' : (string)$newVal);
                              $changed = ($newStr !== '' || $oldStr !== '') ? ($oldStr !== $newStr) : false;
                            @endphp
                            <tr @if($changed) class="table-warning" @endif>
                              <td><strong>{{ $label }}</strong> <code class="text-muted d-none d-md-inline">{{ $key }}</code></td>
                              <td>{{ $oldStr === '' ? '—' : $oldStr }}</td>
                              <td>
                                @if($newStr === '')
                                  —
                                @else
                                  @if($changed)
                                    <span class="font-weight-bold">{{ $newStr }}</span>
                                  @else
                                    {{ $newStr }}
                                  @endif
                                @endif
                              </td>
                              <td>
                                @if($newStr === '' && $oldStr === '')
                                  <span class="badge badge-secondary">n/a</span>
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

                    {{-- Raw JSON --}}
                    <div class="mt-2">
                      <button class="btn btn-xs btn-outline-dark" type="button" data-toggle="collapse" data-target="#raw{{ $h->id }}">
                        Lihat Raw JSON
                      </button>
                      <div id="raw{{ $h->id }}" class="collapse mt-2">
                        <div class="row">
                          <div class="col-md-6">
                            <h6>data_lama</h6>
                            <pre class="mb-3" style="white-space:pre-wrap">{{ is_string($h->data_lama) ? $h->data_lama : json_encode($h->data_lama, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                          </div>
                          <div class="col-md-6">
                            <h6>data_baru</h6>
                            <pre class="mb-0" style="white-space:pre-wrap">{{ is_string($h->data_baru ?? null) ? $h->data_baru : json_encode($h->data_baru ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Belum ada riwayat untuk OMT ini</td>
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
