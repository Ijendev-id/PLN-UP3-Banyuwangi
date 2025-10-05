<?php

namespace App\Http\Controllers\Admin\ManajemenData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryData\HistoryDataGardu;
use App\Models\ManajemenData\DataGardu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class GarduController extends Controller
{
    public function index(Request $request)
    {
        $q          = $request->query('q');           // cari kd_gardu/kd_pylg/desa/alamat
        $garduInduk = $request->query('gardu_induk'); // banyuwangi|genteng
        $kdTrfGi    = $request->query('kd_trf_gi');   // 1|2|3|4

        $gardus = DataGardu::query()
            ->select([
                'id',
                'gardu_induk',
                'kd_trf_gi',
                'jml_trafo',
                'kd_pylg',
                'kd_gardu',
                'alamat',
                'desa',
                'daya_trafo',
                'merek_trafo',
                'no_seri',
                'tahun',
                'beban_kva_trafo',
                'persentase_beban',
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('kd_gardu', 'like', "%{$q}%")
                        ->orWhere('kd_pylg', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%")
                        ->orWhere('gardu_induk', 'like', "%{$q}%"); // ✅ tambahan ini
                });
            })
            ->when($garduInduk, fn($q2) => $q2->where('gardu_induk', $garduInduk))
            ->when($kdTrfGi,    fn($q2) => $q2->where('kd_trf_gi', $kdTrfGi))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $gardus]);
        }

        return view('manajemen-data.gardu.index', [
            'gardus'       => $gardus,
            'q'            => $q,
            'gardu_induk'  => $garduInduk,
            'kd_trf_gi'    => $kdTrfGi,
        ]);
    }
    public function create()
    {
        return view('manajemen-data.gardu.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gardu_induk' => 'required|in:banyuwangi,genteng',
            'kd_trf_gi' => 'required|in:1,2,3,4',
            'kd_pylg' => 'required|string|max:20',
            'kd_gardu' => 'required|string|max:10|unique:data_gardu,kd_gardu',
            'daya_trafo' => 'required|integer|max:32767',
            'jml_trafo' => 'required|integer|max:127',
            'alamat' => 'required|string|max:30',
            'desa' => 'required|string|max:20',
            'no_seri' => 'required|string|max:20',
            'berat_total' => 'required|integer|max:32767',
            'berat_minyak' => 'required|integer|max:32767',
            'hubungan' => 'required|string|max:10',
            'impedansi' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'tegangan_tm' => 'required|integer|max:32767',
            'tegangan_tr' => 'required|integer|max:32767',
            'frekuensi' => 'required|string|max:20',
            'tahun' => 'required|regex:/^\d{4}$/',
            'merek_trafo' => 'required|string|max:30',
            'beban_kva_trafo' => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
            'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
            'section_lbs' => 'required|string|max:30',
            'fasa' => 'required|integer|max:127',
            'nilai_sdk_utama' => 'required|integer|max:32767',
            'nilai_primer' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'tap_no' => 'required|integer|max:127',
            'tap_kv' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'rekondisi_preman' => 'required|in:rek,pre',
            'bengkel' => 'required|in:wep,mar',

            'merek_trafo_2' => 'nullable|string|max:30', //update baru
            'merek_trafo_3' => 'nullable|string|max:30',
            'no_seri_2' => 'nullable|string|max:20',
            'no_seri_3' => 'nullable|string|max:20',
            'tahun_2' => 'nullable|regex:/^\d{4}$/',
            'tahun_3' => 'nullable|regex:/^\d{4}$/',
            'berat_minyak_2' => 'nullable|integer|max:32767',
            'berat_minyak_3' => 'nullable|integer|max:32767',
            'berat_total_2' => 'nullable|integer|max:32767',
            'berat_total_3' => 'nullable|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan' => 'nullable|string|max:20',
        ], [
            'gardu_induk.required' => 'Gardu induk wajib diisi',
            'gardu_induk.in' => 'Gardu induk hanya boleh di isi dengan: banyuwangi atau genteng',
            'kd_trf_gi.required' => 'Kode trf GI wajib diisi',
            'kd_trf_gi.in' => 'Kode traf GI hanya boleh 1, 2, 3, atau 4',
            'kd_pylg.required' => 'Kode penyulang wajib diisi',
            'kd_pylg.max' => 'Panjang kode pylg maksimal adalah 20 karakter',
            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.unique' => 'Kode gardu sudah terdaftar pada sistem',
            'kd_gardu.max' => 'Panjang maksimal kode gardu adalah 10 karakter',
            'daya_trafo.required' => 'Daya trafo wajib diisi',
            'daya_trafo.integer' => 'Daya trafo harus berupa angka',
            'daya_trafo.max' => 'Daya trafo maksimal adalah 32.767',
            'jml_trafo.required' => 'Jumlah trafo wajib diisi',
            'jml_trafo.integer' => 'Jumlah trafo harus berupa angka',
            'jml_trafo.max' => 'Jumlah trafo maksimal adalah 127',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Panjang karakter maksimal alamat adalah 30',
            'desa.required' => 'Desa wajib diisi',
            'desa.max' => 'Panjang karakter maksimal desa adalah 20',
            'no_seri.required' => 'Nomor seri wajib diisi',
            'no_seri.max' => 'Panjang karakter maksimal nomor seri adalah 20',
            'berat_total.required' => 'Berat total wajib diisi',
            'berat_total.integer' => 'Berat total harus berupa angka',
            'berat_total.max' => 'Berat total maksimal adalah 32.767',
            'berat_minyak.required' => 'Berat minyak wajib diisi',
            'berat_minyak.integer' => 'Berat minyak harus berupa angka',
            'berat_minyak.max' => 'Berat minyak maksimal adalah 32.767',
            'hubungan.required' => 'Hubungan wajib diisi',
            'hubungan.max' => 'Panjang karakter maksimal hubungan adalah 10',
            'impedansi.required' => 'Impedansi wajib diisi',
            'impedansi.numeric' => 'Impedansi harus berupa angka',
            'impedansi.between' => 'Impedansi harus diantara 0 hingga 99.9',
            'impedansi.regex' => 'Sistem hanya menerima data impedansi dengan 1 angka desimal, contoh max input 99.9',
            'tegangan_tm.required' => 'Tegangan TM wajib diisi',
            'tegangan_tm.integer' => 'Tegangan TM harus berupa angka',
            'tegangan_tm.max' => 'Tegangan TM maksimal adalah 32.767',
            'tegangan_tr.required' => 'Tegangan TR wajib diisi',
            'tegangan_tr.integer' => 'Tegangan TR harus berupa angka',
            'tegangan_tr.max' => 'Tegangan TR maksimal adalah 32.767',
            'frekuensi.required' => 'Frekuensi wajib diisi',
            'frekuensi.max' => 'Panjang karakter maksimal frekuensi adalah 20',
            'tahun.required' => 'Tahun wajib diisi',
            'tahun.regex' => 'Tahun harus menggunakan format (YYYY)',
            'merek_trafo.required' => 'Merek trafo wajib diisi',
            'merek_trafo.max' => 'Panjang karakter maksimal Merek trafo adalah 30',
            'beban_kva_trafo.numeric' => 'Beban kva trafo harus berupa angka',
            'beban_kva_trafo.between' => 'Beban kva trafo harus diantara 0 hingga 9999.9',
            'beban_kva_trafo.regex' => 'Sistem hanya menerima data Beban kva trafo dengan 1 angka desimal, contoh max input 9999.9',
            'persentase_beban.numeric' => 'Persentase beban harus berupa angka',
            'persentase_beban.between' => 'Persentase beban harus diantara 0 hingga 999.9',
            'persentase_beban.regex' => 'Sistem hanya menerima data Persentase beban dengan 1 angka desimal, contoh max input 999.9',
            'section_lbs.required' => 'Section lbs wajib diisi',
            'section_lbs.max' => 'Panjang karakter maksimal Section lbs adalah 30',
            'fasa.required' => 'Fasa wajib diisi',
            'fasa.integer' => 'Fasa harus berupa angka',
            'fasa.max' => 'Fasa maksimal adalah 127',
            'nilai_sdk_utama.required' => 'Nilai sdk utama wajib diisi',
            'nilai_sdk_utama.integer' => 'Nilai sdk utama harus berupa angka',
            'nilai_sdk_utama.max' => 'Nilai sdk utama maksimal adalah 32.767',
            'nilai_primer.required' => 'Nilai primer wajib diisi',
            'nilai_primer.numeric' => 'Nilai primer harus berupa angka',
            'nilai_primer.between' => 'Nilai primer harus diantara 0 hingga 99.9',
            'nilai_primer.regex' => 'Sistem hanya menerima data Nilai primer dengan 1 angka desimal, contoh max input 99.9',
            'tap_no.required' => 'Tap No wajib diisi',
            'tap_no.integer' => 'Tap No harus berupa angka',
            'tap_no.max' => 'Tap no maksimal adalah 127',
            'tap_kv.required' => 'Tap kv wajib diisi',
            'tap_kv.numeric' => 'Tap kv harus berupa angka',
            'tap_kv.between' => 'Tap kv harus diantara 0 hingga 99.9',
            'tap_kv.regex' => 'Sistem hanya menerima data Tap kv dengan 1 angka desimal, contoh max input 99.9',
            'rekondisi_preman.required' => 'Rekondisi/Preman wajib diisi',
            'rekondisi_preman.in' => 'Rekondisi/Preman hanya boleh di isi dengan: rek atau pre',
            'bengkel.required' => 'Bengkel wajib diisi',
            'bengkel.in' => 'Bengkel hanya boleh di isi dengan: wep atau mar',

            'merek_trafo_2.max' => 'Panjang karakter maksimal Merek trafo 2 adalah 30', //update baru
            'merek_trafo_3.max' => 'Panjang karakter maksimal Merek trafo 3 adalah 30',
            'no_seri_2.max' => 'Panjang karakter maksimal nomor seri trafo 2 adalah 20',
            'no_seri_3.max' => 'Panjang karakter maksimal nomor seri trafo 3 adalah 20',
            'tahun_2.regex' => 'Tahun trafo 2 harus menggunakan format (YYYY)',
            'tahun_3.regex' => 'Tahun trafo 3 harus menggunakan format (YYYY)',
            'berat_minyak_2.integer' => 'Berat minyak trafo 2 harus berupa angka',
            'berat_minyak_2.max' => 'Berat minyak maksimal trafo 2 adalah 32.767',
            'berat_minyak_3.integer' => 'Berat minyak trafo 3 harus berupa angka',
            'berat_minyak_3.max' => 'Berat minyak maksimal trafo 3 adalah 32.767',
            'berat_total_2.integer' => 'Berat total trafo 2 harus berupa angka',
            'berat_total_2.max' => 'Berat total maksimal trafo 2 adalah 32.767',
            'berat_total_3.integer' => 'Berat total trafo 3 harus berupa angka',
            'berat_total_3.max' => 'Berat total maksimal trafo 3 adalah 32.767',

            'keterangan.max' => 'Keterangan maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gardu = DB::transaction(function () use ($request) {
                // ambil data request sesuai fillable
                $data = $request->only((new DataGardu())->getFillable());

                // simpan data utama
                $gardu = DataGardu::create($data);

                // simpan history
                HistoryDataGardu::create([
                    'id_data_gardu' => $gardu->id,
                    'data_lama' => json_encode($gardu->toArray(), JSON_UNESCAPED_UNICODE),
                    'aksi' => 'create',
                    'diubah_oleh' => Auth::user()->name,
                    'keterangan' => $request->input('keterangan'),
                ]);

                return $gardu;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data gardu berhasil ditambahkan',
                'data' => $gardu
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $gardu = DataGardu::find($id);

        if (!$gardu) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Data gardu tidak ditemukan'], 404);
            }
            return redirect()->route('gardu.index')->with('error', 'Data gardu tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'gardu_induk' => 'required|in:banyuwangi,genteng',
            'kd_trf_gi' => 'required|in:1,2,3,4',
            'kd_pylg' => 'required|string|max:20',
            'kd_gardu' => "required|string|max:10|unique:data_gardu,kd_gardu,{$id},id",
            'daya_trafo' => 'required|integer|max:32767',
            'jml_trafo' => 'required|integer|max:127',
            'alamat' => 'required|string|max:30',
            'desa' => 'required|string|max:20',
            'no_seri' => 'required|string|max:20',
            'berat_total' => 'required|integer|max:32767',
            'berat_minyak' => 'required|integer|max:32767',
            'hubungan' => 'required|string|max:10',
            'impedansi' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'tegangan_tm' => 'required|integer|max:32767',
            'tegangan_tr' => 'required|integer|max:32767',
            'frekuensi' => 'required|string|max:20',
            'tahun' => 'required|regex:/^\d{4}$/',
            'merek_trafo' => 'required|string|max:30',
            'beban_kva_trafo' => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
            'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
            'section_lbs' => 'required|string|max:30',
            'fasa' => 'required|integer|max:127',
            'nilai_sdk_utama' => 'required|integer|max:32767',
            'nilai_primer' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'tap_no' => 'required|integer|max:127',
            'tap_kv' => 'required|numeric|between:0,99.9|regex:/^\d{1,2}(\.\d)?$/',
            'rekondisi_preman' => 'required|in:rek,pre',
            'bengkel' => 'required|in:wep,mar',

            'merek_trafo_2' => 'nullable|string|max:30', //update baru
            'merek_trafo_3' => 'nullable|string|max:30',
            'no_seri_2' => 'nullable|string|max:20',
            'no_seri_3' => 'nullable|string|max:20',
            'tahun_2' => 'nullable|regex:/^\d{4}$/',
            'tahun_3' => 'nullable|regex:/^\d{4}$/',
            'berat_minyak_2' => 'nullable|integer|max:32767',
            'berat_minyak_3' => 'nullable|integer|max:32767',
            'berat_total_2' => 'nullable|integer|max:32767',
            'berat_total_3' => 'nullable|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan' => 'nullable|string|max:20',
        ], [
            'gardu_induk.required' => 'Gardu induk wajib diisi',
            'gardu_induk.in' => 'Gardu induk hanya boleh di isi dengan: banyuwangi atau genteng',
            'kd_trf_gi.required' => 'Kode trf GI wajib diisi',
            'kd_trf_gi.in' => 'Kode traf GI hanya boleh 1, 2, 3, atau 4',
            'kd_pylg.required' => 'Kode penyulang wajib diisi',
            'kd_pylg.max' => 'Panjang kode pylg maksimal adalah 20 karakter',
            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.unique' => 'Kode gardu sudah terdaftar pada sistem',
            'kd_gardu.max' => 'Panjang maksimal kode gardu adalah 10 karakter',
            'daya_trafo.required' => 'Daya trafo wajib diisi',
            'daya_trafo.integer' => 'Daya trafo harus berupa angka',
            'daya_trafo.max' => 'Daya trafo maksimal adalah 32.767',
            'jml_trafo.required' => 'Jumlah trafo wajib diisi',
            'jml_trafo.integer' => 'Jumlah trafo harus berupa angka',
            'jml_trafo.max' => 'Jumlah trafo maksimal adalah 127',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Panjang karakter maksimal alamat adalah 30',
            'desa.required' => 'Desa wajib diisi',
            'desa.max' => 'Panjang karakter maksimal desa adalah 20',
            'no_seri.required' => 'Nomor seri wajib diisi',
            'no_seri.max' => 'Panjang karakter maksimal nomor seri adalah 20',
            'berat_total.required' => 'Berat total wajib diisi',
            'berat_total.integer' => 'Berat total harus berupa angka',
            'berat_total.max' => 'Berat total maksimal adalah 32.767',
            'berat_minyak.required' => 'Berat minyak wajib diisi',
            'berat_minyak.integer' => 'Berat minyak harus berupa angka',
            'berat_minyak.max' => 'Berat minyak maksimal adalah 32.767',
            'hubungan.required' => 'Hubungan wajib diisi',
            'hubungan.max' => 'Panjang karakter maksimal hubungan adalah 10',
            'impedansi.required' => 'Impedansi wajib diisi',
            'impedansi.numeric' => 'Impedansi harus berupa angka',
            'impedansi.between' => 'Impedansi harus diantara 0 hingga 99.9',
            'impedansi.regex' => 'Sistem hanya menerima data impedansi dengan 1 angka desimal, contoh max input 99.9',
            'tegangan_tm.required' => 'Tegangan TM wajib diisi',
            'tegangan_tm.integer' => 'Tegangan TM harus berupa angka',
            'tegangan_tm.max' => 'Tegangan TM maksimal adalah 32.767',
            'tegangan_tr.required' => 'Tegangan TR wajib diisi',
            'tegangan_tr.integer' => 'Tegangan TR harus berupa angka',
            'tegangan_tr.max' => 'Tegangan TR maksimal adalah 32.767',
            'frekuensi.required' => 'Frekuensi wajib diisi',
            'frekuensi.max' => 'Panjang karakter maksimal frekuensi adalah 20',
            'tahun.required' => 'Tahun wajib diisi',
            'tahun.regex' => 'Tahun harus menggunakan format (YYYY)',
            'merek_trafo.required' => 'Merek trafo wajib diisi',
            'merek_trafo.max' => 'Panjang karakter maksimal Merek trafo adalah 30',
            'beban_kva_trafo.numeric' => 'Beban kva trafo harus berupa angka',
            'beban_kva_trafo.between' => 'Beban kva trafo harus diantara 0 hingga 9999.9',
            'beban_kva_trafo.regex' => 'Sistem hanya menerima data Beban kva trafo dengan 1 angka desimal, contoh max input 999.9',
            'persentase_beban.numeric' => 'Persentase beban harus berupa angka',
            'persentase_beban.between' => 'Persentase beban harus diantara 0 hingga 999.9',
            'persentase_beban.regex' => 'Sistem hanya menerima data Persentase beban dengan 1 angka desimal, contoh max input 999.9',
            'section_lbs.required' => 'Section lbs wajib diisi',
            'section_lbs.max' => 'Panjang karakter maksimal Section lbs adalah 30',
            'fasa.required' => 'Fasa wajib diisi',
            'fasa.integer' => 'Fasa harus berupa angka',
            'fasa.max' => 'Fasa maksimal adalah 127',
            'nilai_sdk_utama.required' => 'Nilai sdk utama wajib diisi',
            'nilai_sdk_utama.integer' => 'Nilai sdk utama harus berupa angka',
            'nilai_sdk_utama.max' => 'Nilai sdk utama maksimal adalah 32.767',
            'nilai_primer.required' => 'Nilai primer wajib diisi',
            'nilai_primer.numeric' => 'Nilai primer harus berupa angka',
            'nilai_primer.between' => 'Nilai primer harus diantara 0 hingga 99.9',
            'nilai_primer.regex' => 'Sistem hanya menerima data Nilai primer dengan 1 angka desimal, contoh max input 99.9',
            'tap_no.required' => 'Tap No wajib diisi',
            'tap_no.integer' => 'Tap No harus berupa angka',
            'tap_no.max' => 'Tap no maksimal adalah 127',
            'tap_kv.required' => 'Tap kv wajib diisi',
            'tap_kv.numeric' => 'Tap kv harus berupa angka',
            'tap_kv.between' => 'Tap kv harus diantara 0 hingga 99.9',
            'tap_kv.regex' => 'Sistem hanya menerima data Tap kv dengan 1 angka desimal, contoh max input 99.9',
            'rekondisi_preman.required' => 'Rekondisi/Preman wajib diisi',
            'rekondisi_preman.in' => 'Rekondisi/Preman hanya boleh di isi dengan: rek atau pre',
            'bengkel.required' => 'Bengkel wajib diisi',
            'bengkel.in' => 'Bengkel hanya boleh di isi dengan: wep atau mar',

            'merek_trafo_2.max' => 'Panjang karakter maksimal Merek trafo 2 adalah 30', //update baru
            'merek_trafo_3.max' => 'Panjang karakter maksimal Merek trafo 3 adalah 30',
            'no_seri_2.max' => 'Panjang karakter maksimal nomor seri trafo 2 adalah 20',
            'no_seri_3.max' => 'Panjang karakter maksimal nomor seri trafo 3 adalah 20',
            'tahun_2.regex' => 'Tahun trafo 2 harus menggunakan format (YYYY)',
            'tahun_3.regex' => 'Tahun trafo 3 harus menggunakan format (YYYY)',
            'berat_minyak_2.integer' => 'Berat minyak trafo 2 harus berupa angka',
            'berat_minyak_2.max' => 'Berat minyak maksimal trafo 2 adalah 32.767',
            'berat_minyak_3.integer' => 'Berat minyak trafo 3 harus berupa angka',
            'berat_minyak_3.max' => 'Berat minyak maksimal trafo 3 adalah 32.767',
            'berat_total_2.integer' => 'Berat total trafo 2 harus berupa angka',
            'berat_total_2.max' => 'Berat total maksimal trafo 2 adalah 32.767',
            'berat_total_3.integer' => 'Berat total trafo 3 harus berupa angka',
            'berat_total_3.max' => 'Berat total maksimal trafo 3 adalah 32.767',

            'keterangan.max' => 'Keterangan maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }
            return redirect()
                ->route('gardu.edit', $id) // ⚠️ PENTING: sertakan $id
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali input Anda.');
        }

        try {
            DB::beginTransaction();

            $fillable = (new DataGardu())->getFillable();
            $dataBaru = $request->only($fillable);

            // samakan format untuk membandingkan
            $dataBaru = array_map(fn($v) => is_string($v) ? trim($v) : $v, $dataBaru);
            $dataLama = $gardu->only(array_keys($dataBaru));

            // bandingkan sebagai string agar 10 vs "10" dianggap sama
            $perubahanUpdate = [];
            foreach ($dataBaru as $k => $v) {
                $lama = $dataLama[$k] ?? null;
                if ((string)$v !== (string)$lama) {
                    $perubahanUpdate[$k] = $v;
                }
            }

            if (empty($perubahanUpdate)) {
                DB::rollBack();

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada perubahan data, update dibatalkan'
                    ], 200);
                }
                return redirect()
                    ->route('gardu.edit', $id) // ⚠️ PENTING
                    ->with('info', 'Tidak ada perubahan data.');
            }

            $gardu->update($dataBaru);

            HistoryDataGardu::create([
                'id_data_gardu' => $gardu->id,
                'data_lama'     => json_encode($dataLama, JSON_UNESCAPED_UNICODE),
                'aksi'          => 'update',
                'diubah_oleh' => Auth::user()->name,
                'keterangan'    => $request->input('keterangan'),
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data gardu berhasil diupdate',
                    'data'    => $gardu
                ], 200);
            }

            // pilih salah satu:
            // 1) kembali ke index (rekomendasi, biar user lihat daftar)
            return redirect()->route('gardu.index')->with('success', 'Data gardu berhasil diupdate.');
            // 2) atau tetap di halaman edit:
            // return redirect()->route('gardu.edit', $id)->with('success', 'Data gardu berhasil diupdate.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate data',
                    'error'   => env('APP_DEBUG') ? $e->getMessage() : 'Terjadi kesalahan internal'
                ], 500);
            }

            return redirect()
                ->route('gardu.edit', $id) // ⚠️ PENTING
                ->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function show($id)
    {
        try {
            // Ambil data gardu beserta 1 history perubahan terbaru
            $gardu = DataGardu::with(['history' => function ($q) {
                $q->latest()->limit(1);
            }])->find($id);

            if (!$gardu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gardu tidak ditemukan'
                ], 404);
            }

            $dataSaatIni = $gardu->toArray();

            $dataYangDirubah = [];

            foreach ($gardu->history as $history) {
                $oldData = json_decode($history->data_lama, true);

                foreach ($oldData as $field => $oldValue) {
                    // Bandingkan data lama dengan data baru
                    if (!array_key_exists($field, $dataSaatIni)) {
                        continue;
                    }

                    $dataBaru = $dataSaatIni[$field];

                    // Jika ada perbedaan, tampilkan pada array riwayat perubahan
                    if ($oldValue != $dataBaru) {
                        $dataYangDirubah[$field][] = [
                            'data_lama' => $oldValue,
                            'data_baru' => $dataBaru,
                            'aksi' => $history->aksi,
                            'diubah_oleh' => $history->diubah_oleh,
                            'keterangan' => $history->keterangan,
                            'tanggal' => $history->created_at->toDateTimeString(),
                        ];
                    }
                }
            }
            return view('manajemen-data.gardu.show', compact('gardu'));

            return response()->json([
                'success' => true,
                'message' => 'Data gardu beserta history dan perubahan ditemukan',
                'data' => [
                    'data_saat_ini' => $dataSaatIni,
                    'detail_data_yang_dirubah' => $dataYangDirubah,
                ]
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gardu',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Terjadi kesalahan internal'
            ], 500);
        }
    }
    public function edit($id)
    {
        $gardu = DataGardu::find($id);

        if (! $gardu) {
            // jika dipanggil via browser (bukan JSON), arahkan balik
            if (!request()->wantsJson()) {
                return redirect()
                    ->route('gardu.index')
                    ->with('error', 'Data gardu tidak ditemukan.');
            }
            // kalau JSON:
            return response()->json([
                'success' => false,
                'message' => 'Data gardu tidak ditemukan'
            ], 404);
        }

        // gunakan view yang kamu punya (update.blade.php dipakai sebagai form edit)
        return view('manajemen-data.gardu.update', compact('gardu'));
    }


    public function destroy(Request $request, $id)
    {
        try {
            $gardu = DataGardu::find($id);

            if (!$gardu) {
                $msg = 'Data gardu tidak ditemukan.';
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 404);
                }
                return redirect()->route('gardu.index')->with('error', $msg);
            }

            $gardu->delete();

            $msg = 'Data gardu berhasil dihapus. History tetap tersimpan.';
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg], 200);
            }
            return redirect()->route('gardu.index')->with('success', $msg);
        } catch (QueryException $e) {
            // Misal gagal karena FK constraint
            $msg = 'Data tidak bisa dihapus karena masih terhubung dengan data lain.';
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg, 'error' => $e->getMessage()], 409);
            }
            return redirect()->route('gardu.index')->with('error', $msg);
        } catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan saat menghapus data gardu';
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'error'   => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Terjadi kesalahan internal'
                ], 500);
            }
            return redirect()->route('gardu.index')->with('error', $msg);
        }
    }
    public function historyIndex(Request $request)
    {
        // per page & filter
        $perPage = max(5, min((int)$request->get('per_page', 20), 100));
        $q = trim((string)$request->get('q', ''));

        // Rekap per gardu: 1 baris per id_data_gardu, ambil log terakhir + total log
        // step 1: subquery rekap
        $recapSub = HistoryDataGardu::select([
                'id_data_gardu',
                DB::raw('MAX(id) as last_id'),
                DB::raw('MAX(created_at) as last_at'),
                DB::raw('COUNT(*) as total_logs'),
            ])
            ->groupBy('id_data_gardu');

        // step 2: join ke history terakhir & data_gardu saat ini
        $query = DB::table(DB::raw("({$recapSub->toSql()}) as recaps"))
            ->mergeBindings($recapSub->getQuery()) // penting utk binding
            ->join('history_data_gardu as hlast', 'hlast.id', '=', 'recaps.last_id')
            ->leftJoin('data_gardu as dg', 'dg.id', '=', 'recaps.id_data_gardu')
            ->select([
                'recaps.id_data_gardu',
                'recaps.total_logs',
                'recaps.last_at',
                'hlast.aksi as last_aksi',
                'hlast.diubah_oleh as last_by',
                'hlast.keterangan as last_notes',
                'hlast.data_lama as last_snapshot',
                'dg.kd_gardu as kd_gardu_now',
                'dg.kd_pylg as kd_pylg_now',
                'dg.gardu_induk as gi_now',
                'dg.alamat as alamat_now',
                'dg.id as id_gardu', // <-- tambahkan ini
            ])
            ->orderByDesc('recaps.last_at');

        // Filter q (prioritas ke kd_gardu_now; fallback ke snapshot JSON)
        if ($q !== '') {
            $query->where(function($qq) use ($q) {
                $qq->where('dg.kd_gardu', 'like', "%{$q}%")
                ->orWhere('hlast.data_lama', 'like', '%"kd_gardu":"'.$q.'"%')
                ->orWhere('hlast.data_lama', 'like', '%"kd_gardu":'.$q.'%');
            });
        }

        $recaps = $query->paginate($perPage)->withQueryString();

        return view('manajemen-data.historis.history_gardu.recap_index', compact('recaps'));
    }

    /**
     * Halaman detail: riwayat update untuk satu gardu (id_data_gardu)
     */
    public function historyShow(Request $request, $idDataGardu)
    {
        $perPage = max(5, min((int)$request->get('per_page', 20), 100));

        // Ambil data gardu saat ini (kalau ada)
        $gardu = DataGardu::find($idDataGardu);

        // Ambil semua log untuk id_data_gardu ini
        $histories = HistoryDataGardu::where('id_data_gardu', $idDataGardu)
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('manajemen-data.historis.history_gardu.detail_by_gardu', [
            'gardu'     => $gardu,
            'histories' => $histories,
            'idDataGardu' => $idDataGardu,
        ]);
    }
    public function qr(Request $request)
{
    // opsi: bisa prefill dari ?kd=... saat masuk ke halaman
    $prefillKd = trim((string) $request->query('kd', ''));
    return view('manajemen-data.detail.qr', compact('prefillKd'));
}

public function findByKode(Request $request)
{
    // validasi sederhana
    $request->validate([
        'kd_gardu' => 'required|string|max:10'
    ]);

    // kd_gardu unik (sesuai rules store/update Anda)
    $kode = trim($request->kd_gardu);

    // pilih kolom yang dipakai di Blade supaya rapi
    $columns = [
        'id',
        'gardu_induk',
        'kd_trf_gi',
        'kd_pylg',
        'kd_gardu',
        'daya_trafo',
        'jml_trafo',
        'alamat',
        'desa',
        'no_seri',
        'berat_total',
        'berat_minyak',
        'hubungan',
        'impedansi',
        'tegangan_tm',
        'tegangan_tr',
        'frekuensi',
        'tahun',
        'merek_trafo',
        'beban_kva_trafo',
        'persentase_beban',
        'section_lbs',
        'fasa',
        'nilai_sdk_utama',
        'nilai_primer',
        'tap_no',
        'tap_kv',
        'rekondisi_preman',
        'bengkel',
    ];

    $gardu = DataGardu::query()
        ->select($columns)
        ->where('kd_gardu', $kode)     // exact match; QR diisi kd_gardu persis
        ->first();

    if (!$gardu) {
        return response()->json(['status' => 'not_found'], 200);
    }

    // kirim data apa adanya; Blade akan baca field-field ini
    return response()->json([
        'status' => 'ok',
        'data'   => $gardu->toArray(),
    ], 200);
}

}
