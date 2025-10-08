@extends('layouts.pln.adminlte.app')

@section('title', 'Manajemen Gardu - Tambah')

@section('content')
<br>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card card-primary">
          <div class="card-header card-header-custom">
            <h3 class="card-title">Tambahkan Data Gardu</h3>
          </div>

          {{-- FORM --}}
          <form id="form-gardu" method="POST" action="{{ route('gardu.store') }}">
            @csrf
            <div class="card-body">

              {{-- Baris 1 --}}
                <div class="row">
                {{-- gardu_induk --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Gardu Induk <span class="text-danger">*</span></label>
                    <select name="gardu_induk" class="form-control @error('gardu_induk') is-invalid @enderror" required>
                        <option value="">-- pilih --</option>
                        <option value="banyuwangi" {{ old('gardu_induk')=='banyuwangi'?'selected':'' }}>banyuwangi</option>
                        <option value="genteng"    {{ old('gardu_induk')=='genteng'?'selected':'' }}>genteng</option>
                    </select>
                    @error('gardu_induk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- kd_trf_gi --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Kode TRF GI <span class="text-danger">*</span></label>
                    <select name="kd_trf_gi" class="form-control @error('kd_trf_gi') is-invalid @enderror" required>
                        <option value="">-- pilih --</option>
                        @foreach([1,2,3,4] as $v)
                        <option value="{{ $v }}" {{ old('kd_trf_gi')==$v?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('kd_trf_gi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- jml_trafo (DROPDOWN 1-3) --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Jumlah Trafo <span class="text-danger">*</span></label>
                    <select name="jml_trafo" id="jml_trafo"
                        class="form-control @error('jml_trafo') is-invalid @enderror" required>
                        @foreach([1,2,3] as $n)
                        <option value="{{ $n }}" {{ old('jml_trafo', 1)==$n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                    @error('jml_trafo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                </div>

                {{-- Baris 2: kd_pylg + kd_gardu + daya_trafo (satu baris) --}}
                <div class="row">
                {{-- kd_pylg --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Kode Penyulang <span class="text-danger">*</span></label>
                    <input type="text" name="kd_pylg" maxlength="20"
                        class="form-control @error('kd_pylg') is-invalid @enderror"
                        value="{{ old('kd_pylg') }}" required>
                    @error('kd_pylg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- kd_gardu --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Kode Gardu <span class="text-danger">*</span></label>
                    <input type="text" name="kd_gardu" maxlength="10"
                        class="form-control @error('kd_gardu') is-invalid @enderror"
                        value="{{ old('kd_gardu') }}" required>
                    @error('kd_gardu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- daya_trafo --}}
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Daya Trafo (kVA) <span class="text-danger">*</span></label>
                    <input type="number" name="daya_trafo" max="32767"
                        class="form-control @error('daya_trafo') is-invalid @enderror"
                        value="{{ old('daya_trafo') }}" required>
                    @error('daya_trafo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                </div>



              {{-- Baris 3 --}}
              <div class="row">
                {{-- alamat --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Alamat <span class="text-danger">*</span></label>
                    <input type="text" name="alamat" maxlength="30"
                      class="form-control @error('alamat') is-invalid @enderror"
                      value="{{ old('alamat') }}" required>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- desa --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Desa <span class="text-danger">*</span></label>
                    <input type="text" name="desa" maxlength="20"
                      class="form-control @error('desa') is-invalid @enderror"
                      value="{{ old('desa') }}" required>
                    @error('desa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              {{-- === TRAFO 1 (UTAMA) === --}}
              <div class="mt-2 border p-3 rounded">
                <h5 class="mb-3">Data Trafo 1</h5>

                <div class="row">
                  {{-- no_seri --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>No. Seri <span class="text-danger">*</span></label>
                      <input type="text" name="no_seri" maxlength="20"
                        class="form-control @error('no_seri') is-invalid @enderror"
                        value="{{ old('no_seri') }}" required>
                      @error('no_seri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- berat_total --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Berat Total <span class="text-danger">*</span></label>
                      <input type="number" name="berat_total" max="32767"
                        class="form-control @error('berat_total') is-invalid @enderror"
                        value="{{ old('berat_total') }}" required>
                      @error('berat_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- berat_minyak --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Berat Minyak <span class="text-danger">*</span></label>
                      <input type="number" name="berat_minyak" max="32767"
                        class="form-control @error('berat_minyak') is-invalid @enderror"
                        value="{{ old('berat_minyak') }}" required>
                      @error('berat_minyak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- hubungan --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Hubungan <span class="text-danger">*</span></label>
                      <input type="text" name="hubungan" maxlength="10"
                        class="form-control @error('hubungan') is-invalid @enderror"
                        value="{{ old('hubungan') }}" required>
                      @error('hubungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- impedansi --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Impedansi (%) <span class="text-danger">*</span></label>
                      <input type="number" name="impedansi" step="0.1" min="0" max="99.9"
                        class="form-control @error('impedansi') is-invalid @enderror"
                        value="{{ old('impedansi') }}" required>
                      @error('impedansi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- tegangan_tm --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tegangan TM <span class="text-danger">*</span></label>
                      <input type="number" name="tegangan_tm" max="32767"
                        class="form-control @error('tegangan_tm') is-invalid @enderror"
                        value="{{ old('tegangan_tm') }}" required>
                      @error('tegangan_tm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- tegangan_tr --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tegangan TR <span class="text-danger">*</span></label>
                      <input type="number" name="tegangan_tr" max="32767"
                        class="form-control @error('tegangan_tr') is-invalid @enderror"
                        value="{{ old('tegangan_tr') }}" required>
                      @error('tegangan_tr') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- frekuensi --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Frekuensi <span class="text-danger">*</span></label>
                      <input type="text" name="frekuensi" maxlength="20"
                        class="form-control @error('frekuensi') is-invalid @enderror"
                        value="{{ old('frekuensi') }}" required>
                      @error('frekuensi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- tahun --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tahun (YYYY) <span class="text-danger">*</span></label>
                      <input type="text" name="tahun" pattern="\d{4}" maxlength="4"
                        class="form-control @error('tahun') is-invalid @enderror"
                        value="{{ old('tahun') }}" placeholder="2025" required>
                      @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- merek_trafo --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Merek Trafo <span class="text-danger">*</span></label>
                      <input type="text" name="merek_trafo" maxlength="30"
                        class="form-control @error('merek_trafo') is-invalid @enderror"
                        value="{{ old('merek_trafo') }}" required>
                      @error('merek_trafo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- section_lbs --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Section LBS <span class="text-danger">*</span></label>
                      <input type="text" name="section_lbs" maxlength="30"
                        class="form-control @error('section_lbs') is-invalid @enderror"
                        value="{{ old('section_lbs') }}" required>
                      @error('section_lbs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- fasa --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Fasa <span class="text-danger">*</span></label>
                      <input type="number" name="fasa" max="127"
                        class="form-control @error('fasa') is-invalid @enderror"
                        value="{{ old('fasa') }}" required>
                      @error('fasa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- nilai_sdk_utama --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Arus Skunder <span class="text-danger">*</span></label>
                      <input type="number" name="nilai_sdk_utama" max="32767"
                        class="form-control @error('nilai_sdk_utama') is-invalid @enderror"
                        value="{{ old('nilai_sdk_utama') }}" required>
                      @error('nilai_sdk_utama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- nilai_primer --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Arus Primer <span class="text-danger">*</span></label>
                      <input type="number" name="nilai_primer" step="0.1" min="0" max="99.9"
                        class="form-control @error('nilai_primer') is-invalid @enderror"
                        value="{{ old('nilai_primer') }}" required>
                      @error('nilai_primer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  {{-- tap_no --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tap No <span class="text-danger">*</span></label>
                      <input type="number" name="tap_no" max="127"
                        class="form-control @error('tap_no') is-invalid @enderror"
                        value="{{ old('tap_no') }}" required>
                      @error('tap_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- tap_kv --}}
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tap kV <span class="text-danger">*</span></label>
                      <input type="number" name="tap_kv" step="0.1" min="0" max="99.9"
                        class="form-control @error('tap_kv') is-invalid @enderror"
                        value="{{ old('tap_kv') }}" required>
                      @error('tap_kv') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>
              </div> {{-- /Trafo 1 --}}

              {{-- ====== OPSIONAL: TRAFO 2 ====== --}}
              <div id="trafo-2-group" class="mt-3" style="display:none;">
                <div class="border p-3 rounded bg-light">
                  <h5 class="mb-3">Data Trafo 2 (opsional)</h5>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Merek Trafo 2</label>
                        <input type="text" name="merek_trafo_2" maxlength="30"
                          class="form-control @error('merek_trafo_2') is-invalid @enderror"
                          value="{{ old('merek_trafo_2') }}">
                        @error('merek_trafo_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>No. Seri Trafo 2</label>
                        <input type="text" name="no_seri_2" maxlength="20"
                          class="form-control @error('no_seri_2') is-invalid @enderror"
                          value="{{ old('no_seri_2') }}">
                        @error('no_seri_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tahun Trafo 2 (YYYY)</label>
                        <input type="text" name="tahun_2" pattern="\d{4}" maxlength="4"
                          class="form-control @error('tahun_2') is-invalid @enderror"
                          value="{{ old('tahun_2') }}" placeholder="2025">
                        @error('tahun_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Berat Minyak Trafo 2</label>
                        <input type="number" name="berat_minyak_2" max="32767"
                          class="form-control @error('berat_minyak_2') is-invalid @enderror"
                          value="{{ old('berat_minyak_2') }}">
                        @error('berat_minyak_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Berat Total Trafo 2</label>
                        <input type="number" name="berat_total_2" max="32767"
                          class="form-control @error('berat_total_2') is-invalid @enderror"
                          value="{{ old('berat_total_2') }}">
                        @error('berat_total_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- ====== OPSIONAL: TRAFO 3 ====== --}}
              <div id="trafo-3-group" class="mt-3" style="display:none;">
                <div class="border p-3 rounded bg-light">
                  <h5 class="mb-3">Data Trafo 3 (opsional)</h5>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Merek Trafo 3</label>
                        <input type="text" name="merek_trafo_3" maxlength="30"
                          class="form-control @error('merek_trafo_3') is-invalid @enderror"
                          value="{{ old('merek_trafo_3') }}">
                        @error('merek_trafo_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>No. Seri Trafo 3</label>
                        <input type="text" name="no_seri_3" maxlength="20"
                          class="form-control @error('no_seri_3') is-invalid @enderror"
                          value="{{ old('no_seri_3') }}">
                        @error('no_seri_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tahun Trafo 3 (YYYY)</label>
                        <input type="text" name="tahun_3" pattern="\d{4}" maxlength="4"
                          class="form-control @error('tahun_3') is-invalid @enderror"
                          value="{{ old('tahun_3') }}" placeholder="2025">
                        @error('tahun_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Berat Minyak Trafo 3</label>
                        <input type="number" name="berat_minyak_3" max="32767"
                          class="form-control @error('berat_minyak_3') is-invalid @enderror"
                          value="{{ old('berat_minyak_3') }}">
                        @error('berat_minyak_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Berat Total Trafo 3</label>
                        <input type="number" name="berat_total_3" max="32767"
                          class="form-control @error('berat_total_3') is-invalid @enderror"
                          value="{{ old('berat_total_3') }}">
                        @error('berat_total_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Rekondisi & Bengkel --}}
              <div class="row mt-3">
                {{-- rekondisi_preman --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Rekondisi / Preman <span class="text-danger">*</span></label>
                    <select name="rekondisi_preman" class="form-control @error('rekondisi_preman') is-invalid @enderror" required>
                      <option value="">-- pilih --</option>
                      <option value="rek" {{ old('rekondisi_preman')=='rek'?'selected':'' }}>Rekondisi</option>
                      <option value="pre" {{ old('rekondisi_preman')=='pre'?'selected':'' }}>Preman</option>
                    </select>
                    @error('rekondisi_preman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- bengkel --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Bengkel <span class="text-danger">*</span></label>
                    <select name="bengkel" class="form-control @error('bengkel') is-invalid @enderror" required>
                      <option value="">-- pilih --</option>
                      <option value="wep" {{ old('bengkel')=='wep'?'selected':'' }}>wep</option>
                      <option value="mar" {{ old('bengkel')=='mar'?'selected':'' }}>mar</option>
                    </select>
                    @error('bengkel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              {{-- Keterangan --}}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" maxlength="20"
                      class="form-control @error('keterangan') is-invalid @enderror"
                      value="{{ old('keterangan') }}">
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

            </div>

            <div class="card-footer d-flex gap-2">
              <a href="{{ route('gardu.index') }}" class="btn btn-secondary">Kembali</a>
              <button type="submit" class="btn btn-custom">Submit</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- === OPTIONAL: AJAX submit (kalau store() return JSON) === --}}
<script>
  // helper: enable/disable semua input dalam container
  function setDisabled(container, disabled) {
    container.querySelectorAll('input, select, textarea').forEach(el => {
      el.disabled = disabled;
    });
  }

  function updateTrafoFields() {
    const jml = parseInt(document.getElementById('jml_trafo').value || '1', 10);
    const g2 = document.getElementById('trafo-2-group');
    const g3 = document.getElementById('trafo-3-group');

    if (jml >= 2) {
      g2.style.display = '';
      setDisabled(g2, false);
    } else {
      g2.style.display = 'none';
      setDisabled(g2, true);
    }

    if (jml >= 3) {
      g3.style.display = '';
      setDisabled(g3, false);
    } else {
      g3.style.display = 'none';
      setDisabled(g3, true);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('jml_trafo');
    sel.addEventListener('change', updateTrafoFields);
    updateTrafoFields(); // hormati old() saat reload validasi
  });

  // === Matikan jika tidak perlu AJAX ===
  document.getElementById('form-gardu').addEventListener('submit', async function(e) {
    @if (app()->environment()) /* gampang dimatikan bila tak perlu */
    e.preventDefault();
    const form = e.target;
    const url  = form.action;
    const fd   = new FormData(form);

    try {
      const res  = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value },
        body: fd
      });
      const data = await res.json();

      if (res.ok) {
        await Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message ?? 'Data gardu ditambahkan.' });
        window.location.href = "{{ route('gardu.index') }}";
      } else if (res.status === 422) {
        const errs = data.errors || {};
        const msg  = Object.values(errs).flat().join('\n');
        Swal.fire({ icon: 'error', title: 'Validasi gagal', text: msg });
      } else {
        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.' });
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: err.message });
    }
    @endif
  });
</script>
@endsection
