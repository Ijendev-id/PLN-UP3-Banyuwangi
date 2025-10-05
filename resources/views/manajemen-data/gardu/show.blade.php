@extends('layouts.pln.adminlte.app')

@section('title', 'Manajemen Gardu - Detail')

@section('content')
<br>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card card-primary">
          <div class="card-header card-header-custom">
            <h3 class="card-title">Detail Data Gardu</h3>
          </div>

          <div class="card-body">

            {{-- ================== RINGKASAN GARDU + QR ================== --}}
            <div class="gardu-summary p-3 rounded mb-4">
              <div class="row align-items-center">
                {{-- Info Grid --}}
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Gardu Induk</div>
                      <div class="summary-value">{{ $gardu->gardu_induk }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Kode Gardu</div>
                      <div class="summary-value">{{ $gardu->kd_gardu }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Penyulang</div>
                      <div class="summary-value">{{ $gardu->kd_pylg ?? $gardu->penyulang ?? '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Trafo GI</div>
                      <div class="summary-value">{{ $gardu->kd_trf_gi }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                      <div class="summary-label">Alamat</div>
                      <div class="summary-value">{{ $gardu->alamat }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Desa</div>
                      <div class="summary-value">{{ $gardu->desa ?? '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Jumlah Trafo</div>
                      <div class="summary-value">{{ $gardu->jml_trafo ?? 1 }}</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Daya Trafo (VA)</div>
                      <div class="summary-value">{{ $gardu->daya_trafo }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Merek Trafo</div>
                      <div class="summary-value">{{ $gardu->merek_trafo }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">No. Seri</div>
                      <div class="summary-value">{{ $gardu->no_seri }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="summary-label">Tahun</div>
                      <div class="summary-value">{{ $gardu->tahun }}</div>
                    </div>

                    {{-- Tambahan sesuai permintaan --}}
                    <div class="col-md-3 mb-1">
                      <div class="summary-label">Beban kVA Trafo</div>
                      <div class="summary-value">
                        {{ $gardu->beban_kva_trafo !== null ? number_format($gardu->beban_kva_trafo, 1) : '-' }}
                      </div>
                    </div>
                    <div class="col-md-3 mb-1">
                      <div class="summary-label">Persentase Beban (%)</div>
                      <div class="summary-value">
                        {{ $gardu->persentase_beban !== null ? number_format($gardu->persentase_beban, 1) : '-' }}
                      </div>
                    </div>
                  </div>
                </div>

                {{-- QR di sisi kanan --}}
                <div class="col-md-3 text-center mt-3 mt-md-0">
                  <div class="qr-wrap d-inline-block p-3">
                    <div id="qr-kd-gardu" data-text="{{ $gardu->kd_gardu }}"></div>
                  </div>
                  <div class="mt-2">
                    <span class="badge badge-secondary px-3 py-2">
                      Kode Gardu: <strong>{{ $gardu->kd_gardu }}</strong>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            {{-- ================== /RINGKASAN GARDU + QR ================== --}}

            {{-- === TRAFO 1 (UTAMA) === --}}
            <div class="mt-2 border p-3 rounded">
              <h5 class="mb-3">Data Trafo 1</h5>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">No. Seri</label>
                    <input type="text" class="form-control" value="{{ $gardu->no_seri }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Berat Total</label>
                    <input type="text" class="form-control" value="{{ $gardu->berat_total }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Berat Minyak</label>
                    <input type="text" class="form-control" value="{{ $gardu->berat_minyak }}" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Hubungan</label>
                    <input type="text" class="form-control" value="{{ $gardu->hubungan }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Impedansi (%)</label>
                    <input type="text" class="form-control" value="{{ $gardu->impedansi }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Tegangan TM</label>
                    <input type="text" class="form-control" value="{{ $gardu->tegangan_tm }}" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Tegangan TR</label>
                    <input type="text" class="form-control" value="{{ $gardu->tegangan_tr }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Frekuensi</label>
                    <input type="text" class="form-control" value="{{ $gardu->frekuensi }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Tahun</label>
                    <input type="text" class="form-control" value="{{ $gardu->tahun }}" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Merek Trafo</label>
                    <input type="text" class="form-control" value="{{ $gardu->merek_trafo }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Section LBS</label>
                    <input type="text" class="form-control" value="{{ $gardu->section_lbs }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Fasa</label>
                    <input type="text" class="form-control" value="{{ $gardu->fasa }}" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Nilai SDK Utama</label>
                    <input type="text" class="form-control" value="{{ $gardu->nilai_sdk_utama }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Nilai Primer</label>
                    <input type="text" class="form-control" value="{{ $gardu->nilai_primer }}" readonly>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="mb-1">Tap No</label>
                    <input type="text" class="form-control" value="{{ $gardu->tap_no }}" readonly>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-0">
                    <label class="mb-1">Tap kV</label>
                    <input type="text" class="form-control" value="{{ $gardu->tap_kv }}" readonly>
                  </div>
                </div>
              </div>
            </div> {{-- /Trafo 1 --}}

            {{-- ====== OPSIONAL: TRAFO 2 ====== --}}
            @if ((int) $gardu->jml_trafo >= 2)
              <div class="mt-3 border p-3 rounded bg-light">
                <h5 class="mb-3">Data Trafo 2 (opsional)</h5>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">Merek Trafo 2</label>
                      <input type="text" class="form-control" value="{{ $gardu->merek_trafo_2 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">No. Seri Trafo 2</label>
                      <input type="text" class="form-control" value="{{ $gardu->no_seri_2 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">Tahun Trafo 2 (YYYY)</label>
                      <input type="text" class="form-control" value="{{ $gardu->tahun_2 }}" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label class="mb-1">Berat Minyak Trafo 2</label>
                      <input type="text" class="form-control" value="{{ $gardu->berat_minyak_2 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label class="mb-1">Berat Total Trafo 2</label>
                      <input type="text" class="form-control" value="{{ $gardu->berat_total_2 }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            @endif

            {{-- ====== OPSIONAL: TRAFO 3 ====== --}}
            @if ((int) $gardu->jml_trafo >= 3)
              <div class="mt-3 border p-3 rounded bg-light">
                <h5 class="mb-3">Data Trafo 3 (opsional)</h5>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">Merek Trafo 3</label>
                      <input type="text" class="form-control" value="{{ $gardu->merek_trafo_3 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">No. Seri Trafo 3</label>
                      <input type="text" class="form-control" value="{{ $gardu->no_seri_3 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label class="mb-1">Tahun Trafo 3 (YYYY)</label>
                      <input type="text" class="form-control" value="{{ $gardu->tahun_3 }}" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label class="mb-1">Berat Minyak Trafo 3</label>
                      <input type="text" class="form-control" value="{{ $gardu->berat_minyak_3 }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label class="mb-1">Berat Total Trafo 3</label>
                      <input type="text" class="form-control" value="{{ $gardu->berat_total_3 }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            @endif

            {{-- Rekondisi & Bengkel --}}
            <div class="row mt-3">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label class="mb-1">Rekondisi / Preman</label>
                  <input type="text" class="form-control" value="{{ $gardu->rekondisi_preman }}" readonly>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="mb-1">Bengkel</label>
                  <input type="text" class="form-control" value="{{ $gardu->bengkel }}" readonly>
                </div>
              </div>
            </div>

          </div>

          {{-- ============= FOOTER: TOMBOL AKSI ============= --}}
          <div class="card-footer">
            <div class="d-flex flex-wrap align-items-center">
              <a href="{{ route('gardu.index') }}" class="btn btn-secondary mr-2 mb-2">Kembali</a>

              @if(auth()->check() && in_array(auth()->user()->role, ['admin','petugas']))
                <a href="{{ route('omt-pengukuran.create', ['kd_gardu' => $gardu->kd_gardu]) }}"
                   class="btn btn-warning mr-2 mb-2" title="Input Pengukuran">
                  <i class="fas fa-bolt"></i> Pengukuran
                </a>

                <a href="{{ route('pemeliharaan.create', ['kd_gardu' => $gardu->kd_gardu]) }}"
                   class="btn btn-info mr-2 mb-2" title="Form Pemeliharaan">
                  <i class="fas fa-tools"></i> Pemeliharaan
                </a>
              @endif

              <a href="{{ route('gardu.cetak.pdf.gardu', $gardu->id) }}" target="_blank"
                 class="btn btn-danger mb-2" title="Cetak Data Gardu ke PDF">
                <i class="fas fa-file-pdf"></i> Cetak PDF
              </a>
            </div>
          </div>
          {{-- ============= /FOOTER ============= --}}

        </div>

      </div>
    </div>
  </div>
</section>

{{-- ======= Styles kecil agar rapi & kontras ======= --}}
<style>
  .gardu-summary{
    background:#f7f9ff;
    border:1px solid #e6ecff;
  }
  .summary-label{
    font-size:.8rem;
    color:#6c757d;
    margin-bottom:.1rem;
  }
  .summary-value{
    font-weight:600;
    line-height:1.2;
  }
  .qr-wrap{
    background:#fff;
    border:1px dashed #b6c9ff;
    border-radius:10px;
  }
</style>

{{-- ======= QRCode library + render ======= --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('qr-kd-gardu');
  const value = el?.dataset.text || '';
  if (!value) return;
  if (typeof QRCode === 'undefined') return;
  new QRCode(el, {
    text: value,
    width: 140,
    height: 140,
    correctLevel: QRCode.CorrectLevel.M
  });
});
</script>
@endsection
