<?php

namespace App\Http\Controllers\Admin\ManajemenData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryData\HistoryDataOmtPengukuran;
use App\Models\ManajemenData\OmtPengukuran;
use App\Models\ManajemenData\DataGardu; // <-- TAMBAHKAN BARIS INI
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OmtPengukuranController extends Controller
{

    public function create($kd_gardu)
    {
        $gardu = DataGardu::where('kd_gardu', $kd_gardu)->firstOrFail();

        // ambil data pengukuran EXISTING (jika ada) untuk kd_gardu ini
        $pengukuran = \App\Models\ManajemenData\OmtPengukuran::where('kd_gardu', $kd_gardu)->first();

        // view pakai path yang kamu gunakan
        return view('manajemen-data.omt-pengukuran.pengukuran', compact('gardu', 'pengukuran'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kd_gardu' => 'required|string|max:10|exists:data_gardu,kd_gardu|unique:omt_pengukuran,kd_gardu',
            // 'kd_gardu' => 'required|string|max:10|exists:data_gardu,kd_gardu',
            'ian' => 'required|integer|max:32767',
            'iar' => 'required|integer|max:32767',
            'ias' => 'required|integer|max:32767',
            'iat' => 'required|integer|max:32767',
            'ibn' => 'required|integer|max:32767',
            'ibr' => 'required|integer|max:32767',
            'ibs' => 'required|integer|max:32767',
            'ibt' => 'required|integer|max:32767',
            'icn' => 'required|integer|max:32767',
            'icr' => 'required|integer|max:32767',
            'ics' => 'required|integer|max:32767',
            'ict' => 'required|integer|max:32767',
            'idn' => 'required|integer|max:32767',
            'idr' => 'required|integer|max:32767',
            'ids' => 'required|integer|max:32767',
            'idt' => 'required|integer|max:32767',
            'vrn' => 'required|integer|max:32767',
            'vrs' => 'required|integer|max:32767',
            'vsn' => 'required|integer|max:32767',
            'vst' => 'required|integer|max:32767',
            'vtn' => 'required|integer|max:32767',
            'vtr' => 'required|integer|max:32767',
            'waktu_pengukuran' => 'required|date_format:Y-m-d\TH:i',

            'iun' => 'required|integer|max:32767', //update baru
            'iur' => 'required|integer|max:32767',
            'ius' => 'required|integer|max:32767',
            'iut' => 'required|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan_history' => 'nullable|string|max:20',
        ], [
            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.unique' => 'Kode gardu sudah terdaftar pada sistem',
            'kd_gardu.max' => 'Panjang Kode gardu maksimal 10 karakter',
            'kd_gardu.exists'   => 'Kode gardu tidak ditemukan pada data master',
            'ian.required' => 'IAN wajib diisi',
            'ian.integer' => 'IAN harus berupa angka',
            'ian.max' => 'IAN maksimal 32.767',
            'iar.required' => 'IAR wajib diisi',
            'iar.integer' => 'IAR harus berupa angka',
            'iar.max' => 'IAR maksimal 32.767',
            'ias.required' => 'IAS wajib diisi',
            'ias.integer' => 'IAS harus berupa angka',
            'ias.max' => 'IAS maksimal 32.767',
            'iat.required' => 'IAT wajib diisi',
            'iat.integer' => 'IAT harus berupa angka',
            'iat.max' => 'IAT maksimal 32.767',
            'ibn.required' => 'IBN wajib diisi',
            'ibn.integer' => 'IBN harus berupa angka',
            'ibn.max' => 'IBN maksimal 32.767',
            'ibr.required' => 'IBR wajib diisi',
            'ibr.integer' => 'IBR harus berupa angka',
            'ibr.max' => 'IBR maksimal 32.767',
            'ibs.required' => 'IBS wajib diisi',
            'ibs.integer' => 'IBS harus berupa angka',
            'ibs.max' => 'IBS maksimal 32.767',
            'ibt.required' => 'IBT wajib diisi',
            'ibt.integer' => 'IBT harus berupa angka',
            'ibt.max' => 'IBT maksimal 32.767',
            'icn.required' => 'ICN wajib diisi',
            'icn.integer' => 'ICN harus berupa angka',
            'icn.max' => 'ICN maksimal 32.767',
            'icr.required' => 'ICR wajib diisi',
            'icr.integer' => 'ICR harus berupa angka',
            'icr.max' => 'ICR maksimal 32.767',
            'ics.required' => 'ICS wajib diisi',
            'ics.integer' => 'ICS harus berupa angka',
            'ics.max' => 'ICS maksimal 32.767',
            'ict.required' => 'ICT wajib diisi',
            'ict.integer' => 'ICT harus berupa angka',
            'ict.max' => 'ICT maksimal 32.767',
            'idn.required' => 'IDN wajib diisi',
            'idn.integer' => 'IDN harus berupa angka',
            'idn.max' => 'IDN maksimal 32.767',
            'idr.required' => 'IDR wajib diisi',
            'idr.integer' => 'IDR harus berupa angka',
            'idr.max' => 'IDR maksimal 32.767',
            'ids.required' => 'IDS wajib diisi',
            'ids.integer' => 'IDS harus berupa angka',
            'ids.max' => 'IDS maksimal 32.767',
            'idt.required' => 'IDT wajib diisi',
            'idt.integer' => 'IDT harus berupa angka',
            'idt.max' => 'IDT maksimal 32.767',
            'vrn.required' => 'VRN wajib diisi',
            'vrn.integer' => 'VRN harus berupa angka',
            'vrn.max' => 'VRN maksimal 32.767',
            'vrs.required' => 'VRS wajib diisi',
            'vrs.integer' => 'VRS harus berupa angka',
            'vrs.max' => 'VRS maksimal 32.767',
            'vsn.required' => 'VSN wajib diisi',
            'vsn.integer' => 'VSN harus berupa angka',
            'vsn.max' => 'VSN maksimal 32.767',
            'vst.required' => 'VST wajib diisi',
            'vst.integer' => 'VST harus berupa angka',
            'vst.max' => 'VST maksimal 32.767',
            'vtn.required' => 'VTN wajib diisi',
            'vtn.integer' => 'VTN harus berupa angka',
            'vtn.max' => 'VTN maksimal 32.767',
            'vtr.required' => 'VTR wajib diisi',
            'vtr.integer' => 'VTR harus berupa angka',
            'vtr.max' => 'VTR maksimal 32.767',
            'diubah_oleh.required' => 'Nama petugas wajib diisi',
            'waktu_pengukuran.required' => 'Waktu pengukuran wajib diisi',
            'waktu_pengukuran.date_format' => 'Format waktu pengukuran harus YYYY-MM-DD HH:MM',

            'iun.integer' => 'IUN harus berupa angka',
            'iun.max' => 'IUN maksimal 32.767',
            'iur.integer' => 'IUR harus berupa angka',
            'iur.max' => 'IUR maksimal 32.767',
            'ius.integer' => 'IUS harus berupa angka',
            'ius.max' => 'IUS maksimal 32.767',
            'iut.integer' => 'IUT harus berupa angka',
            'iut.max' => 'IUT maksimal 32.767',

            'keterangan_history.max' => 'Keterangan history maksimal 20 karakter',
        ]);


        if ($validator->fails()) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }
        return back()->withErrors($validator)->withInput();
    }

    try {
        DB::beginTransaction();

        // Ambil field yang diizinkan + normalisasi & set operator
        $dataBaru = $request->only((new OmtPengukuran())->getFillable());

        // Normalisasi dari "YYYY-MM-DDTHH:ii" -> "YYYY-MM-DD HH:ii:ss"
        $dataBaru['waktu_pengukuran'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->waktu_pengukuran)
                                              ->format('Y-m-d H:i:s');

        // Set diubah_oleh dari user login (override kalau tak ada di request)
        $dataBaru['diubah_oleh'] = auth()->user()->name ?? 'system';

        // Simpan utama
        $pengukuran = OmtPengukuran::create($dataBaru);

        // Hitung beban & persentase
        $bebanKvaTrafo = ($request->iur * $request->vrn)
                       + ($request->ius * $request->vsn)
                       + ($request->iut * $request->vtn);

        $gardu = DataGardu::where('kd_gardu', $dataBaru['kd_gardu'])->first();
        $persentaseBeban = 0;
        if ($gardu && $gardu->daya_trafo > 0) {
            $persentaseBeban = round(($bebanKvaTrafo / $gardu->daya_trafo) * 100, 1);
        }

        // Validasi hasil hitung — JANGAN return back() di sini
        $validasiHasil = Validator::make([
            'beban_kva_trafo'  => $bebanKvaTrafo,
            'persentase_beban' => $persentaseBeban
        ], [
            'beban_kva_trafo'  => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
            'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
        ], [
            // pesan2mu sama seperti sebelumnya...
        ]);
        if ($validasiHasil->fails()) {
            // lempar agar ketangkap dan di-rollback rapi
            throw ValidationException::withMessages($validasiHasil->errors()->toArray());
        }

        // Update ringkasan ke data_gardu
        DataGardu::where('kd_gardu', $dataBaru['kd_gardu'])->update([
            'beban_kva_trafo'  => $bebanKvaTrafo,
            'persentase_beban' => $persentaseBeban,
        ]);

        // History: simpan data_baru dari MODEL (sudah "spasi", bukan yang ada 'T')
        HistoryDataOmtPengukuran::create([
            'id_omt_pengukuran' => $pengukuran->id,
            'data_lama'         => json_encode([], JSON_UNESCAPED_UNICODE),
            'data_baru'         => json_encode($pengukuran->only(array_keys($dataBaru)), JSON_UNESCAPED_UNICODE),
            'aksi'              => 'create',
            'diubah_oleh'       => $dataBaru['diubah_oleh'],
            'keterangan'        => $request->input('keterangan_history'),
        ]);

        DB::commit();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data OMT pengukuran berhasil ditambahkan',
                'data'    => $pengukuran
            ], 201);
        }

        return redirect()
            ->route('omt-pengukuran.create', ['kd_gardu' => $pengukuran->kd_gardu])
            ->with('success', 'Data OMT pengukuran berhasil ditambahkan');

    } catch (ValidationException $e) {
        DB::rollBack();
        return back()->withErrors($e->errors())->withInput();
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('OMT Store Error', ['exception' => $e]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
        return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
    }
    }

    public function update(Request $request, $id)
    {
        $pengukuran = OmtPengukuran::find($id);

        if (!$pengukuran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengukuran untuk gardu ini belum ada, silakan buat baru dulu.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kd_gardu' => "required|string|max:10|exists:data_gardu,kd_gardu|unique:omt_pengukuran,kd_gardu,{$id},id",
            'ian' => 'required|integer|max:32767',
            'iar' => 'required|integer|max:32767',
            'ias' => 'required|integer|max:32767',
            'iat' => 'required|integer|max:32767',
            'ibn' => 'required|integer|max:32767',
            'ibr' => 'required|integer|max:32767',
            'ibs' => 'required|integer|max:32767',
            'ibt' => 'required|integer|max:32767',
            'icn' => 'required|integer|max:32767',
            'icr' => 'required|integer|max:32767',
            'ics' => 'required|integer|max:32767',
            'ict' => 'required|integer|max:32767',
            'idn' => 'required|integer|max:32767',
            'idr' => 'required|integer|max:32767',
            'ids' => 'required|integer|max:32767',
            'idt' => 'required|integer|max:32767',
            'vrn' => 'required|integer|max:32767',
            'vrs' => 'required|integer|max:32767',
            'vsn' => 'required|integer|max:32767',
            'vst' => 'required|integer|max:32767',
            'vtn' => 'required|integer|max:32767',
            'vtr' => 'required|integer|max:32767',
            'waktu_pengukuran' => 'required|date_format:Y-m-d\TH:i',

            'iun' => 'required|integer|max:32767', //update baru
            'iur' => 'required|integer|max:32767',
            'ius' => 'required|integer|max:32767',
            'iut' => 'required|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan_history' => 'nullable|string|max:20',
        ], [
            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.unique' => 'Kode gardu sudah terdaftar pada sistem',
            'kd_gardu.max' => 'Panjang Kode gardu maksimal 10 karakter',
            'kd_gardu.exists'   => 'Kode gardu tidak ditemukan pada data master',
            'ian.required' => 'IAN wajib diisi',
            'ian.integer' => 'IAN harus berupa angka',
            'ian.max' => 'IAN maksimal 32.767',
            'iar.required' => 'IAR wajib diisi',
            'iar.integer' => 'IAR harus berupa angka',
            'iar.max' => 'IAR maksimal 32.767',
            'ias.required' => 'IAS wajib diisi',
            'ias.integer' => 'IAS harus berupa angka',
            'ias.max' => 'IAS maksimal 32.767',
            'iat.required' => 'IAT wajib diisi',
            'iat.integer' => 'IAT harus berupa angka',
            'iat.max' => 'IAT maksimal 32.767',
            'ibn.required' => 'IBN wajib diisi',
            'ibn.integer' => 'IBN harus berupa angka',
            'ibn.max' => 'IBN maksimal 32.767',
            'ibr.required' => 'IBR wajib diisi',
            'ibr.integer' => 'IBR harus berupa angka',
            'ibr.max' => 'IBR maksimal 32.767',
            'ibs.required' => 'IBS wajib diisi',
            'ibs.integer' => 'IBS harus berupa angka',
            'ibs.max' => 'IBS maksimal 32.767',
            'ibt.required' => 'IBT wajib diisi',
            'ibt.integer' => 'IBT harus berupa angka',
            'ibt.max' => 'IBT maksimal 32.767',
            'icn.required' => 'ICN wajib diisi',
            'icn.integer' => 'ICN harus berupa angka',
            'icn.max' => 'ICN maksimal 32.767',
            'icr.required' => 'ICR wajib diisi',
            'icr.integer' => 'ICR harus berupa angka',
            'icr.max' => 'ICR maksimal 32.767',
            'ics.required' => 'ICS wajib diisi',
            'ics.integer' => 'ICS harus berupa angka',
            'ics.max' => 'ICS maksimal 32.767',
            'ict.required' => 'ICT wajib diisi',
            'ict.integer' => 'ICT harus berupa angka',
            'ict.max' => 'ICT maksimal 32.767',
            'idn.required' => 'IDN wajib diisi',
            'idn.integer' => 'IDN harus berupa angka',
            'idn.max' => 'IDN maksimal 32.767',
            'idr.required' => 'IDR wajib diisi',
            'idr.integer' => 'IDR harus berupa angka',
            'idr.max' => 'IDR maksimal 32.767',
            'ids.required' => 'IDS wajib diisi',
            'ids.integer' => 'IDS harus berupa angka',
            'ids.max' => 'IDS maksimal 32.767',
            'idt.required' => 'IDT wajib diisi',
            'idt.integer' => 'IDT harus berupa angka',
            'idt.max' => 'IDT maksimal 32.767',
            'vrn.required' => 'VRN wajib diisi',
            'vrn.integer' => 'VRN harus berupa angka',
            'vrn.max' => 'VRN maksimal 32.767',
            'vrs.required' => 'VRS wajib diisi',
            'vrs.integer' => 'VRS harus berupa angka',
            'vrs.max' => 'VRS maksimal 32.767',
            'vsn.required' => 'VSN wajib diisi',
            'vsn.integer' => 'VSN harus berupa angka',
            'vsn.max' => 'VSN maksimal 32.767',
            'vst.required' => 'VST wajib diisi',
            'vst.integer' => 'VST harus berupa angka',
            'vst.max' => 'VST maksimal 32.767',
            'vtn.required' => 'VTN wajib diisi',
            'vtn.integer' => 'VTN harus berupa angka',
            'vtn.max' => 'VTN maksimal 32.767',
            'vtr.required' => 'VTR wajib diisi',
            'vtr.integer' => 'VTR harus berupa angka',
            'vtr.max' => 'VTR maksimal 32.767',
            'diubah_oleh.required' => 'Nama petugas wajib diisi',
            'waktu_pengukuran.required' => 'Waktu pengukuran wajib diisi',
            'waktu_pengukuran.date_format' => 'Format waktu pengukuran harus YYYY-MM-DD HH:MM',

            'iun.integer' => 'IUN harus berupa angka',
            'iun.max' => 'IUN maksimal 32.767',
            'iur.integer' => 'IUR harus berupa angka',
            'iur.max' => 'IUR maksimal 32.767',
            'ius.integer' => 'IUS harus berupa angka',
            'ius.max' => 'IUS maksimal 32.767',
            'iut.integer' => 'IUT harus berupa angka',
            'iut.max' => 'IUT maksimal 32.767',

            'keterangan_history.max' => 'Keterangan history maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }
        return back()->withErrors($validator)->withInput();
    }


// kalau mau tetap pakai Log tanpa use, gunakan \Log::error(...) di catch

try {
    DB::beginTransaction();

    // Hanya kolom yang ada di tabel (sesuai $fillable)
    $dataBaru = $request->only((new OmtPengukuran())->getFillable());

    // Normalisasi datetime: dari "2025-10-05T12:34" -> "2025-10-05 12:34:00"
    $dataBaru['waktu_pengukuran'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->waktu_pengukuran)
        ->format('Y-m-d H:i:s');

    // Set diubah_oleh dari user login (override input form)
    $dataBaru['diubah_oleh'] = auth()->user()->name ?? 'system';

    // Hitung beban & persentase (disimpan ke data_gardu, bukan ke omt_pengukuran)
    $bebanKvaTrafo = ($request->iur * $request->vrn)
        + ($request->ius * $request->vsn)
        + ($request->iut * $request->vtn);

    $gardu = DataGardu::where('kd_gardu', $request->kd_gardu)->first();
    $persentaseBeban = 0;
    if ($gardu && $gardu->daya_trafo > 0) {
        $persentaseBeban = round(($bebanKvaTrafo / $gardu->daya_trafo) * 100, 1);
    }

    // Validasi angka hasil (opsional – tetap seperti punyamu)
    $validasiHasil = Validator::make([
        'beban_kva_trafo'  => $bebanKvaTrafo,
        'persentase_beban' => $persentaseBeban
    ], [
        'beban_kva_trafo'  => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
        'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
    ]);
    if ($validasiHasil->fails()) {
        return back()->withErrors($validasiHasil)->withInput();
    }

    // Update hasil hitung ke data_gardu (ikut transaksi, akan di-rollback jika tidak ada perubahan)
    DataGardu::where('kd_gardu', $request->kd_gardu)->update([
        'beban_kva_trafo'  => $bebanKvaTrafo,
        'persentase_beban' => $persentaseBeban,
    ]);

    // Ambil data lama utk pembanding
    $dataLama = $pengukuran->only(array_keys($dataBaru));

    // Bandingkan tanpa 'diubah_oleh'
    $keysCompare = array_diff(array_keys($dataBaru), ['diubah_oleh']);
    $filterByKeys = function(array $arr, array $keys) {
        return array_intersect_key($arr, array_flip($keys));
    };
    $perubahanUpdate = array_diff_assoc(
        $filterByKeys($dataBaru, $keysCompare),
        $filterByKeys($dataLama, $keysCompare)
    );

    if (empty($perubahanUpdate)) {
        DB::rollBack();
        return back()->with('info', 'Tidak ada perubahan data, update dibatalkan.');
    }

    // Lanjut update ke omt_pengukuran
    $pengukuran->update($dataBaru);

    // Simpan history
    HistoryDataOmtPengukuran::create([
        'id_omt_pengukuran' => $pengukuran->id,
        'data_lama'         => json_encode($dataLama, JSON_UNESCAPED_UNICODE),
        'data_baru'         => json_encode($pengukuran->only(array_keys($dataBaru)), JSON_UNESCAPED_UNICODE),
        'aksi'              => 'update',
        'diubah_oleh'       => $dataBaru['diubah_oleh'],
        'keterangan'        => $request->input('keterangan_history'),
    ]);

    DB::commit();

    return redirect()
        ->route('omt-pengukuran.create', ['kd_gardu' => $pengukuran->kd_gardu])
        ->with('success', 'OMT pengukuran berhasil diperbarui.');
} catch (\Throwable $e) {
    \Log::error('OMT Update Error', ['msg' => $e->getMessage()]);
    DB::rollBack();
    return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
}
    }

    public function show($id)
    {
        try {
            // Ambil data omt pengukuran beserta 1 history perubahan terbaru
            $pengukuran = OmtPengukuran::with(['history' => function ($q) {
                $q->latest()->limit(1);
            }])->find($id);

            if (!$pengukuran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data omt pengukuran tidak ditemukan'
                ], 404);
            }

            $dataSaatIni = $pengukuran->toArray();

            $dataYangDirubah = [];

            foreach ($pengukuran->history as $history) {
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

    public function destroy($id)
    {
        try {
            //hapus data pengukuran tanpa menghapus data history
            $pengukuran = OmtPengukuran::find($id);

            if (!$pengukuran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data omt pengukuran tidak ditemukan'
                ], 404);
            }

            $pengukuran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data omt pengukuran berhasil dihapus. History tetap tersimpan.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data omt pengukuran',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Terjadi kesalahan internal'
            ], 500);
        }
    }
    public function historyIndex(Request $request)
{
    $perPage = max(5, min((int)$request->get('per_page', 20), 100));
    $q = trim((string)$request->get('q', ''));

    // pakai nama tabel dari model (anti-typo)
    $histTable = (new HistoryDataOmtPengukuran())->getTable(); // biasanya: history_omt_pengukuran
    $omtTable  = (new OmtPengukuran())->getTable();            // biasanya: omt_pengukuran
    $dgTable   = (new DataGardu())->getTable();                 // biasanya: data_gardu

    // subquery rekap (1 baris per id_omt_pengukuran)
    $recapSub = HistoryDataOmtPengukuran::select([
            'id_omt_pengukuran',
            DB::raw('MAX(id) as last_id'),
            DB::raw('MAX(created_at) as last_at'),
            DB::raw('COUNT(*) as total_logs'),
        ])
        ->groupBy('id_omt_pengukuran');

    // FROM (subquery) + JOIN ke tabel yang sama untuk ambil baris terakhir (hlast)
    $query = DB::query()
        ->fromSub($recapSub, 'recaps')
        ->join("$histTable as hlast", 'hlast.id', '=', 'recaps.last_id')
        ->leftJoin("$omtTable as omt", 'omt.id', '=', 'recaps.id_omt_pengukuran')
        ->leftJoin("$dgTable as dg", 'dg.kd_gardu', '=', 'omt.kd_gardu')
        ->select([
            'recaps.id_omt_pengukuran',
            'recaps.total_logs',
            'recaps.last_at',
            'hlast.aksi as last_aksi',
            'hlast.diubah_oleh as last_by',
            'hlast.keterangan as last_notes',
            'hlast.data_lama as last_snapshot',
            'omt.kd_gardu as kd_gardu_now',
            'omt.waktu_pengukuran as waktu_pengukuran_now',
            'dg.kd_pylg as kd_pylg_now',
            'dg.alamat as alamat_now',
            'dg.gardu_induk as gi_now',
        ])
        ->orderByDesc('recaps.last_at');

    if ($q !== '') {
        $query->where(function ($qq) use ($q, $histTable, $omtTable) {
            $qq->where("omt.kd_gardu", 'like', "%{$q}%")
               ->orWhere("hlast.data_lama", 'like', '%"kd_gardu":"'.$q.'"%')
               ->orWhere("hlast.data_lama", 'like', '%"kd_gardu":'.$q.'%');
        });
    }

    $recaps = $query->paginate($perPage)->withQueryString();

    return view('manajemen-data.historis.history_omt_pengkuruan.recap_index', compact('recaps'));
}

    public function historyShow(Request $request, $idOmt)
    {
        $perPage = max(5, min((int)$request->get('per_page', 20), 100));

        $omt = OmtPengukuran::find($idOmt);

        $histories = HistoryDataOmtPengukuran::where('id_omt_pengukuran', $idOmt)
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('manajemen-data.historis.history_omt_pengkuruan.detail_by_omt', [
            'omt'       => $omt,
            'histories' => $histories,
            'idOmt'     => $idOmt,
        ]);
    }
}
