<?php

namespace App\Http\Controllers\Admin\ManajemenData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryData\HistoryDataOmtPengukuran;
use App\Models\ManajemenData\OmtPengukuran;
use App\Models\ManajemenData\DataGardu; // <-- TAMBAHKAN BARIS INI
use App\Models\HistoryData\HistoryDataGardu;
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
            'ian' => 'nullable|integer|max:32767',
            'iar' => 'nullable|integer|max:32767',
            'ias' => 'nullable|integer|max:32767',
            'iat' => 'nullable|integer|max:32767',
            'ibn' => 'nullable|integer|max:32767',
            'ibr' => 'nullable|integer|max:32767',
            'ibs' => 'nullable|integer|max:32767',
            'ibt' => 'nullable|integer|max:32767',
            'icn' => 'nullable|integer|max:32767',
            'icr' => 'nullable|integer|max:32767',
            'ics' => 'nullable|integer|max:32767',
            'ict' => 'nullable|integer|max:32767',
            'idn' => 'nullable|integer|max:32767',
            'idr' => 'nullable|integer|max:32767',
            'ids' => 'nullable|integer|max:32767',
            'idt' => 'nullable|integer|max:32767',
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
            'ian.integer' => 'IAN harus berupa angka',
            'ian.max' => 'IAN maksimal 32.767',
            'iar.integer' => 'IAR harus berupa angka',
            'iar.max' => 'IAR maksimal 32.767',
            'ias.integer' => 'IAS harus berupa angka',
            'ias.max' => 'IAS maksimal 32.767',
            'iat.integer' => 'IAT harus berupa angka',
            'iat.max' => 'IAT maksimal 32.767',
            'ibn.integer' => 'IBN harus berupa angka',
            'ibn.max' => 'IBN maksimal 32.767',
            'ibr.integer' => 'IBR harus berupa angka',
            'ibr.max' => 'IBR maksimal 32.767',
            'ibs.integer' => 'IBS harus berupa angka',
            'ibs.max' => 'IBS maksimal 32.767',
            'ibt.integer' => 'IBT harus berupa angka',
            'ibt.max' => 'IBT maksimal 32.767',
            'icn.integer' => 'ICN harus berupa angka',
            'icn.max' => 'ICN maksimal 32.767',
            'icr.integer' => 'ICR harus berupa angka',
            'icr.max' => 'ICR maksimal 32.767',
            'ics.integer' => 'ICS harus berupa angka',
            'ics.max' => 'ICS maksimal 32.767',
            'ict.integer' => 'ICT harus berupa angka',
            'ict.max' => 'ICT maksimal 32.767',
            'idn.integer' => 'IDN harus berupa angka',
            'idn.max' => 'IDN maksimal 32.767',
            'idr.integer' => 'IDR harus berupa angka',
            'idr.max' => 'IDR maksimal 32.767',
            'ids.integer' => 'IDS harus berupa angka',
            'ids.max' => 'IDS maksimal 32.767',
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

            $dataBaru = $request->only((new OmtPengukuran())->getFillable());
            $dataBaru['waktu_pengukuran'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->waktu_pengukuran)
                ->format('Y-m-d H:i:s');

            //apabila field ini dikosongi maka sistem otomatis mengisi menjadi 0
            $kolomAngka = [
                'ian',
                'iar',
                'ias',
                'iat',
                'ibn',
                'ibr',
                'ibs',
                'ibt',
                'icn',
                'icr',
                'ics',
                'ict',
                'idn',
                'idr',
                'ids',
                'idt'
            ];

            foreach ($kolomAngka as $kolom) {
                $dataBaru[$kolom] = $request->input($kolom, 0) ?? 0;
            }

            $pengukuran = OmtPengukuran::create($dataBaru);

            // === Hitung beban dan persentase
            $bebanKvaTrafo = round((($request->iur * $request->vrn) + ($request->ius * $request->vsn) + ($request->iut * $request->vtn)) / 1000, 1);

            $gardu = DataGardu::where('kd_gardu', $dataBaru['kd_gardu'])->first();
            $persentaseBeban = 0;
            if ($gardu && $gardu->daya_trafo > 0) {
                $persentaseBeban = round(($bebanKvaTrafo / $gardu->daya_trafo) * 100, 1);
            }

            // Validasi hasil hitung
            $validasiHasil = Validator::make([
                'beban_kva_trafo'  => $bebanKvaTrafo,
                'persentase_beban' => $persentaseBeban
            ], [
                'beban_kva_trafo'  => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
                'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
            ], [
                'persentase_beban.numeric' => 'Persentase beban harus berupa angka',
                'persentase_beban.between' => 'Sistem secara otomatis menghitung persentase beban dengan rumus = beban kva trafo / daya trafo x 100. input anda melebihi batas max input 999.9',
                'persentase_beban.regex'   => 'Sistem hanya menerima data Persentase beban dengan 1 angka desimal, contoh max input 999.9',
                'beban_kva_trafo.numeric'  => 'Beban KVA trafo harus berupa angka',
                'beban_kva_trafo.between'  => 'Sistem secara otomatis menghitung beban kva trafo dengan rumus = (iur*vrn) + (ius*vsn) + (iut*vtn). input anda melebihi batas max input 9999.9',
                'beban_kva_trafo.regex'    => 'Sistem hanya menerima data Beban KVA trafo dengan 1 angka desimal, contoh max input 9999.9',
            ]);
            if ($validasiHasil->fails()) {
                throw ValidationException::withMessages($validasiHasil->errors()->toArray());
            }

            //simpan history full pada tabel history data gardu walau hanya 2 field yang berubah beban_kva_trafo dan persentase_beban
            if ($gardu) {
                $dataLamaFull = $gardu->toArray();
                $gardu->update([
                    'beban_kva_trafo'  => $bebanKvaTrafo,
                    'persentase_beban' => $persentaseBeban,
                ]);
                $dataBaruFull = $gardu->fresh()->toArray();
                $fieldsUntukBanding = ['beban_kva_trafo', 'persentase_beban'];
                $dataLamaBanding = array_intersect_key($dataLamaFull, array_flip($fieldsUntukBanding));
                $dataBaruBanding = array_intersect_key($dataBaruFull, array_flip($fieldsUntukBanding));

                if ($dataLamaBanding !== $dataBaruBanding) {
                    HistoryDataGardu::create([
                        'id_data_gardu' => $gardu->id,
                        'data_lama' => json_encode($dataLamaFull, JSON_UNESCAPED_UNICODE),
                        'data_baru' => json_encode($dataBaruFull, JSON_UNESCAPED_UNICODE),
                        'aksi' => 'update',
                        'diubah_oleh' => Auth::user()->name ?? 'Sistem (otomatis)',
                        'keterangan' => 'Pembaruan beban dari hasil pengukuran',
                    ]);
                }
            }

            // simpan history pengukuran
            HistoryDataOmtPengukuran::create([
                'id_omt_pengukuran' => $pengukuran->id,
                'data_lama'         => json_encode([], JSON_UNESCAPED_UNICODE),
                'data_baru'         => json_encode($pengukuran->only(array_keys($dataBaru)), JSON_UNESCAPED_UNICODE),
                'aksi'              => 'create',
                'diubah_oleh'       => Auth::user()->name,
                'keterangan'        => $request->input('keterangan_history'),
            ]);

            DB::commit();

            return redirect()
                ->route('omt-pengukuran.create', ['kd_gardu' => $pengukuran->kd_gardu])
                ->with('success', 'Data OMT pengukuran berhasil ditambahkan');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('OMT Store Error', ['exception' => $e]);
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
            'ian' => 'nullable|integer|max:32767',
            'iar' => 'nullable|integer|max:32767',
            'ias' => 'nullable|integer|max:32767',
            'iat' => 'nullable|integer|max:32767',
            'ibn' => 'nullable|integer|max:32767',
            'ibr' => 'nullable|integer|max:32767',
            'ibs' => 'nullable|integer|max:32767',
            'ibt' => 'nullable|integer|max:32767',
            'icn' => 'nullable|integer|max:32767',
            'icr' => 'nullable|integer|max:32767',
            'ics' => 'nullable|integer|max:32767',
            'ict' => 'nullable|integer|max:32767',
            'idn' => 'nullable|integer|max:32767',
            'idr' => 'nullable|integer|max:32767',
            'ids' => 'nullable|integer|max:32767',
            'idt' => 'nullable|integer|max:32767',
            'vrn' => 'required|integer|max:32767',
            'vrs' => 'required|integer|max:32767',
            'vsn' => 'required|integer|max:32767',
            'vst' => 'required|integer|max:32767',
            'vtn' => 'required|integer|max:32767',
            'vtr' => 'required|integer|max:32767',
            'waktu_pengukuran' => 'required|date_format:Y-m-d\TH:i',

            'iun' => 'required|integer|max:32767',
            'iur' => 'required|integer|max:32767',
            'ius' => 'required|integer|max:32767',
            'iut' => 'required|integer|max:32767',

            'keterangan_history' => 'nullable|string|max:20',
        ], [
            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.unique' => 'Kode gardu sudah terdaftar pada sistem',
            'kd_gardu.max' => 'Panjang Kode gardu maksimal 10 karakter',
            'kd_gardu.exists'   => 'Kode gardu tidak ditemukan pada data master',
            'ian.integer' => 'IAN harus berupa angka',
            'ian.max' => 'IAN maksimal 32.767',
            'iar.integer' => 'IAR harus berupa angka',
            'iar.max' => 'IAR maksimal 32.767',
            'ias.integer' => 'IAS harus berupa angka',
            'ias.max' => 'IAS maksimal 32.767',
            'iat.integer' => 'IAT harus berupa angka',
            'iat.max' => 'IAT maksimal 32.767',
            'ibn.integer' => 'IBN harus berupa angka',
            'ibn.max' => 'IBN maksimal 32.767',
            'ibr.integer' => 'IBR harus berupa angka',
            'ibr.max' => 'IBR maksimal 32.767',
            'ibs.integer' => 'IBS harus berupa angka',
            'ibs.max' => 'IBS maksimal 32.767',
            'ibt.integer' => 'IBT harus berupa angka',
            'ibt.max' => 'IBT maksimal 32.767',
            'icn.integer' => 'ICN harus berupa angka',
            'icn.max' => 'ICN maksimal 32.767',
            'icr.integer' => 'ICR harus berupa angka',
            'icr.max' => 'ICR maksimal 32.767',
            'ics.integer' => 'ICS harus berupa angka',
            'ics.max' => 'ICS maksimal 32.767',
            'ict.integer' => 'ICT harus berupa angka',
            'ict.max' => 'ICT maksimal 32.767',
            'idn.integer' => 'IDN harus berupa angka',
            'idn.max' => 'IDN maksimal 32.767',
            'idr.integer' => 'IDR harus berupa angka',
            'idr.max' => 'IDR maksimal 32.767',
            'ids.integer' => 'IDS harus berupa angka',
            'ids.max' => 'IDS maksimal 32.767',
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

        try {
            DB::beginTransaction();

            // Format waktu pengukuran
            $waktuPengukuranFormatted = Carbon::createFromFormat('Y-m-d\TH:i', $request->waktu_pengukuran)
                ->format('Y-m-d H:i:s');

            $dataBaru = $request->only((new OmtPengukuran())->getFillable());
            $dataBaru['waktu_pengukuran'] = $waktuPengukuranFormatted;

            //apabila field ini dikosongi maka sistem otomatis mengisi menjadi 0
            $kolomAngka = [
                'ian',
                'iar',
                'ias',
                'iat',
                'ibn',
                'ibr',
                'ibs',
                'ibt',
                'icn',
                'icr',
                'ics',
                'ict',
                'idn',
                'idr',
                'ids',
                'idt'
            ];

            foreach ($kolomAngka as $kolom) {
                $dataBaru[$kolom] = $request->input($kolom, 0) ?? 0;
            }

            unset($dataBaru['diubah_oleh']);
            $dataLamaOmt = $pengukuran->only(array_keys($dataBaru));
            $perubahanUpdate = array_diff_assoc($dataBaru, $dataLamaOmt);

            // Hitung beban KVA trafo
            $bebanKvaTrafo = round((($request->iur * $request->vrn) + ($request->ius * $request->vsn) + ($request->iut * $request->vtn)) / 1000, 1);

            $gardu = DataGardu::where('kd_gardu', $request->kd_gardu)->first();

            $persentaseBeban = 0;
            if ($gardu && $gardu->daya_trafo > 0) {
                $persentaseBeban = round(($bebanKvaTrafo / $gardu->daya_trafo) * 100, 1);
            }

            // === VALIDASI HASIL HITUNG - DITAMBAHKAN DI SINI ===
            $validasiHasil = Validator::make([
                'beban_kva_trafo'  => $bebanKvaTrafo,
                'persentase_beban' => $persentaseBeban
            ], [
                'beban_kva_trafo'  => 'nullable|numeric|between:0,9999.9|regex:/^\d{1,4}(\.\d)?$/',
                'persentase_beban' => 'nullable|numeric|between:0,999.9|regex:/^\d{1,3}(\.\d)?$/',
            ], [
                'persentase_beban.numeric' => 'Persentase beban harus berupa angka',
                'persentase_beban.between' => 'Sistem secara otomatis menghitung persentase beban dengan rumus = beban kva trafo / daya trafo x 100. input anda melebihi batas max input 999.9',
                'persentase_beban.regex'   => 'Sistem hanya menerima data Persentase beban dengan 1 angka desimal, contoh max input 999.9',
                'beban_kva_trafo.numeric'  => 'Beban KVA trafo harus berupa angka',
                'beban_kva_trafo.between'  => 'Sistem secara otomatis menghitung beban kva trafo dengan rumus = (iur*vrn) + (ius*vsn) + (iut*vtn). input anda melebihi batas max input 9999.9',
                'beban_kva_trafo.regex'    => 'Sistem hanya menerima data Beban KVA trafo dengan 1 angka desimal, contoh max input 9999.9',
            ]);
            if ($validasiHasil->fails()) {
                throw ValidationException::withMessages($validasiHasil->errors()->toArray());
            }

            $adaPerubahanGardu = false;
            $adaPerubahanOmt = !empty($perubahanUpdate);

            if ($gardu) {
                $fmtDecimal = function ($val, $decimals = 1) {
                    if (is_null($val) || $val === '') return null;
                    if (is_numeric($val)) {
                        return number_format(round((float)$val, $decimals), $decimals, '.', '');
                    }
                    return trim((string)$val);
                };

                $newBebanStr = $fmtDecimal($bebanKvaTrafo, 1);
                $newPersStr  = $fmtDecimal($persentaseBeban, 1);
                $oldBebanStr = $fmtDecimal($gardu->beban_kva_trafo ?? null, 1);
                $oldPersStr  = $fmtDecimal($gardu->persentase_beban ?? null, 1);

                if ($oldBebanStr !== $newBebanStr || $oldPersStr !== $newPersStr) {
                    $adaPerubahanGardu = true;

                    $dataLamaFull = $gardu->toArray();

                    $gardu->update([
                        'beban_kva_trafo'  => $newBebanStr,
                        'persentase_beban' => $newPersStr,
                    ]);

                    $dataBaruFull = $gardu->fresh()->toArray();

                    $fieldsUntukBanding = ['beban_kva_trafo', 'persentase_beban'];
                    foreach ($fieldsUntukBanding as $f) {
                        $dataLamaFull[$f] = $fmtDecimal($dataLamaFull[$f] ?? null, 1);
                        $dataBaruFull[$f] = $fmtDecimal($dataBaruFull[$f] ?? null, 1);
                    }

                    $dataLamaBanding = array_intersect_key($dataLamaFull, array_flip($fieldsUntukBanding));
                    $dataBaruBanding = array_intersect_key($dataBaruFull, array_flip($fieldsUntukBanding));

                    if ($dataLamaBanding !== $dataBaruBanding) {
                        HistoryDataGardu::create([
                            'id_data_gardu' => $gardu->id,
                            'data_lama'     => json_encode($dataLamaFull, JSON_UNESCAPED_UNICODE),
                            'data_baru'     => json_encode($dataBaruFull, JSON_UNESCAPED_UNICODE),
                            'aksi'          => 'update',
                            'diubah_oleh'   => Auth::user()->name ?? 'Sistem (otomatis)',
                            'keterangan'    => 'Pembaruan beban dari hasil pengukuran',
                        ]);
                    }
                }
            }

            if ($adaPerubahanOmt) {
                $dataBaru['diubah_oleh'] = auth()->user()->name ?? 'Sistem';

                $pengukuran->update($dataBaru);
                $pengukuran->refresh();

                // $extra = [
                //     'beban_kva_trafo'  => $gardu->beban_kva_trafo ?? null,
                //     'persentase_beban' => $gardu->persentase_beban ?? null,
                // ];

                HistoryDataOmtPengukuran::create([
                    'id_omt_pengukuran' => $pengukuran->id,
                    'data_lama'         => json_encode(array_merge($dataLamaOmt), JSON_UNESCAPED_UNICODE),
                    'data_baru'         => json_encode(array_merge($pengukuran->only(array_keys($dataBaru))), JSON_UNESCAPED_UNICODE),
                    'aksi'              => 'update',
                    'diubah_oleh'       => auth()->user()->name,
                    'keterangan'        => $request->keterangan_history,
                ]);
            }

            DB::commit();

            // Berikan response berdasarkan perubahan yang terjadi
            if (!$adaPerubahanOmt && !$adaPerubahanGardu) {
                return back()->with('info', 'Tidak ada perubahan data.');
            } elseif ($adaPerubahanOmt && $adaPerubahanGardu) {
                return redirect()
                    ->route('omt-pengukuran.create', ['kd_gardu' => $pengukuran->kd_gardu])
                    ->with('success', 'OMT pengukuran dan data gardu berhasil diperbarui.');
            } elseif ($adaPerubahanOmt) {
                return redirect()
                    ->route('omt-pengukuran.create', ['kd_gardu' => $pengukuran->kd_gardu])
                    ->with('success', 'OMT pengukuran berhasil diperbarui.');
            } else {
                return back()->with('info', 'Data gardu berhasil diperbarui, tidak ada perubahan pada OMT.');
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('OMT Update Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
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
                    ->orWhere("hlast.data_lama", 'like', '%"kd_gardu":"' . $q . '"%')
                    ->orWhere("hlast.data_lama", 'like', '%"kd_gardu":' . $q . '%');
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
