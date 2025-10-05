{{-- resources/views/manajemen-data/gardu/qr.blade.php --}}
@extends('layouts.pln.adminlte.app')

@section('title', 'Scan QR Gardu')

@section('content')
<br>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card card-primary">
          <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h3 class="card-title">Scan QR / Cari Kode Gardu</h3>
            {{-- tombol header disederhanakan; tombol aksi akan muncul di bawah detail --}}
          </div>

          <div class="card-body">
            <div class="row g-4">
              {{-- Kolom kiri: Scanner & Cari Manual --}}
              <div class="col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Scan QR (isi: kd_gardu)</label>
                  <div id="reader" style="width:100%; max-width:360px; border:1px dashed #cbd5e1; border-radius:.5rem;"></div>
                  <small class="text-muted d-block mt-2">
                    Izinkan akses kamera browser untuk memindai QR. Jika tidak tersedia, gunakan cari manual di bawah.
                  </small>
                </div>

                <hr>

                <div class="mb-3">
                  <label for="inputKodeGardu" class="form-label">Cari Manual berdasarkan Kode Gardu</label>
                  <div class="input-group">
                    <input type="text" id="inputKodeGardu" class="form-control" placeholder="mis. GD-00123" value="{{ $prefillKd ?? '' }}">
                    <button id="btnCari" class="btn btn-primary">Cari</button>
                  </div>
                  <small class="form-text text-muted">Masukkan <strong>kd_gardu</strong> persis seperti di database.</small>
                </div>

                <div id="alertBox" class="mt-2" style="display:none;"></div>
              </div>

              {{-- Kolom kanan: Detail Gardu (summary style) --}}
              <div class="col-lg-8">
                <h5 class="mb-3">Detail Data Gardu</h5>

                <div id="wrapDetail" class="gardu-summary p-3 p-md-4 mb-3" style="display:none;">
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Gardu Induk</div>
                      <div class="font-weight-bold" id="gardu_induk">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Kode Gardu</div>
                      <div class="font-weight-bold" id="kd_gardu">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Penyulang</div>
                      <div class="font-weight-bold" id="kd_pylg">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Trafo GI</div>
                      <div class="font-weight-bold" id="kd_trf_gi">-</div>
                    </div>

                    <div class="col-md-6 mb-3">
                      <div class="text-muted small">Alamat</div>
                      <div class="font-weight-bold" id="alamat">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Desa</div>
                      <div class="font-weight-bold" id="desa">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Jumlah Trafo</div>
                      <div class="font-weight-bold" id="jml_trafo">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Daya Trafo (VA)</div>
                      <div class="font-weight-bold" id="daya_trafo">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Merek Trafo</div>
                      <div class="font-weight-bold" id="merek_trafo">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">No. Seri</div>
                      <div class="font-weight-bold" id="no_seri">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Tahun</div>
                      <div class="font-weight-bold" id="tahun">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Beban kVA Trafo</div>
                      <div class="font-weight-bold" id="beban_kva_trafo">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Persentase Beban (%)</div>
                      <div class="font-weight-bold" id="persentase_beban">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Berat Total</div>
                      <div class="font-weight-bold" id="berat_total">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Berat Minyak</div>
                      <div class="font-weight-bold" id="berat_minyak">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Hubungan</div>
                      <div class="font-weight-bold" id="hubungan">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Impedansi (%)</div>
                      <div class="font-weight-bold" id="impedansi">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Tegangan TM</div>
                      <div class="font-weight-bold" id="tegangan_tm">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Tegangan TR</div>
                      <div class="font-weight-bold" id="tegangan_tr">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Frekuensi</div>
                      <div class="font-weight-bold" id="frekuensi">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Section LBS</div>
                      <div class="font-weight-bold" id="section_lbs">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Fasa</div>
                      <div class="font-weight-bold" id="fasa">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Nilai SDK Utama</div>
                      <div class="font-weight-bold" id="nilai_sdk_utama">-</div>
                    </div>

                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Nilai Primer</div>
                      <div class="font-weight-bold" id="nilai_primer">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Tap No</div>
                      <div class="font-weight-bold" id="tap_no">-</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <div class="text-muted small">Tap kV</div>
                      <div class="font-weight-bold" id="tap_kv">-</div>
                    </div>

                    <div class="col-md-6 mb-3">
                      <div class="text-muted small">Rekondisi / Preman</div>
                      <div class="font-weight-bold" id="rekondisi_preman">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <div class="text-muted small">Bengkel</div>
                      <div class="font-weight-bold" id="bengkel">-</div>
                    </div>
                  </div>
                </div>

                {{-- Tombol aksi: muncul hanya setelah data dimuat --}}
                <div id="actionButtons" class="d-flex flex-wrap gap-2" style="display:none;">
                  <a href="{{ route('gardu.index') }}" class="btn btn-secondary">
                    Kembali
                  </a>

                  <a id="btnCetakPdf" href="#" target="_blank" class="btn btn-danger disabled" aria-disabled="true">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                  </a>

                  @if(auth()->check() && in_array(auth()->user()->role, ['admin','petugas']))
                    <a id="btnPengukuran" href="#" class="btn btn-warning disabled" aria-disabled="true">
                      Pengukuran
                    </a>
                    <a id="btnPemeliharaan" href="#" class="btn btn-info disabled" aria-disabled="true">
                      Pemeliharaan
                    </a>
                  @endif
                </div>

              </div> {{-- /col-lg-8 --}}
            </div> {{-- /row --}}
          </div> {{-- /card-body --}}
        </div>{{-- /card --}}

      </div>
    </div>
  </div>
</section>

{{-- ===== Style (sesuai detail gardu) ===== --}}
<style>
  .gardu-summary{
    background: linear-gradient(180deg,#e9f3ff 0%,#f7fbff 100%);
    border: 1px solid #cfe2ff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.05);
  }
  .gap-2 { gap:.5rem; }
</style>
@endsection

@push('scripts')
  <script src="https://unpkg.com/html5-qrcode"></script>
  <script>
    const csrf = "{{ csrf_token() }}";
    const findUrl = "{{ route('gardu.findByKode') }}"; // POST { kd_gardu: "…" }
    const pdfTpl  = "{{ route('gardu.cetak.pdf.gardu', ':id') }}"; // ganti :id dengan real id

    // Template link untuk tombol aksi (akan di-replace kd_gardu)
    @if(auth()->check() && in_array(auth()->user()->role, ['admin','petugas']))
      const pengukuranTpl   = "{{ route('omt-pengukuran.create', ['kd_gardu' => 'KD_PLACEHOLDER']) }}";
      const pemeliharaanTpl = "{{ route('pemeliharaan.create',   ['kd_gardu' => 'KD_PLACEHOLDER']) }}";
    @endif

    function setAlert(html, klass = 'alert-danger') {
      const box = document.getElementById('alertBox');
      box.className = `alert ${klass}`;
      box.innerHTML = html;
      box.style.display = 'block';
    }
    function clearAlert(){ const b=document.getElementById('alertBox'); b.style.display='none'; b.className=''; b.innerHTML=''; }

    function showDetailBox(show=true){
      document.getElementById('wrapDetail').style.display = show ? 'block' : 'none';
      document.getElementById('actionButtons').style.display = show ? 'flex' : 'none';
    }

    function setText(id, val){ const el = document.getElementById(id); if(el) el.textContent = (val ?? '-') + ''; }

    function disablePdf(){
      const btn = document.getElementById('btnCetakPdf');
      if(!btn) return;
      btn.classList.add('disabled'); btn.setAttribute('aria-disabled','true'); btn.href='#';
    }
    function setPdfButton(id){
      const btn = document.getElementById('btnCetakPdf');
      if(!btn) return;
      if(!id){ disablePdf(); return; }
      btn.classList.remove('disabled'); btn.removeAttribute('aria-disabled');
      btn.href = pdfTpl.replace(':id', id);
    }

    function disableActions(){
      disablePdf();
      const pk = document.getElementById('btnPengukuran');
      const pm = document.getElementById('btnPemeliharaan');
      [pk, pm].forEach(b => {
        if (!b) return;
        b.classList.add('disabled');
        b.setAttribute('aria-disabled','true');
        b.href = '#';
      });
    }
    function setActions(kd){
      const pk = document.getElementById('btnPengukuran');
      const pm = document.getElementById('btnPemeliharaan');
      const enc = encodeURIComponent(kd || '');
      if (pk && typeof pengukuranTpl !== 'undefined' && kd){
        pk.href = pengukuranTpl.replace('KD_PLACEHOLDER', enc);
        pk.classList.remove('disabled'); pk.removeAttribute('aria-disabled');
      }
      if (pm && typeof pemeliharaanTpl !== 'undefined' && kd){
        pm.href = pemeliharaanTpl.replace('KD_PLACEHOLDER', enc);
        pm.classList.remove('disabled'); pm.removeAttribute('aria-disabled');
      }
    }

    function clearDetail(){
      [
        'gardu_induk','kd_trf_gi','kd_pylg','kd_gardu','daya_trafo','jml_trafo',
        'alamat','desa','no_seri','berat_total','berat_minyak','hubungan','impedansi',
        'tegangan_tm','tegangan_tr','frekuensi','tahun','merek_trafo','beban_kva_trafo',
        'persentase_beban','section_lbs','fasa','nilai_sdk_utama','nilai_primer','tap_no',
        'tap_kv','rekondisi_preman','bengkel'
      ].forEach(id => setText(id, '-'));
      disableActions();
      showDetailBox(false);
    }

    function setDetail(d){
      setText('gardu_induk', d.gardu_induk);
      setText('kd_trf_gi', d.kd_trf_gi);
      setText('kd_pylg', d.kd_pylg);
      setText('kd_gardu', d.kd_gardu);
      setText('daya_trafo', d.daya_trafo);
      setText('jml_trafo', d.jml_trafo);
      setText('alamat', d.alamat);
      setText('desa', d.desa);
      setText('no_seri', d.no_seri);
      setText('berat_total', d.berat_total);
      setText('berat_minyak', d.berat_minyak);
      setText('hubungan', d.hubungan);
      setText('impedansi', d.impedansi);
      setText('tegangan_tm', d.tegangan_tm);
      setText('tegangan_tr', d.tegangan_tr);
      setText('frekuensi', d.frekuensi);
      setText('tahun', d.tahun);
      setText('merek_trafo', d.merek_trafo);
      setText('beban_kva_trafo', d.beban_kva_trafo);
      setText('persentase_beban', d.persentase_beban);
      setText('section_lbs', d.section_lbs);
      setText('fasa', d.fasa);
      setText('nilai_sdk_utama', d.nilai_sdk_utama);
      setText('nilai_primer', d.nilai_primer);
      setText('tap_no', d.tap_no);
      setText('tap_kv', d.tap_kv);
      setText('rekondisi_preman', d.rekondisi_preman);
      setText('bengkel', d.bengkel);

      setPdfButton(d.id);
      setActions(d.kd_gardu);
      showDetailBox(true);
    }

    async function fetchByKode(kd){
      clearAlert(); clearDetail();
      if(!kd || !kd.trim()){ setAlert('Masukkan <b>kd_gardu</b> yang valid.', 'alert-warning'); return; }
      setAlert('Mencari data…', 'alert-info');
      try{
        const res = await fetch(findUrl, {
          method: 'POST',
          headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
          body: JSON.stringify({ kd_gardu: kd.trim() })
        });
        if(!res.ok){ throw new Error(await res.text() || 'Gagal memuat data'); }
        const json = await res.json();
        if(json.status === 'ok' && json.data){
          setDetail(json.data);
          setAlert('Data ditemukan dan dimuat.', 'alert-success');
        }else{
          setAlert('Data dengan kd_gardu tersebut tidak ditemukan.', 'alert-warning');
        }
      }catch(err){
        console.error(err);
        setAlert('Terjadi kesalahan: ' + (err.message || err), 'alert-danger');
      }
    }

    document.addEventListener('DOMContentLoaded', function(){
      document.getElementById('btnCari').addEventListener('click', () => {
        const kd = document.getElementById('inputKodeGardu').value;
        fetchByKode(kd);
      });
      document.getElementById('inputKodeGardu').addEventListener('keydown', (e) => {
        if(e.key === 'Enter'){ e.preventDefault(); document.getElementById('btnCari').click(); }
      });

      // Prefill dari controller (query ?kd=) kalau ada:
      const pre = "{{ $prefillKd ?? '' }}";
      if(pre){ fetchByKode(pre); }
    });

    // Inisialisasi scanner QR
    (function initScanner(){
      if (!window.Html5QrcodeScanner) return;
      function onScanSuccess(decodedText){
        document.getElementById('inputKodeGardu').value = decodedText;
        fetchByKode(decodedText);
      }
      function onScanFailure(_e){ /* silent */ }
      const scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
      scanner.render(onScanSuccess, onScanFailure);
    })();
  </script>
@endpush
