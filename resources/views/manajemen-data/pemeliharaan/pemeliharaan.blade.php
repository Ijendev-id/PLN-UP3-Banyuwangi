@extends('layouts.pln.adminlte.app')

@section('title', 'Input Pemeliharaan OMT')

@section('content')
<br>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card card-primary">
          <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h3 class="card-title">Form Pemeliharaan OMT</h3>
            @php $isEdit = !empty($pemeliharaan); @endphp
            <div class="d-flex align-items-center">
              @if($isEdit)
                <span class="badge badge-warning">Mode: Update</span>
                <a href="{{ route('pemeliharaan.cetak.pdf.pemeliharaan', $pemeliharaan->id) }}"
                   class="btn btn-sm btn-danger ml-2" target="_blank">
                  <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
              @else
                <span class="badge badge-success">Mode: Input Baru</span>
              @endif
            </div>
          </div>

          @php
            $formAction = $isEdit
              ? url('manajemen-data/pemeliharaan/'.$pemeliharaan->id)
              : route('pemeliharaan.store');

            $userName = auth()->user()->name ?? auth()->user()->nama_lengkap ?? '—';

            // default waktu -> seperti pengukuran (datetime-local value pakai Y-m-d\TH:i)
            $waktuDefault = old('waktu_pemeliharaan',
              isset($pemeliharaan->waktu_pemeliharaan)
                ? \Carbon\Carbon::parse($pemeliharaan->waktu_pemeliharaan)->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s')
            );

            // helper singkat ambil value lama/ditambah
            function v($name, $obj = null) {
              return old($name, $obj->{$name} ?? '');
            }
          @endphp

          <form action="{{ $formAction }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="card-body">

              {{-- ALERT ERROR (backup selain SweetAlert) --}}
              @if ($errors->any())
                <div class="alert alert-danger">
                  <strong>Periksa kembali input Anda:</strong>
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              {{-- ======================== DETAIL GARDU (HEADER) ======================== --}}
              <div class="gardu-summary p-3 p-md-4 mb-3">
                <div class="d-flex align-items-center mb-3">
                  <i class="fas fa-bolt mr-2" style="font-size:20px;"></i>
                  <h5 class="mb-0">Detail Gardu</h5>
                </div>

                <div class="row">
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Gardu Induk</div>
                    <div class="font-weight-bold">{{ $gardu->gardu_induk }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Kode Gardu</div>
                    <div class="font-weight-bold">{{ $gardu->kd_gardu }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Penyulang</div>
                    <div class="font-weight-bold">{{ $gardu->kd_pylg ?? $gardu->penyulang ?? '-' }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Trafo GI</div>
                    <div class="font-weight-bold">{{ $gardu->kd_trf_gi }}</div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <div class="text-muted small">Alamat</div>
                    <div class="font-weight-bold">{{ $gardu->alamat }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Desa</div>
                    <div class="font-weight-bold">{{ $gardu->desa ?? '-' }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Jumlah Trafo</div>
                    <div class="font-weight-bold">{{ $gardu->jml_trafo ?? 1 }}</div>
                  </div>

                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Daya Trafo (VA)</div>
                    <div class="font-weight-bold">{{ $gardu->daya_trafo }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Merek Trafo</div>
                    <div class="font-weight-bold">{{ $gardu->merek_trafo }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">No. Seri</div>
                    <div class="font-weight-bold">{{ $gardu->no_seri }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Tahun</div>
                    <div class="font-weight-bold">{{ $gardu->tahun }}</div>
                  </div>

                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Beban kVA Trafo</div>
                    <div class="font-weight-bold">{{ $gardu->beban_kva_trafo ?? '-' }}</div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <div class="text-muted small">Persentase Beban (%)</div>
                    <div class="font-weight-bold">{{ $gardu->persentase_beban ?? '-' }}</div>
                  </div>
                </div>
              </div>

              {{-- ====== HIDDEN yang harus ikut ====== --}}
              <input type="hidden" name="kd_gardu"
                     value="{{ old('kd_gardu', $pemeliharaan->kd_gardu ?? $gardu->kd_gardu) }}">
              <input type="hidden" name="diubah_oleh" value="{{ $userName }}">

              {{-- ======================== FORM ISIAN PEMELIHARAAN ======================== --}}
              <div class="mb-3 d-flex align-items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                <h5 class="mb-0">Isian Pemeliharaan</h5>
              </div>

              {{-- Waktu + Petugas + Keterangan --}}
              <div class="row">
                <div class="col-md-4">
                  <label for="waktu_pemeliharaan">Waktu Pemeliharaan</label>
                  <input required
  type="datetime-local"
  id="waktu_pemeliharaan"
  name="waktu_pemeliharaan"
  step="1"
  class="form-control @error('waktu_pemeliharaan') is-invalid @enderror"
  value="{{ $waktuDefault ? \Carbon\Carbon::parse($waktuDefault)->format('Y-m-d\TH:i:s') : '' }}">
                  @error('waktu_pemeliharaan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                  <label>Diubah Oleh</label>
                  <input type="text" class="form-control" value="{{ $userName }}" readonly>
                </div>

                <div class="col-md-4">
                  <label>Keterangan (opsional)</label>
                  <input type="text" name="keterangan_history" maxlength="20"
                         class="form-control @error('keterangan_history') is-invalid @enderror"
                         value="{{ old('keterangan_history') }}">
                  @error('keterangan_history') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>

              <hr>

              {{-- ======================== SUTM & OUT ======================== --}}
              <div class="row">
                <div class="col-12"><h5>SUTM & OUT</h5></div>
                <div class="col-md-3 mt-2">
                  <label>SUTM (mm)</label>
                  <input required type="text" name="sutm_mm" maxlength="15" class="form-control" value="{{ v('sutm_mm', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Jumper SUTM OUT Fasa R</label>
                  <input required type="text" name="jumper_sutm_out_fasa_r" maxlength="10" class="form-control" value="{{ v('jumper_sutm_out_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Jumper SUTM OUT Fasa S</label>
                  <input required type="text" name="jumper_sutm_out_fasa_s" maxlength="10" class="form-control" value="{{ v('jumper_sutm_out_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Jumper SUTM OUT Fasa T</label>
                  <input required type="text" name="jumper_sutm_out_fasa_t" maxlength="10" class="form-control" value="{{ v('jumper_sutm_out_fasa_t', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Kondisi SUTM CO & Income ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Kondisi SUTM CO & Income</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Cond SUTM CO Fasa R</label>
                  <input required type="text" name="cond_sutm_co_fasa_r" maxlength="10" class="form-control" value="{{ v('cond_sutm_co_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Cond SUTM CO Fasa S</label>
                  <input required type="text" name="cond_sutm_co_fasa_s" maxlength="10" class="form-control" value="{{ v('cond_sutm_co_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Cond SUTM CO Fasa T</label>
                  <input required type="text" name="cond_sutm_co_fasa_t" maxlength="10" class="form-control" value="{{ v('cond_sutm_co_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-2">
                  <label>Jumper SUTM CO Income Fasa R</label>
                  <input required type="text" name="jumper_sutm_co_income_fasa_r" maxlength="10" class="form-control" value="{{ v('jumper_sutm_co_income_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper SUTM CO Income Fasa S</label>
                  <input required type="text" name="jumper_sutm_co_income_fasa_s" maxlength="10" class="form-control" value="{{ v('jumper_sutm_co_income_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper SUTM CO Income Fasa T</label>
                  <input required type="text" name="jumper_sutm_co_income_fasa_t" maxlength="10" class="form-control" value="{{ v('jumper_sutm_co_income_fasa_t', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Fuse Link & Keramik ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Fuse Link & Keramik</h5></div>
                <div class="col-md-2 mt-2">
                  <label>Fuse Link Fasa R</label>
                  <input required type="number" name="fuse_link_fasa_r" max="32767" class="form-control" value="{{ v('fuse_link_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Fuse Link Fasa S</label>
                  <input required type="number" name="fuse_link_fasa_s" max="32767" class="form-control" value="{{ v('fuse_link_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Fuse Link Fasa T</label>
                  <input required type="number" name="fuse_link_fasa_t" max="32767" class="form-control" value="{{ v('fuse_link_fasa_t', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Keramik Polimer</label>
                  <input required type="text" name="keramik_polimer" maxlength="10" class="form-control" value="{{ v('keramik_polimer', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== CO Trafo Primer ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>CO Trafo Primer</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Jumper CO Trafo Primer OUT Fasa R</label>
                  <input required type="text" name="jumper_co_trafo_primer_out_fasa_r" maxlength="10" class="form-control" value="{{ v('jumper_co_trafo_primer_out_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper CO Trafo Primer OUT Fasa S</label>
                  <input required type="text" name="jumper_co_trafo_primer_out_fasa_s" maxlength="10" class="form-control" value="{{ v('jumper_co_trafo_primer_out_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper CO Trafo Primer OUT Fasa T</label>
                  <input required type="text" name="jumper_co_trafo_primer_out_fasa_t" maxlength="10" class="form-control" value="{{ v('jumper_co_trafo_primer_out_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-2">
                  <label>Cond CO Trafo Bush Primer Fasa R</label>
                  <input required type="text" name="cond_co_trafo_bush_primer_fasa_r" maxlength="10" class="form-control" value="{{ v('cond_co_trafo_bush_primer_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Cond CO Trafo Bush Primer Fasa S</label>
                  <input required type="text" name="cond_co_trafo_bush_primer_fasa_s" maxlength="10" class="form-control" value="{{ v('cond_co_trafo_bush_primer_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Cond CO Trafo Bush Primer Fasa T</label>
                  <input required type="text" name="cond_co_trafo_bush_primer_fasa_t" maxlength="10" class="form-control" value="{{ v('cond_co_trafo_bush_primer_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-2">
                  <label>Jumper CO Bush Primer Income Fasa R</label>
                  <input required type="text" name="jumper_co_bush_trafo_primer_income_fasa_r" maxlength="10" class="form-control" value="{{ v('jumper_co_bush_trafo_primer_income_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper CO Bush Primer Income Fasa S</label>
                  <input required type="text" name="jumper_co_bush_trafo_primer_income_fasa_s" maxlength="10" class="form-control" value="{{ v('jumper_co_bush_trafo_primer_income_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper CO Bush Primer Income Fasa T</label>
                  <input required type="text" name="jumper_co_bush_trafo_primer_income_fasa_t" maxlength="10" class="form-control" value="{{ v('jumper_co_bush_trafo_primer_income_fasa_t', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Arester & LA (+ bush primer ↔ arester) ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Arester & Lightning Arrester (LA)</h5></div>

                <div class="col-12 mt-2"><strong>Jumper/Kondisi Bush Primer ↔ Arester</strong></div>

                <div class="col-12 mt-2"><em>Jumper Bush Primer OUT → Arester</em></div>
                <div class="col-md-4 mt-2">
                  <label>OUT → Arester Fasa R</label>
                  <input required type="text" name="jumper_bush_primer_out_arester_fasa_r" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_out_arester_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>OUT → Arester Fasa S</label>
                  <input required type="text" name="jumper_bush_primer_out_arester_fasa_s" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_out_arester_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>OUT → Arester Fasa T</label>
                  <input required type="text" name="jumper_bush_primer_out_arester_fasa_t" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_out_arester_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-12 mt-2"><em>Kondisi Bush Primer ↔ Arester</em></div>
                <div class="col-md-4 mt-2">
                  <label>Kondisi Fasa R</label>
                  <input required type="text" name="cond_bush_primer_arester_fasa_r" maxlength="10"
                         class="form-control" value="{{ v('cond_bush_primer_arester_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Kondisi Fasa S</label>
                  <input required type="text" name="cond_bush_primer_arester_fasa_s" maxlength="10"
                         class="form-control" value="{{ v('cond_bush_primer_arester_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Kondisi Fasa T</label>
                  <input required type="text" name="cond_bush_primer_arester_fasa_t" maxlength="10"
                         class="form-control" value="{{ v('cond_bush_primer_arester_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-12 mt-2"><em>Jumper Bush Primer INCOME → Arester</em></div>
                <div class="col-md-4 mt-2">
                  <label>INCOME → Arester Fasa R</label>
                  <input required type="text" name="jumper_bush_primer_income_arester_fasa_r" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_income_arester_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>INCOME → Arester Fasa S</label>
                  <input required type="text" name="jumper_bush_primer_income_arester_fasa_s" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_income_arester_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>INCOME → Arester Fasa T</label>
                  <input required type="text" name="jumper_bush_primer_income_arester_fasa_t" maxlength="10"
                         class="form-control" value="{{ v('jumper_bush_primer_income_arester_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                @php $opsiAdaTidak = ['ada'=>'Ada', 'tidak ada'=>'Tidak Ada']; @endphp
                <div class="col-md-4 mt-3">
                  <label>Arester Fasa R</label>
                  <select required name="arester_fasa_r" class="form-control">
                    @foreach($opsiAdaTidak as $k=>$t)
                      <option value="{{ $k }}" {{ v('arester_fasa_r', $pemeliharaan ?? null)==$k?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 mt-3">
                  <label>Arester Fasa S</label>
                  <select required name="arester_fasa_s" class="form-control">
                    @foreach($opsiAdaTidak as $k=>$t)
                      <option value="{{ $k }}" {{ v('arester_fasa_s', $pemeliharaan ?? null)==$k?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 mt-3">
                  <label>Arester Fasa T</label>
                  <select required name="arester_fasa_t" class="form-control">
                    @foreach($opsiAdaTidak as $k=>$t)
                      <option value="{{ $k }}" {{ v('arester_fasa_t', $pemeliharaan ?? null)==$k?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 mt-3">
                  <label>Keramik Polimer Lighting Arester</label>
                  <input required type="text" name="keramik_polimer_lighting_arester" maxlength="10" class="form-control" value="{{ v('keramik_polimer_lighting_arester', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-3">
                  <label>Jumper Dudukan Arester Fasa R</label>
                  <input required type="text" name="jumper_dudukan_arester_fasa_r" maxlength="10" class="form-control" value="{{ v('jumper_dudukan_arester_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Jumper Dudukan Arester Fasa S</label>
                  <input required type="text" name="jumper_dudukan_arester_fasa_s" maxlength="10" class="form-control" value="{{ v('jumper_dudukan_arester_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Jumper Dudukan Arester Fasa T</label>
                  <input required type="text" name="jumper_dudukan_arester_fasa_t" maxlength="10" class="form-control" value="{{ v('jumper_dudukan_arester_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-3">
                  <label>Cond Dudukan LA</label>
                  <input required type="text" name="cond_dudukan_la" maxlength="10" class="form-control" value="{{ v('cond_dudukan_la', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Jumper Body TRF ⇄ LA</label>
                  <input required type="text" name="jumper_body_trf_la" maxlength="10" class="form-control" value="{{ v('jumper_body_trf_la', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Cond Body TRF ⇄ LA</label>
                  <input required type="text" name="cond_body_trf_la" maxlength="10" class="form-control" value="{{ v('cond_body_trf_la', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Jumper Cond LA ⇄ Body TRF</label>
                  <input required type="text" name="jumper_cond_la_dg_body_trf" maxlength="10" class="form-control" value="{{ v('jumper_cond_la_dg_body_trf', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-3">
                  <label>Cond Ground LA ⇄ Panel</label>
                  <input required type="text" name="cond_ground_la_panel" maxlength="10" class="form-control" value="{{ v('cond_ground_la_panel', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Isolasi & Arus Bocor ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Isolasi & Arus Bocor</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Isolasi Fasa R</label>
                  <input required type="text" name="isolasi_fasa_r" maxlength="10" class="form-control" value="{{ v('isolasi_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Isolasi Fasa S</label>
                  <input required type="text" name="isolasi_fasa_s" maxlength="10" class="form-control" value="{{ v('isolasi_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Isolasi Fasa T</label>
                  <input required type="text" name="isolasi_fasa_t" maxlength="10" class="form-control" value="{{ v('isolasi_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-2">
                  <label>Arus Bocor (A)</label>
                  <input required type="number" name="arus_bocor" class="form-control"
                         min="0" max="999.99" step="0.01"
                         value="{{ v('arus_bocor', $pemeliharaan ?? null) }}"
                         inputmode="decimal" pattern="^\d{1,3}(\.\d{1,2})?$">
                </div>
              </div>

              {{-- ======================== Panel & Tahanan Isolasi ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Panel & Tahanan Isolasi</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Jumper TRF Bush Skunder 4x Panel</label>
                  <input required type="text" name="jumper_trf_bush_skunder_4x_panel" maxlength="10" class="form-control" value="{{ v('jumper_trf_bush_skunder_4x_panel', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Cond OUT TRF ⇄ Panel</label>
                  <input required type="text" name="cond_out_trf_panel" maxlength="10" class="form-control" value="{{ v('cond_out_trf_panel', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Tahanan Isolasi PP</label>
                  <input required type="number" name="tahanan_isolasi_pp" max="32767" class="form-control" value="{{ v('tahanan_isolasi_pp', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Tahanan Isolasi PG</label>
                  <input required type="number" name="tahanan_isolasi_pg" max="32767" class="form-control" value="{{ v('tahanan_isolasi_pg', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Panel Saklar & NH Utama ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Panel Saklar & NH Utama</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Jumper IN Panel Saklar</label>
                  <input required type="text" name="jumper_in_panel_saklar" maxlength="10" class="form-control" value="{{ v('jumper_in_panel_saklar', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper IN Nol</label>
                  <input required type="text" name="jumper_in_nol" maxlength="10" class="form-control" value="{{ v('jumper_in_nol', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper Nol ⇄ Ground</label>
                  <input required type="text" name="jumper_nol_ground" maxlength="10" class="form-control" value="{{ v('jumper_nol_ground', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-4 mt-2">
                  <label>Jenis Saklar Utama</label>
                  <input required type="text" name="jenis_saklar_utama" maxlength="10" class="form-control" value="{{ v('jenis_saklar_utama', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jumper dari Saklar OUT</label>
                  <input required type="text" name="jumper_dr_saklar_out" maxlength="10" class="form-control" value="{{ v('jumper_dr_saklar_out', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Jenis Conductor dari Saklar NH Utama</label>
                  <input required type="text" name="jenis_cond_dr_saklar_nh_utama" maxlength="20" class="form-control" value="{{ v('jenis_cond_dr_saklar_nh_utama', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Proteksi Utama & Jurusan ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Data Proteksi Utama</h5></div>
                <div class="col-md-4 mt-2">
                  <label>Proteksi Utama Fasa R</label>
                  <input required type="text" name="data_proteksi_utama_fasa_r" maxlength="10" class="form-control" value="{{ v('data_proteksi_utama_fasa_r', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Proteksi Utama Fasa S</label>
                  <input required type="text" name="data_proteksi_utama_fasa_s" maxlength="10" class="form-control" value="{{ v('data_proteksi_utama_fasa_s', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-4 mt-2">
                  <label>Proteksi Utama Fasa T</label>
                  <input required type="text" name="data_proteksi_utama_fasa_t" maxlength="10" class="form-control" value="{{ v('data_proteksi_utama_fasa_t', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-12 mt-3"><h5>Jurusan NH Utama</h5></div>
                <div class="col-md-6 mt-2">
                  <label>Jenis Conductor dari NH Utama → Jurusan</label>
                  <input required type="text" name="jenis_cond_dr_nh_utama_jurusan" maxlength="20" class="form-control" value="{{ v('jenis_cond_dr_nh_utama_jurusan', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-6 mt-2">
                  <label>Jumper dari NH Jurusan (IN)</label>
                  <input required type="text" name="jumper_dr_nh_jurusan_in" maxlength="20" class="form-control" value="{{ v('jumper_dr_nh_jurusan_in', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Proteksi Line A-D ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Data Proteksi Line</h5></div>
                @foreach(['a','b','c','d'] as $line)
                  <div class="col-12 mt-2"><strong>Line {{ strtoupper($line) }}</strong></div>
                  <div class="col-md-4 mt-2">
                    <label>Fasa R</label>
                    <input required type="text" name="data_proteksi_line_{{ $line }}_fasa_r" maxlength="10" class="form-control"
                           value="{{ v('data_proteksi_line_'.$line.'_fasa_r', $pemeliharaan ?? null) }}">
                  </div>
                  <div class="col-md-4 mt-2">
                    <label>Fasa S</label>
                    <input required type="text" name="data_proteksi_line_{{ $line }}_fasa_s" maxlength="10" class="form-control"
                           value="{{ v('data_proteksi_line_'.$line.'_fasa_s', $pemeliharaan ?? null) }}">
                  </div>
                  <div class="col-md-4 mt-2">
                    <label>Fasa T</label>
                    <input required type="text" name="data_proteksi_line_{{ $line }}_fasa_t" maxlength="10" class="form-control"
                           value="{{ v('data_proteksi_line_'.$line.'_fasa_t', $pemeliharaan ?? null) }}">
                  </div>
                @endforeach
              </div>

              {{-- ======================== JTR / OUT JTR ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>JTR / OUT JTR</h5></div>
                <div class="col-md-6 mt-2">
                  <label>Jumper OUT dari NH Jurusan → Cond OUT JTR</label>
                  <input required type="text" name="jumper_out_dr_nh_jurusan_cond_out_jtr" maxlength="10" class="form-control"
                         value="{{ v('jumper_out_dr_nh_jurusan_cond_out_jtr', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-12 mt-2"><strong>Conductor dari NH Jurusan OUT → JTR (per line)</strong></div>
                @foreach(['a','b','c','d'] as $line)
                  <div class="col-md-3 mt-2">
                    <label>Line {{ strtoupper($line) }}</label>
                    <input required type="text" name="cond_dr_nh_jurusan_out_jtr_line_{{ $line }}" maxlength="10" class="form-control"
                           value="{{ v('cond_dr_nh_jurusan_out_jtr_line_'.$line, $pemeliharaan ?? null) }}">
                  </div>
                @endforeach

                <div class="col-12 mt-3"><strong>Conductor Jurusan JTR (per line)</strong></div>
                @foreach(['a','b','c','d'] as $line)
                  <div class="col-md-3 mt-2">
                    <label>Line {{ strtoupper($line) }}</label>
                    <input required type="text" name="cond_jurusan_jtr_line_{{ $line }}" maxlength="10" class="form-control"
                           value="{{ v('cond_jurusan_jtr_line_'.$line, $pemeliharaan ?? null) }}">
                  </div>
                @endforeach
              </div>

              {{-- ======================== Panel LA & Grounding ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Panel LA & Grounding</h5></div>
                <div class="col-md-3 mt-2">
                  <label>Jumper LA ⇄ Body Panel</label>
                  <input required type="text" name="jumper_la_body_panel" maxlength="10" class="form-control" value="{{ v('jumper_la_body_panel', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Cond dari Ground LA → Body</label>
                  <input required type="text" name="cond_dr_ground_la_body" maxlength="10" class="form-control" value="{{ v('cond_dr_ground_la_body', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Cond dari Nol → Ground</label>
                  <input required type="text" name="cond_dr_nol_ground" maxlength="10" class="form-control" value="{{ v('cond_dr_nol_ground', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Cond Kopel Body ⇄ LA Ground</label>
                  <input required type="text" name="cond_dr_kopel_body_dg_la_ground" maxlength="10" class="form-control" value="{{ v('cond_dr_kopel_body_dg_la_ground', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-3 mt-2">
                  <label>Nilai R Tanah Nol</label>
                  <input required type="number" name="nilai_r_tanah_nol" max="32767" class="form-control" value="{{ v('nilai_r_tanah_nol', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Nilai R Tanah LA</label>
                  <input required type="number" name="nilai_r_tanah_la" max="32767" class="form-control" value="{{ v('nilai_r_tanah_la', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Panel GTT ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Panel GTT</h5></div>
                <div class="col-md-2 mt-2">
                  <label>Pintu</label>
                  <input required type="text" name="panel_gtt_pintu" maxlength="10" class="form-control" value="{{ v('panel_gtt_pintu', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Kunci</label>
                  <input required type="text" name="panel_gtt_kunci" maxlength="10" class="form-control" value="{{ v('panel_gtt_kunci', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>No GTT</label>
                  <input required type="text" name="panel_gtt_no_gtt" maxlength="10" class="form-control" value="{{ v('panel_gtt_no_gtt', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Kondisi</label>
                  <input required type="text" name="panel_gtt_kondisi" maxlength="10" class="form-control" value="{{ v('panel_gtt_kondisi', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Lubang Pipa</label>
                  <input required type="text" name="panel_gtt_lubang_pipa" maxlength="10" class="form-control" value="{{ v('panel_gtt_lubang_pipa', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>Pondasi</label>
                  <input required type="text" name="panel_gtt_pondasi" maxlength="10" class="form-control" value="{{ v('panel_gtt_pondasi', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-3 mt-2">
                  <label>Tanda Peringatan</label>
                  <input required type="text" name="panel_gtt_tanda_peringatan" maxlength="10" class="form-control" value="{{ v('panel_gtt_tanda_peringatan', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Jenis Gardu</label>
                  <input required type="text" name="panel_gtt_jenis_gardu" maxlength="10" class="form-control" value="{{ v('panel_gtt_jenis_gardu', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Tanggal Inspeksi</label>
                  <input required type="date" name="panel_gtt_tgl_inspeksi" class="form-control"
                         value="{{ old('panel_gtt_tgl_inspeksi', isset($pemeliharaan->panel_gtt_tgl_inspeksi) ? \Carbon\Carbon::parse($pemeliharaan->panel_gtt_tgl_inspeksi)->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>Inspeksi Siang</label>
                  <input required type="text" name="panel_gtt_insp_siang" maxlength="10" class="form-control" value="{{ v('panel_gtt_insp_siang', $pemeliharaan ?? null) }}">
                </div>

                <div class="col-md-6 mt-2">
                  <label>Pekerjaan Pemeliharaan</label>
                  <input required type="text" name="panel_gtt_pekerjaan_pemeliharaan" maxlength="50" class="form-control" value="{{ v('panel_gtt_pekerjaan_pemeliharaan', $pemeliharaan ?? null) }}">
                </div>
                <div class="col-md-6 mt-2">
                  <label>Catatan (opsional)</label>
                  <input type="text" name="panel_gtt_catatan" maxlength="50" class="form-control" value="{{ v('panel_gtt_catatan', $pemeliharaan ?? null) }}">
                </div>
              </div>

              {{-- ======================== Tahanan Isolasi Trafo 1–3 ======================== --}}
              <div class="row mt-3">
                <div class="col-12"><h5>Tahanan Isolasi Trafo</h5></div>
                @foreach([1,2,3] as $n)
                  <div class="col-12 mt-2"><strong>Trafo {{ $n }}</strong></div>
                  <div class="col-md-4 mt-2">
                    <label>P-B</label>
                    <input required type="number" name="tahan_isolasi_trafo_{{ $n }}_pb" max="32767" class="form-control"
                           value="{{ v('tahan_isolasi_trafo_'.$n.'_pb', $pemeliharaan ?? null) }}">
                  </div>
                  <div class="col-md-4 mt-2">
                    <label>S-B</label>
                    <input required type="number" name="tahan_isolasi_trafo_{{ $n }}_sb" max="32767" class="form-control"
                           value="{{ v('tahan_isolasi_trafo_'.$n.'_sb', $pemeliharaan ?? null) }}">
                  </div>
                  <div class="col-md-4 mt-2">
                    <label>P-S</label>
                    <input required type="number" name="tahan_isolasi_trafo_{{ $n }}_ps" max="32767" class="form-control"
                           value="{{ v('tahan_isolasi_trafo_'.$n.'_ps', $pemeliharaan ?? null) }}">
                  </div>
                @endforeach
              </div>

            </div>

            <div class="card-footer d-flex align-items-center">
              <a href="{{ route('gardu.edit', $gardu->id) }}" class="btn btn-secondary mr-2">Kembali</a>
              <button type="submit" class="btn btn-{{ $isEdit ? 'primary' : 'success' }}">
                {{ $isEdit ? 'Update' : 'Submit' }}
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ===== Style (samakan dengan pengukuran) ===== --}}
<style>
  .gardu-summary{
    background: linear-gradient(180deg,#e9f3ff 0%,#f7fbff 100%);
    border: 1px solid #cfe2ff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.05);
  }
</style>

{{-- SweetAlert (samakan perilaku) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')) })
  .then(() => window.location.href = "{{ route('gardu.index') }}");
</script>
@endif
@if (session('error'))
<script>
Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('error')) });
</script>
@endif
@if (session('info'))
<script>
Swal.fire({ icon: 'info', title: 'Info', text: @json(session('info')) });
</script>
@endif
@endsection
