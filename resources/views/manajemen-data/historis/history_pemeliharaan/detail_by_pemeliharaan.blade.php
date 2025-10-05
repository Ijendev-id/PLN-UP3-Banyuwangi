@extends('layouts.pln.adminlte.app')

@section('title', 'Riwayat Pemeliharaan')

@section('content')
@php
  \Carbon\Carbon::setLocale(app()->getLocale() ?? 'id');
@endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0">
          Riwayat Pemeliharaan
          @if($pemeliharaan)
            <small class="text-muted">— KD: <span class="badge badge-info" style="font-size:100%">{{ $pemeliharaan->kd_gardu }}</span></small>
          @else
            <small class="text-muted">— ID Pemeliharaan: {{ $idPemeliharaan }}</small>
          @endif
        </h1>
      </div>
      <div class="col-sm-4 text-right">
        <a href="{{ route('pemeliharaan.history.index') }}" class="btn btn-outline-secondary">← Kembali ke Rekap</a>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Info saat ini --}}
    <div class="card">
      <div class="card-body">
        @php
          // Ambil log terakhir pada halaman ini (pastikan controller orderByDesc('created_at'))
          $lastHist = (method_exists($histories ?? null, 'first')) ? $histories->first() : null;

          // Format sama seperti rekap: Y-m-d H:i:s
          $lastAt = $lastHist
            ? \Carbon\Carbon::parse($lastHist->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s')
            : ($pemeliharaan && $pemeliharaan->updated_at
                ? \Carbon\Carbon::parse($pemeliharaan->updated_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s')
                : '—');

          $lastLabel = $lastHist ? 'diambil dari log terakhir' : 'diambil dari data saat ini';
        @endphp

        @if($pemeliharaan)
          <div class="row">
            <div class="col-md-3"><strong>KD Gardu</strong>: {{ $pemeliharaan->kd_gardu }}</div>
            <div class="col-md-5">
              <strong>Waktu Pemeliharaan Terakhir</strong>: {{ $lastAt }}
              <span class="text-muted small">({{ $lastLabel }})</span>
            </div>
          </div>
        @else
          <em>Data pemeliharaan saat ini tidak ditemukan. Menampilkan riwayat dari log.</em>
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
              // Daftar field untuk dibandingkan (label mengikuti rules yang kamu kirim)
              $fields = [
                'waktu_pemeliharaan' => 'Waktu Pemeliharaan',
                'kd_gardu' => 'KD Gardu',
                'sutm_mm' => 'SUTM mm',
                'jumper_sutm_out_fasa_r' => 'Jumper SUTM OUT R',
                'jumper_sutm_out_fasa_s' => 'Jumper SUTM OUT S',
                'jumper_sutm_out_fasa_t' => 'Jumper SUTM OUT T',
                'cond_sutm_co_fasa_r' => 'Cond SUTM CO R',
                'cond_sutm_co_fasa_s' => 'Cond SUTM CO S',
                'cond_sutm_co_fasa_t' => 'Cond SUTM CO T',
                'jumper_sutm_co_income_fasa_r' => 'Jumper SUTM CO Income R',
                'jumper_sutm_co_income_fasa_s' => 'Jumper SUTM CO Income S',
                'jumper_sutm_co_income_fasa_t' => 'Jumper SUTM CO Income T',

                'fuse_link_fasa_r' => 'Fuse Link R',
                'fuse_link_fasa_s' => 'Fuse Link S',
                'fuse_link_fasa_t' => 'Fuse Link T',
                'keramik_polimer' => 'Keramik/Polimer',

                'jumper_co_trafo_primer_out_fasa_r' => 'Jumper CO Trafo Primer OUT R',
                'jumper_co_trafo_primer_out_fasa_s' => 'Jumper CO Trafo Primer OUT S',
                'jumper_co_trafo_primer_out_fasa_t' => 'Jumper CO Trafo Primer OUT T',
                'cond_co_trafo_bush_primer_fasa_r' => 'Cond CO Trafo Bush Primer R',
                'cond_co_trafo_bush_primer_fasa_s' => 'Cond CO Trafo Bush Primer S',
                'cond_co_trafo_bush_primer_fasa_t' => 'Cond CO Trafo Bush Primer T',
                'jumper_co_bush_trafo_primer_income_fasa_r' => 'Jumper CO Bush Primer Income R',
                'jumper_co_bush_trafo_primer_income_fasa_s' => 'Jumper CO Bush Primer Income S',
                'jumper_co_bush_trafo_primer_income_fasa_t' => 'Jumper CO Bush Primer Income T',

                'jumper_bush_primer_out_arester_fasa_r' => 'Jumper Bush Primer → Arester R',
                'jumper_bush_primer_out_arester_fasa_s' => 'Jumper Bush Primer → Arester S',
                'jumper_bush_primer_out_arester_fasa_t' => 'Jumper Bush Primer → Arester T',
                'cond_bush_primer_arester_fasa_r' => 'Cond Bush Primer → Arester R',
                'cond_bush_primer_arester_fasa_s' => 'Cond Bush Primer → Arester S',
                'cond_bush_primer_arester_fasa_t' => 'Cond Bush Primer → Arester T',
                'jumper_bush_primer_income_arester_fasa_r' => 'Jumper Bush Primer Income → Arester R',
                'jumper_bush_primer_income_arester_fasa_s' => 'Jumper Bush Primer Income → Arester S',
                'jumper_bush_primer_income_arester_fasa_t' => 'Jumper Bush Primer Income → Arester T',

                'arester_fasa_r' => 'Arester Fasa R',
                'arester_fasa_s' => 'Arester Fasa S',
                'arester_fasa_t' => 'Arester Fasa T',
                'keramik_polimer_lighting_arester' => 'Keramik/Polimer Lighting Arester',
                'jumper_dudukan_arester_fasa_r' => 'Jumper Dudukan Arester R',
                'jumper_dudukan_arester_fasa_s' => 'Jumper Dudukan Arester S',
                'jumper_dudukan_arester_fasa_t' => 'Jumper Dudukan Arester T',
                'cond_dudukan_la' => 'Cond Dudukan LA',
                'jumper_body_trf_la' => 'Jumper Body TRF ↔ LA',
                'cond_body_trf_la' => 'Cond Body TRF ↔ LA',
                'jumper_cond_la_dg_body_trf' => 'Jumper Cond LA ↔ Body TRF',
                'cond_ground_la_panel' => 'Cond Ground LA Panel',

                'isolasi_fasa_r' => 'Isolasi R',
                'isolasi_fasa_s' => 'Isolasi S',
                'isolasi_fasa_t' => 'Isolasi T',
                'arus_bocor' => 'Arus Bocor',

                'jumper_trf_bush_skunder_4x_panel' => 'Jumper TRF Bush Skunder → Panel',
                'cond_out_trf_panel' => 'Cond OUT TRF → Panel',
                'tahanan_isolasi_pp' => 'Tahanan Isolasi P-P',
                'tahanan_isolasi_pg' => 'Tahanan Isolasi P-G',

                'jumper_in_panel_saklar' => 'Jumper IN Panel Saklar',
                'jumper_in_nol' => 'Jumper IN Nol',
                'jumper_nol_ground' => 'Jumper Nol ↔ Ground',
                'jenis_saklar_utama' => 'Jenis Saklar Utama',
                'jumper_dr_saklar_out' => 'Jumper dari Saklar → OUT',
                'jenis_cond_dr_saklar_nh_utama' => 'Jenis Cond dari Saklar NH Utama',

                'data_proteksi_utama_fasa_r' => 'Data Proteksi Utama R',
                'data_proteksi_utama_fasa_s' => 'Data Proteksi Utama S',
                'data_proteksi_utama_fasa_t' => 'Data Proteksi Utama T',

                'jenis_cond_dr_nh_utama_jurusan' => 'Jenis Cond dari NH Utama → Jurusan',
                'jumper_dr_nh_jurusan_in' => 'Jumper dari NH Jurusan → IN',

                'data_proteksi_line_a_fasa_r' => 'Proteksi Line A R',
                'data_proteksi_line_a_fasa_s' => 'Proteksi Line A S',
                'data_proteksi_line_a_fasa_t' => 'Proteksi Line A T',
                'data_proteksi_line_b_fasa_r' => 'Proteksi Line B R',
                'data_proteksi_line_b_fasa_s' => 'Proteksi Line B S',
                'data_proteksi_line_b_fasa_t' => 'Proteksi Line B T',
                'data_proteksi_line_c_fasa_r' => 'Proteksi Line C R',
                'data_proteksi_line_c_fasa_s' => 'Proteksi Line C S',
                'data_proteksi_line_c_fasa_t' => 'Proteksi Line C T',
                'data_proteksi_line_d_fasa_r' => 'Proteksi Line D R',
                'data_proteksi_line_d_fasa_s' => 'Proteksi Line D S',
                'data_proteksi_line_d_fasa_t' => 'Proteksi Line D T',

                'jumper_out_dr_nh_jurusan_cond_out_jtr' => 'Jumper OUT dari NH Jurusan → Cond OUT JTR',
                'cond_dr_nh_jurusan_out_jtr_line_a' => 'Cond dari NH Jurusan OUT → JTR Line A',
                'cond_dr_nh_jurusan_out_jtr_line_b' => 'Cond dari NH Jurusan OUT → JTR Line B',
                'cond_dr_nh_jurusan_out_jtr_line_c' => 'Cond dari NH Jurusan OUT → JTR Line C',
                'cond_dr_nh_jurusan_out_jtr_line_d' => 'Cond dari NH Jurusan OUT → JTR Line D',
                'cond_jurusan_jtr_line_a' => 'Cond Jurusan JTR Line A',
                'cond_jurusan_jtr_line_b' => 'Cond Jurusan JTR Line B',
                'cond_jurusan_jtr_line_c' => 'Cond Jurusan JTR Line C',
                'cond_jurusan_jtr_line_d' => 'Cond Jurusan JTR Line D',

                'jumper_la_body_panel' => 'Jumper LA ↔ Body Panel',
                'cond_dr_ground_la_body' => 'Cond dari Ground LA ↔ Body',
                'cond_dr_nol_ground' => 'Cond dari Nol ↔ Ground',
                'cond_dr_kopel_body_dg_la_ground' => 'Cond Kopel Body ↔ LA Ground',
                'nilai_r_tanah_nol' => 'Nilai R Tanah Nol',
                'nilai_r_tanah_la' => 'Nilai R Tanah LA',

                'panel_gtt_pintu' => 'Panel GTT - Pintu',
                'panel_gtt_kunci' => 'Panel GTT - Kunci',
                'panel_gtt_no_gtt' => 'Panel GTT - No GTT',
                'panel_gtt_kondisi' => 'Panel GTT - Kondisi',
                'panel_gtt_lubang_pipa' => 'Panel GTT - Lubang Pipa',
                'panel_gtt_pondasi' => 'Panel GTT - Pondasi',
                'panel_gtt_tanda_peringatan' => 'Panel GTT - Tanda Peringatan',
                'panel_gtt_jenis_gardu' => 'Panel GTT - Jenis Gardu',
                'panel_gtt_tgl_inspeksi' => 'Panel GTT - Tgl Inspeksi',
                'panel_gtt_insp_siang' => 'Panel GTT - Insp Siang',
                'panel_gtt_pekerjaan_pemeliharaan' => 'Panel GTT - Pekerjaan',
                'panel_gtt_catatan' => 'Panel GTT - Catatan',

                'tahan_isolasi_trafo_1_pb' => 'Tahanan Isolasi Trafo 1 P-B',
                'tahan_isolasi_trafo_1_sb' => 'Tahanan Isolasi Trafo 1 S-B',
                'tahan_isolasi_trafo_1_ps' => 'Tahanan Isolasi Trafo 1 P-S',
                'tahan_isolasi_trafo_2_pb' => 'Tahanan Isolasi Trafo 2 P-B',
                'tahan_isolasi_trafo_2_sb' => 'Tahanan Isolasi Trafo 2 S-B',
                'tahan_isolasi_trafo_2_ps' => 'Tahanan Isolasi Trafo 2 P-S',
                'tahan_isolasi_trafo_3_pb' => 'Tahanan Isolasi Trafo 3 P-B',
                'tahan_isolasi_trafo_3_sb' => 'Tahanan Isolasi Trafo 3 S-B',
                'tahan_isolasi_trafo_3_ps' => 'Tahanan Isolasi Trafo 3 P-S',
              ];
            @endphp

            @forelse($histories as $h)
              @php
                $wkt   = $h->created_at ? \Carbon\Carbon::parse($h->created_at)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') : '-';
                $badge = $h->aksi === 'create' ? 'success' : ($h->aksi === 'update' ? 'warning' : 'danger');
                $rowId = 'snapRow'.$h->id;

                // data_lama
                $old = [];
                try { $old = json_decode($h->data_lama ?? '{}', true) ?: []; } catch(\Throwable $e) { $old = []; }

                // data_baru (jika disimpan di history)
                $new = [];
                try { $new = json_decode($h->data_baru ?? '{}', true) ?: []; } catch(\Throwable $e) { $new = []; }

                // fallback: jika data_baru kosong, bandingkan ke kondisi record pemeliharaan saat ini
                $fallbackToCurrent = empty($new) && !empty($pemeliharaan);
              @endphp

              {{-- Baris utama --}}
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

              {{-- Baris snapshot & perbandingan --}}
              <tr id="{{ $rowId }}" class="collapse">
                <td colspan="5">
                  <div class="p-3 border rounded bg-light">
                    <div class="alert alert-info mb-2">
                      Perbandingan <strong>Data Lama</strong> vs <strong>Data Baru</strong>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                          <tr>
                            <th style="width: 260px;">Field</th>
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
                                $newVal = $pemeliharaan->{$key} ?? null;
                              } else {
                                $newVal = null;
                              }

                              // Normalisasi jadi string untuk pembanding yang konsisten
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
                <td colspan="5" class="text-center">Belum ada riwayat untuk pemeliharaan ini</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if (method_exists($histories ?? null, 'hasPages') && $histories->hasPages())
        <div class="card-footer clearfix">
          {{ $histories->appends(['per_page'=>request('per_page',20)])->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
