@extends('layouts.pln.adminlte.app')

@section('title', 'Input Pengukuran OMT')

@section('content')
<br>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card card-primary">
          <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h3 class="card-title">Form Pengukuran</h3>
            @if(!empty($pengukuran))
              <div class="d-flex align-items-center">
                <span class="badge badge-warning">Mode: Update</span>
                <a href="{{ route('omt-pengukuran.cetak.pdf.pengukuran', $pengukuran->id) }}"
                   class="btn btn-sm btn-danger ml-2" target="_blank">
                  <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
              </div>
            @else
              <span class="badge badge-success">Mode: Input Baru</span>
            @endif
          </div>

          @php
            $isEdit = !empty($pengukuran);
            $formAction = $isEdit
              ? url('manajemen-data/omt-pengukuran/'.$pengukuran->id)
              : route('omt-pengukuran.store');
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

              {{-- ====== HIDDEN yang harus ikut terkirim ====== --}}
              <input type="hidden" name="kd_gardu"
                     value="{{ old('kd_gardu', $pengukuran->kd_gardu ?? $gardu->kd_gardu) }}">
              {{-- Diubah oleh: tampilkan & kirimkan --}}
              @php $userName = auth()->user()->name ?? auth()->user()->nama_lengkap ?? '—'; @endphp
              <input type="hidden" name="diubah_oleh" value="{{ $userName }}">

              {{-- ======================== FORM ISIAN PENGUKURAN ======================== --}}
              <div class="mb-3 d-flex align-items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                <h5 class="mb-0">Isian Pengukuran</h5>
              </div>

              <div class="row">
                {{-- 1) ian, iar, ias, iat --}}
                <div class="col-md-3">
                  <label>ian</label>
                  <input required type="number" name="ian" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ian', $pengukuran->ian ?? '') }}">
                </div>
                <div class="col-md-3">
                  <label>iar</label>
                  <input required type="number" name="iar" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('iar', $pengukuran->iar ?? '') }}">
                </div>
                <div class="col-md-3">
                  <label>ias</label>
                  <input required type="number" name="ias" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ias', $pengukuran->ias ?? '') }}">
                </div>
                <div class="col-md-3">
                  <label>iat</label>
                  <input required type="number" name="iat" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('iat', $pengukuran->iat ?? '') }}">
                </div>

                {{-- 2) ibn, ibr, ibs, ibt --}}
                <div class="col-md-3 mt-2">
                  <label>ibn</label>
                  <input required type="number" name="ibn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ibn', $pengukuran->ibn ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ibr</label>
                  <input required type="number" name="ibr" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ibr', $pengukuran->ibr ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ibs</label>
                  <input required type="number" name="ibs" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ibs', $pengukuran->ibs ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ibt</label>
                  <input required type="number" name="ibt" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ibt', $pengukuran->ibt ?? '') }}">
                </div>

                {{-- 3) icn, icr, ics, ict --}}
                <div class="col-md-3 mt-2">
                  <label>icn</label>
                  <input required type="number" name="icn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('icn', $pengukuran->icn ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>icr</label>
                  <input required type="number" name="icr" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('icr', $pengukuran->icr ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ics</label>
                  <input required type="number" name="ics" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ics', $pengukuran->ics ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ict</label>
                  <input required type="number" name="ict" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ict', $pengukuran->ict ?? '') }}">
                </div>

                {{-- 4) idn, idr, ids, idt --}}
                <div class="col-md-3 mt-2">
                  <label>idn</label>
                  <input required type="number" name="idn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('idn', $pengukuran->idn ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>idr</label>
                  <input required type="number" name="idr" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('idr', $pengukuran->idr ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ids</label>
                  <input required type="number" name="ids" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ids', $pengukuran->ids ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>idt</label>
                  <input required type="number" name="idt" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('idt', $pengukuran->idt ?? '') }}">
                </div>

                {{-- 5) iun, iur, ius, iut --}}
                <div class="col-md-3 mt-2">
                  <label>iun</label>
                  <input required type="number" name="iun" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('iun', $pengukuran->iun ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>iur</label>
                  <input required type="number" name="iur" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('iur', $pengukuran->iur ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>ius</label>
                  <input required type="number" name="ius" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('ius', $pengukuran->ius ?? '') }}">
                </div>
                <div class="col-md-3 mt-2">
                  <label>iut</label>
                  <input required type="number" name="iut" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('iut', $pengukuran->iut ?? '') }}">
                </div>

                {{-- 6) vrn, vrs, vsn, vst, vtn, vtr --}}
                <div class="col-md-2 mt-2">
                  <label>vrn</label>
                  <input required type="number" name="vrn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vrn', $pengukuran->vrn ?? '') }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>vrs</label>
                  <input required type="number" name="vrs" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vrs', $pengukuran->vrs ?? '') }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>vsn</label>
                  <input required type="number" name="vsn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vsn', $pengukuran->vsn ?? '') }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>vst</label>
                  <input required type="number" name="vst" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vst', $pengukuran->vst ?? '') }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>vtn</label>
                  <input required type="number" name="vtn" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vtn', $pengukuran->vtn ?? '') }}">
                </div>
                <div class="col-md-2 mt-2">
                  <label>vtr</label>
                  <input required type="number" name="vtr" class="form-control" step="1" min="0" max="32767"
                         value="{{ old('vtr', $pengukuran->vtr ?? '') }}">
                </div>
              </div>

              {{-- Waktu, Diubah Oleh, Keterangan --}}
              @php
                $waktuDefault = old('waktu_pengukuran',
                  isset($pengukuran->waktu_pengukuran)
                    ? \Carbon\Carbon::parse($pengukuran->waktu_pengukuran)->format('Y-m-d H:i:s')
                    : now()->format('Y-m-d H:i:s')
                );
              @endphp
              <div class="row mt-3">
                <div class="col-md-4">
                  <label for="waktu_pengukuran">Waktu Pengukuran</label>
                  <input required
                         type="datetime-local"
                         id="waktu_pengukuran"
                         name="waktu_pengukuran"
                         class="form-control @error('waktu_pengukuran') is-invalid @enderror"
                         value="{{ old('waktu_pengukuran', $waktuDefault ? \Carbon\Carbon::parse($waktuDefault)->format('Y-m-d\TH:i') : '') }}">
                  @error('waktu_pengukuran') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                  <label>Diubah Oleh</label>
                  <input type="text" class="form-control" value="{{ $userName }}" readonly>
                  {{-- hidden sudah ada di atas --}}
                </div>

                <div class="col-md-4">
                  <label>Keterangan (opsional)</label>
                  <input type="text" name="keterangan_history" maxlength="20"
                         class="form-control @error('keterangan_history') is-invalid @enderror"
                         value="{{ old('keterangan_history') }}">
                  @error('keterangan_history') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
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

{{-- ===== Style ===== --}}
<style>
  .gardu-summary{
    background: linear-gradient(180deg,#e9f3ff 0%,#f7fbff 100%);
    border: 1px solid #cfe2ff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.05);
  }
  .qr-wrap{
    background: #ffffff;
    border: 1px dashed #b6c9ff;
    border-radius: 12px;
  }
</style>

{{-- SweetAlert --}}
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
Swal.fire({ icon: 'info', title: 'Tidak ada perubahan', text: @json(session('info')) });
</script>
@endif
@endsection
