<?php

namespace App\Http\Controllers\Admin\ManajemenData;

use App\Http\Controllers\Controller;
use App\Models\HistoryData\HistoryDataPemeliharaan;
use App\Models\ManajemenData\Pemeliharaan;
use App\Models\ManajemenData\DataGardu; // <-- TAMBAHKAN BARIS INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemeliharaanController extends Controller
{
    public function create($kd_gardu)
    {
        $gardu = DataGardu::where('kd_gardu', $kd_gardu)->firstOrFail();
        $pemeliharaan = Pemeliharaan::where('kd_gardu', $kd_gardu)->first(); // bisa null (mode input baru)

        return view('manajemen-data.pemeliharaan.pemeliharaan', compact('gardu', 'pemeliharaan'));
    }
    public function store(Request $request)
    {
        $this->normalizeDatetime($request, 'waktu_pemeliharaan');
        $validator = Validator::make($request->all(), [
            'waktu_pemeliharaan' => 'required|date_format:Y-m-d H:i:s',
            'kd_gardu' => 'required|string|max:10|exists:data_gardu,kd_gardu|unique:pemeliharaan,kd_gardu',
            'sutm_mm' => 'required|string|max:15',
            'jumper_sutm_out_fasa_r' => 'required|string|max:10',
            'jumper_sutm_out_fasa_s' => 'required|string|max:10',
            'jumper_sutm_out_fasa_t' => 'required|string|max:10',
            'cond_sutm_co_fasa_r' => 'required|string|max:10',
            'cond_sutm_co_fasa_s' => 'required|string|max:10',
            'cond_sutm_co_fasa_t' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_r' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_s' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_t' => 'required|string|max:10',
            'fuse_link_fasa_r' => 'required|integer|max:32767',
            'fuse_link_fasa_s' => 'required|integer|max:32767',
            'fuse_link_fasa_t' => 'required|integer|max:32767',
            'keramik_polimer' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_r' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_s' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_t' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_r' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_s' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_t' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_r' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_s' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_t' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_r' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_s' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_t' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_r' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_s' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_t' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_r' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_s' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_t' => 'required|string|max:10',
            'arester_fasa_r' => 'required|in:ada,tidak ada',
            'arester_fasa_s' => 'required|in:ada,tidak ada',
            'arester_fasa_t' => 'required|in:ada,tidak ada',
            'keramik_polimer_lighting_arester' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_r' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_s' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_t' => 'required|string|max:10',
            'cond_dudukan_la' => 'required|string|max:10',
            'jumper_body_trf_la' => 'required|string|max:10',
            'cond_body_trf_la' => 'required|string|max:10',
            'jumper_cond_la_dg_body_trf' => 'required|string|max:10',
            'cond_ground_la_panel' => 'required|string|max:10',
            'isolasi_fasa_r' => 'required|string|max:10',
            'isolasi_fasa_s' => 'required|string|max:10',
            'isolasi_fasa_t' => 'required|string|max:10',
            'arus_bocor' => 'required|numeric|between:0,999.99|regex:/^\d{1,3}(\.\d{1,2})?$/',
            'jumper_trf_bush_skunder_4x_panel' => 'required|string|max:10',
            'cond_out_trf_panel' => 'required|string|max:10',
            'tahanan_isolasi_pp' => 'required|integer|max:32767',
            'tahanan_isolasi_pg' => 'required|integer|max:32767',
            'jumper_in_panel_saklar' => 'required|string|max:10',
            'jumper_in_nol' => 'required|string|max:10',
            'jumper_nol_ground' => 'required|string|max:10',
            'jenis_saklar_utama' => 'required|string|max:10',
            'jumper_dr_saklar_out' => 'required|string|max:10',
            'jenis_cond_dr_saklar_nh_utama' => 'required|string|max:20',
            'data_proteksi_utama_fasa_r' => 'required|string|max:10',
            'data_proteksi_utama_fasa_s' => 'required|string|max:10',
            'data_proteksi_utama_fasa_t' => 'required|string|max:10',
            'jenis_cond_dr_nh_utama_jurusan' => 'required|string|max:20',
            'jumper_dr_nh_jurusan_in' => 'required|string|max:20',
            'data_proteksi_line_a_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_a_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_a_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_t' => 'required|string|max:10',
            'jumper_out_dr_nh_jurusan_cond_out_jtr' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_a' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_b' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_c' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_d' => 'required|string|max:10',
            'cond_jurusan_jtr_line_a' => 'required|string|max:10',
            'cond_jurusan_jtr_line_b' => 'required|string|max:10',
            'cond_jurusan_jtr_line_c' => 'required|string|max:10',
            'cond_jurusan_jtr_line_d' => 'required|string|max:10',
            'jumper_la_body_panel' => 'required|string|max:10',
            'cond_dr_ground_la_body' => 'required|string|max:10',
            'cond_dr_nol_ground' => 'required|string|max:10',
            'cond_dr_kopel_body_dg_la_ground' => 'required|string|max:10',
            'nilai_r_tanah_nol' => 'required|integer|max:32767',
            'nilai_r_tanah_la' => 'required|integer|max:32767',
            'panel_gtt_pintu' => 'required|string|max:10',
            'panel_gtt_kunci' => 'required|string|max:10',
            'panel_gtt_no_gtt' => 'required|string|max:10',
            'panel_gtt_kondisi' => 'required|string|max:10',
            'panel_gtt_lubang_pipa' => 'required|string|max:10',
            'panel_gtt_pondasi' => 'required|string|max:10',
            'panel_gtt_tanda_peringatan' => 'required|string|max:10',
            'panel_gtt_jenis_gardu' => 'required|string|max:10',
            'panel_gtt_tgl_inspeksi' => 'required|date',
            'panel_gtt_insp_siang' => 'required|string|max:10',
            'panel_gtt_pekerjaan_pemeliharaan' => 'required|string|max:50',
            'panel_gtt_catatan' => 'nullable|string|max:50',
            'tahan_isolasi_trafo_1_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_1_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_1_ps' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_ps' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_ps' => 'required|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan_history' => 'nullable|string|max:20',
        ], [
            'waktu_pemeliharaan.required' => 'Waktu pemeliharaan wajib diisi',
            'waktu_pemeliharaan.date_format' => 'Format waktu pemeliharaan harus YYYY-MM-DD HH:MM:SS',

            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.max' => 'Panjang karakter maksimal kode gardu adalah 10',
            'kd_gardu.exists' => 'Kode gardu tidak ditemukan di data master gardu',
            'kd_gardu.unique' => 'Kode gardu sudah digunakan',

            'sutm_mm.required' => 'SUTM mm wajib diisi',
            'sutm_mm.max' => 'Panjang karakter maksimal SUTM mm adalah 15',

            'jumper_sutm_out_fasa_r.required' => 'Jumper SUTM out fasa R wajib diisi',
            'jumper_sutm_out_fasa_r.max' => 'Panjang karakter maksimal jumper SUTM out fasa R adalah 10',

            'jumper_sutm_out_fasa_s.required' => 'Jumper SUTM out fasa S wajib diisi',
            'jumper_sutm_out_fasa_s.max' => 'Panjang karakter maksimal jumper SUTM out fasa S adalah 10',

            'jumper_sutm_out_fasa_t.required' => 'Jumper SUTM out fasa T wajib diisi',
            'jumper_sutm_out_fasa_t.max' => 'Panjang karakter maksimal jumper SUTM out fasa T adalah 10',

            'cond_sutm_co_fasa_r.required' => 'Cond SUTM CO fasa R wajib diisi',
            'cond_sutm_co_fasa_r.max' => 'Panjang karakter maksimal cond SUTM CO fasa R adalah 10',

            'cond_sutm_co_fasa_s.required' => 'Cond SUTM CO fasa S wajib diisi',
            'cond_sutm_co_fasa_s.max' => 'Panjang karakter maksimal cond SUTM CO fasa S adalah 10',

            'cond_sutm_co_fasa_t.required' => 'Cond SUTM CO fasa T wajib diisi',
            'cond_sutm_co_fasa_t.max' => 'Panjang karakter maksimal cond SUTM CO fasa T adalah 10',

            'jumper_sutm_co_income_fasa_r.required' => 'Jumper SUTM CO income fasa R wajib diisi',
            'jumper_sutm_co_income_fasa_r.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa R adalah 10',

            'jumper_sutm_co_income_fasa_s.required' => 'Jumper SUTM CO income fasa S wajib diisi',
            'jumper_sutm_co_income_fasa_s.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa S adalah 10',

            'jumper_sutm_co_income_fasa_t.required' => 'Jumper SUTM CO income fasa T wajib diisi',
            'jumper_sutm_co_income_fasa_t.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa T adalah 10',

            'fuse_link_fasa_r.required' => 'Fuse link fasa R wajib diisi',
            'fuse_link_fasa_r.integer' => 'Fuse link fasa R harus berupa angka',
            'fuse_link_fasa_r.max' => 'Nilai maksimal fuse link fasa R adalah 32.767',

            'fuse_link_fasa_s.required' => 'Fuse link fasa S wajib diisi',
            'fuse_link_fasa_s.integer' => 'Fuse link fasa S harus berupa angka',
            'fuse_link_fasa_s.max' => 'Nilai maksimal fuse link fasa S adalah 32.767',

            'fuse_link_fasa_t.required' => 'Fuse link fasa T wajib diisi',
            'fuse_link_fasa_t.integer' => 'Fuse link fasa T harus berupa angka',
            'fuse_link_fasa_t.max' => 'Nilai maksimal fuse link fasa T adalah 32.767',

            'keramik_polimer.required' => 'Keramik polimer wajib diisi',
            'keramik_polimer.max' => 'Panjang karakter maksimal keramik polimer adalah 10',

            'jumper_co_trafo_primer_out_fasa_r.required' => 'Jumper CO trafo primer out fasa R wajib diisi',
            'jumper_co_trafo_primer_out_fasa_r.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa R adalah 10',

            'jumper_co_trafo_primer_out_fasa_s.required' => 'Jumper CO trafo primer out fasa S wajib diisi',
            'jumper_co_trafo_primer_out_fasa_s.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa S adalah 10',

            'jumper_co_trafo_primer_out_fasa_t.required' => 'Jumper CO trafo primer out fasa T wajib diisi',
            'jumper_co_trafo_primer_out_fasa_t.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa T adalah 10',

            'cond_co_trafo_bush_primer_fasa_r.required' => 'Cond CO trafo bush primer fasa R wajib diisi',
            'cond_co_trafo_bush_primer_fasa_r.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa R adalah 10',

            'cond_co_trafo_bush_primer_fasa_s.required' => 'Cond CO trafo bush primer fasa S wajib diisi',
            'cond_co_trafo_bush_primer_fasa_s.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa S adalah 10',

            'cond_co_trafo_bush_primer_fasa_t.required' => 'Cond CO trafo bush primer fasa T wajib diisi',
            'cond_co_trafo_bush_primer_fasa_t.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa T adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_r.required' => 'Jumper CO bush trafo primer income fasa R wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_r.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa R adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_s.required' => 'Jumper CO bush trafo primer income fasa S wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_s.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa S adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_t.required' => 'Jumper CO bush trafo primer income fasa T wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_t.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa T adalah 10',

            'jumper_bush_primer_out_arester_fasa_r.required' => 'Jumper bush primer out arester fasa R wajib diisi',
            'jumper_bush_primer_out_arester_fasa_r.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa R adalah 10',

            'jumper_bush_primer_out_arester_fasa_s.required' => 'Jumper bush primer out arester fasa S wajib diisi',
            'jumper_bush_primer_out_arester_fasa_s.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa S adalah 10',

            'jumper_bush_primer_out_arester_fasa_t.required' => 'Jumper bush primer out arester fasa T wajib diisi',
            'jumper_bush_primer_out_arester_fasa_t.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa T adalah 10',

            'cond_bush_primer_arester_fasa_r.required' => 'Cond bush primer arester fasa R wajib diisi',
            'cond_bush_primer_arester_fasa_r.max' => 'Panjang karakter maksimal cond bush primer arester fasa R adalah 10',

            'cond_bush_primer_arester_fasa_s.required' => 'Cond bush primer arester fasa S wajib diisi',
            'cond_bush_primer_arester_fasa_s.max' => 'Panjang karakter maksimal cond bush primer arester fasa S adalah 10',

            'cond_bush_primer_arester_fasa_t.required' => 'Cond bush primer arester fasa T wajib diisi',
            'cond_bush_primer_arester_fasa_t.max' => 'Panjang karakter maksimal cond bush primer arester fasa T adalah 10',

            'jumper_bush_primer_income_arester_fasa_r.required' => 'Jumper bush primer income arester fasa R wajib diisi',
            'jumper_bush_primer_income_arester_fasa_r.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa R adalah 10',

            'jumper_bush_primer_income_arester_fasa_s.required' => 'Jumper bush primer income arester fasa S wajib diisi',
            'jumper_bush_primer_income_arester_fasa_s.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa S adalah 10',

            'jumper_bush_primer_income_arester_fasa_t.required' => 'Jumper bush primer income arester fasa T wajib diisi',
            'jumper_bush_primer_income_arester_fasa_t.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa T adalah 10',

            'arester_fasa_r.required' => 'Arester fasa R wajib diisi',
            'arester_fasa_r.in' => 'Arester fasa R hanya bisa di isi ada/ tidak ada',

            'arester_fasa_s.required' => 'Arester fasa S wajib diisi',
            'arester_fasa_r.in' => 'Arester fasa S hanya bisa di isi ada/ tidak ada',

            'arester_fasa_t.required' => 'Arester fasa T wajib diisi',
            'arester_fasa_t.in' => 'Arester fasa T hanya bisa di isi ada/ tidak ada',

            'keramik_polimer_lighting_arester.required' => 'Keramik polimer lighting arester wajib diisi',
            'keramik_polimer_lighting_arester.max' => 'Panjang karakter maksimal keramik polimer lighting arester adalah 10',

            'jumper_dudukan_arester_fasa_r.required' => 'Jumper dudukan arester fasa R wajib diisi',
            'jumper_dudukan_arester_fasa_r.max' => 'Panjang karakter maksimal jumper dudukan arester fasa R adalah 10',

            'jumper_dudukan_arester_fasa_s.required' => 'Jumper dudukan arester fasa S wajib diisi',
            'jumper_dudukan_arester_fasa_s.max' => 'Panjang karakter maksimal jumper dudukan arester fasa S adalah 10',

            'jumper_dudukan_arester_fasa_t.required' => 'Jumper dudukan arester fasa T wajib diisi',
            'jumper_dudukan_arester_fasa_t.max' => 'Panjang karakter maksimal jumper dudukan arester fasa T adalah 10',

            'cond_dudukan_la.required' => 'Cond dudukan LA wajib diisi',
            'cond_dudukan_la.max' => 'Panjang karakter maksimal cond dudukan LA adalah 10',

            'jumper_body_trf_la.required' => 'Jumper body TRF LA wajib diisi',
            'jumper_body_trf_la.max' => 'Panjang karakter maksimal jumper body TRF LA adalah 10',

            'cond_body_trf_la.required' => 'Cond body TRF LA wajib diisi',
            'cond_body_trf_la.max' => 'Panjang karakter maksimal cond body TRF LA adalah 10',

            'jumper_cond_la_dg_body_trf.required' => 'Jumper cond LA dg body TRF wajib diisi',
            'jumper_cond_la_dg_body_trf.max' => 'Panjang karakter maksimal jumper cond LA dg body TRF adalah 10',

            'cond_ground_la_panel.required' => 'Cond ground LA panel wajib diisi',
            'cond_ground_la_panel.max' => 'Panjang karakter maksimal cond ground LA panel adalah 10',

            'isolasi_fasa_r.required' => 'Isolasi fasa R wajib diisi',
            'isolasi_fasa_r.max' => 'Panjang karakter maksimal isolasi fasa R adalah 10',

            'isolasi_fasa_s.required' => 'Isolasi fasa S wajib diisi',
            'isolasi_fasa_s.max' => 'Panjang karakter maksimal isolasi fasa S adalah 10',

            'isolasi_fasa_t.required' => 'Isolasi fasa T wajib diisi',
            'isolasi_fasa_t.max' => 'Panjang karakter maksimal isolasi fasa T adalah 10',

            'arus_bocor.required' => 'Arus bocor wajib diisi',
            'arus_bocor.numeric' => 'Arus bocor harus berupa angka',
            'arus_bocor.between' => 'Arus bocor harus antara 0 sampai 999.99',
            'arus_bocor.regex' => 'Sistem hanya menerima data arus bocor dengan 2 angka desimal, contoh max input 999.99',

            'jumper_trf_bush_skunder_4x_panel.required' => 'Jumper TRF bush skunder 4x panel wajib diisi',
            'jumper_trf_bush_skunder_4x_panel.max' => 'Panjang karakter maksimal jumper TRF bush skunder 4x panel adalah 10',

            'cond_out_trf_panel.required' => 'Cond out TRF panel wajib diisi',
            'cond_out_trf_panel.max' => 'Panjang karakter maksimal cond out TRF panel adalah 10',

            'tahanan_isolasi_pp.required' => 'Tahanan isolasi PP wajib diisi',
            'tahanan_isolasi_pp.integer' => 'Tahanan isolasi PP harus berupa angka',
            'tahanan_isolasi_pp.max' => 'Nilai maksimal tahanan isolasi PP adalah 32.767',

            'tahanan_isolasi_pg.required' => 'Tahanan isolasi PG wajib diisi',
            'tahanan_isolasi_pg.integer' => 'Tahanan isolasi PG harus berupa angka',
            'tahanan_isolasi_pg.max' => 'Nilai maksimal tahanan isolasi PG adalah 32.767',

            'jumper_in_panel_saklar.required' => 'Jumper in panel saklar wajib diisi',
            'jumper_in_panel_saklar.max' => 'Panjang karakter maksimal jumper in panel saklar adalah 10',

            'jumper_in_nol.required' => 'Jumper in nol wajib diisi',
            'jumper_in_nol.max' => 'Panjang karakter maksimal jumper in nol adalah 10',

            'jumper_nol_ground.required' => 'Jumper nol ground wajib diisi',
            'jumper_nol_ground.max' => 'Panjang karakter maksimal jumper nol ground adalah 10',

            'jenis_saklar_utama.required' => 'Jenis saklar utama wajib diisi',
            'jenis_saklar_utama.max' => 'Panjang karakter maksimal jenis saklar utama adalah 10',

            'jumper_dr_saklar_out.required' => 'Jumper dr saklar out wajib diisi',
            'jumper_dr_saklar_out.max' => 'Panjang karakter maksimal jumper dr saklar out adalah 10',

            'jenis_cond_dr_saklar_nh_utama.required' => 'Jenis cond dr saklar NH utama wajib diisi',
            'jenis_cond_dr_saklar_nh_utama.max' => 'Panjang karakter maksimal jenis cond dr saklar NH utama adalah 10',

            'data_proteksi_utama_fasa_r.required' => 'Data proteksi utama fasa R wajib diisi',
            'data_proteksi_utama_fasa_r.max' => 'Panjang karakter maksimal data proteksi utama fasa R adalah 10',

            'data_proteksi_utama_fasa_s.required' => 'Data proteksi utama fasa S wajib diisi',
            'data_proteksi_utama_fasa_s.max' => 'Panjang karakter maksimal data proteksi utama fasa S adalah 10',

            'data_proteksi_utama_fasa_t.required' => 'Data proteksi utama fasa T wajib diisi',
            'data_proteksi_utama_fasa_t.max' => 'Panjang karakter maksimal data proteksi utama fasa T adalah 10',

            'jenis_cond_dr_nh_utama_jurusan.required' => 'Jenis cond dr NH utama jurusan wajib diisi',
            'jenis_cond_dr_nh_utama_jurusan.max' => 'Panjang karakter maksimal jenis cond dr NH utama jurusan adalah 20',

            'jumper_dr_nh_jurusan_in.required' => 'Jumper dr NH jurusan in wajib diisi',
            'jumper_dr_nh_jurusan_in.max' => 'Panjang karakter maksimal jumper dr NH jurusan in adalah 20',

            'data_proteksi_line_a_fasa_r.required' => 'Data proteksi line A fasa R wajib diisi',
            'data_proteksi_line_a_fasa_r.max' => 'Panjang karakter maksimal data proteksi line A fasa R adalah 10',

            'data_proteksi_line_a_fasa_s.required' => 'Data proteksi line A fasa S wajib diisi',
            'data_proteksi_line_a_fasa_s.max' => 'Panjang karakter maksimal data proteksi line A fasa S adalah 10',

            'data_proteksi_line_a_fasa_t.required' => 'Data proteksi line A fasa T wajib diisi',
            'data_proteksi_line_a_fasa_t.max' => 'Panjang karakter maksimal data proteksi line A fasa T adalah 10',

            'data_proteksi_line_b_fasa_r.required' => 'Data proteksi line B fasa R wajib diisi',
            'data_proteksi_line_b_fasa_r.max' => 'Panjang karakter maksimal data proteksi line B fasa R adalah 10',

            'data_proteksi_line_b_fasa_s.required' => 'Data proteksi line B fasa S wajib diisi',
            'data_proteksi_line_b_fasa_s.max' => 'Panjang karakter maksimal data proteksi line B fasa S adalah 10',

            'data_proteksi_line_b_fasa_t.required' => 'Data proteksi line B fasa T wajib diisi',
            'data_proteksi_line_b_fasa_t.max' => 'Panjang karakter maksimal data proteksi line B fasa T adalah 10',

            'data_proteksi_line_c_fasa_r.required' => 'Data proteksi line C fasa R wajib diisi',
            'data_proteksi_line_c_fasa_r.max' => 'Panjang karakter maksimal data proteksi line C fasa R adalah 10',

            'data_proteksi_line_c_fasa_s.required' => 'Data proteksi line C fasa S wajib diisi',
            'data_proteksi_line_c_fasa_s.max' => 'Panjang karakter maksimal data proteksi line C fasa S adalah 10',

            'data_proteksi_line_c_fasa_t.required' => 'Data proteksi line C fasa T wajib diisi',
            'data_proteksi_line_c_fasa_t.max' => 'Panjang karakter maksimal data proteksi line C fasa T adalah 10',

            'data_proteksi_line_d_fasa_r.required' => 'Data proteksi line D fasa R wajib diisi',
            'data_proteksi_line_d_fasa_r.max' => 'Panjang karakter maksimal data proteksi line D fasa R adalah 10',

            'data_proteksi_line_d_fasa_s.required' => 'Data proteksi line D fasa S wajib diisi',
            'data_proteksi_line_d_fasa_s.max' => 'Panjang karakter maksimal data proteksi line D fasa S adalah 10',

            'data_proteksi_line_d_fasa_t.required' => 'Data proteksi line D fasa T wajib diisi',
            'data_proteksi_line_d_fasa_t.max' => 'Panjang karakter maksimal data proteksi line D fasa T adalah 10',

            'jumper_out_dr_nh_jurusan_cond_out_jtr.required' => 'Jumper out dr NH jurusan cond out JTR wajib diisi',
            'jumper_out_dr_nh_jurusan_cond_out_jtr.max' => 'Panjang karakter maksimal jumper out dr NH jurusan cond out JTR adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_a.required' => 'Cond dr NH jurusan out JTR line A wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_a.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line A adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_b.required' => 'Cond dr NH jurusan out JTR line B wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_b.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line B adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_c.required' => 'Cond dr NH jurusan out JTR line C wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_c.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line C adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_d.required' => 'Cond dr NH jurusan out JTR line D wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_d.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line D adalah 10',

            'cond_jurusan_jtr_line_a.required' => 'Cond jurusan JTR line A wajib diisi',
            'cond_jurusan_jtr_line_a.max' => 'Panjang karakter maksimal cond jurusan JTR line A adalah 10',

            'cond_jurusan_jtr_line_b.required' => 'Cond jurusan JTR line B wajib diisi',
            'cond_jurusan_jtr_line_b.max' => 'Panjang karakter maksimal cond jurusan JTR line B adalah 10',

            'cond_jurusan_jtr_line_c.required' => 'Cond jurusan JTR line C wajib diisi',
            'cond_jurusan_jtr_line_c.max' => 'Panjang karakter maksimal cond jurusan JTR line C adalah 10',

            'cond_jurusan_jtr_line_d.required' => 'Cond jurusan JTR line D wajib diisi',
            'cond_jurusan_jtr_line_d.max' => 'Panjang karakter maksimal cond jurusan JTR line D adalah 10',

            'jumper_la_body_panel.required' => 'Jumper LA body panel wajib diisi',
            'jumper_la_body_panel.max' => 'Panjang karakter maksimal jumper LA body panel adalah 10',

            'cond_dr_ground_la_body.required' => 'Cond dr ground LA body wajib diisi',
            'cond_dr_ground_la_body.max' => 'Panjang karakter maksimal cond dr ground LA body adalah 10',

            'cond_dr_nol_ground.required' => 'Cond dr nol ground wajib diisi',
            'cond_dr_nol_ground.max' => 'Panjang karakter maksimal cond dr nol ground adalah 10',

            'cond_dr_kopel_body_dg_la_ground.required' => 'Cond dr kopel body dg LA ground wajib diisi',
            'cond_dr_kopel_body_dg_la_ground.max' => 'Panjang karakter maksimal cond dr kopel body dg LA ground adalah 10',

            'nilai_r_tanah_nol.required' => 'Nilai R tanah nol wajib diisi',
            'nilai_r_tanah_nol.integer' => 'Nilai R tanah nol harus berupa angka',
            'nilai_r_tanah_nol.max' => 'Nilai maksimal R tanah nol adalah 32.767',

            'nilai_r_tanah_la.required' => 'Nilai R tanah LA wajib diisi',
            'nilai_r_tanah_la.integer' => 'Nilai R tanah LA harus berupa angka',
            'nilai_r_tanah_la.max' => 'Nilai maksimal R tanah LA adalah 32.767',

            'panel_gtt_pintu.required' => 'Panel GTT pintu wajib diisi',
            'panel_gtt_pintu.max' => 'Panjang karakter maksimal panel GTT pintu adalah 10',

            'panel_gtt_kunci.required' => 'Panel GTT kunci wajib diisi',
            'panel_gtt_kunci.max' => 'Panjang karakter maksimal panel GTT kunci adalah 10',

            'panel_gtt_no_gtt.required' => 'Panel GTT no GTT wajib diisi',
            'panel_gtt_no_gtt.max' => 'Panjang karakter maksimal panel GTT no GTT adalah 10',

            'panel_gtt_kondisi.required' => 'Panel GTT kondisi wajib diisi',
            'panel_gtt_kondisi.max' => 'Panjang karakter maksimal panel GTT kondisi adalah 10',

            'panel_gtt_lubang_pipa.required' => 'Panel GTT lubang pipa wajib diisi',
            'panel_gtt_lubang_pipa.max' => 'Panjang karakter maksimal panel GTT lubang pipa adalah 10',

            'panel_gtt_pondasi.required' => 'Panel GTT pondasi wajib diisi',
            'panel_gtt_pondasi.max' => 'Panjang karakter maksimal panel GTT pondasi adalah 10',

            'panel_gtt_tanda_peringatan.required' => 'Panel GTT tanda peringatan wajib diisi',
            'panel_gtt_tanda_peringatan.max' => 'Panjang karakter maksimal panel GTT tanda peringatan adalah 10',

            'panel_gtt_jenis_gardu.required' => 'Panel GTT jenis gardu wajib diisi',
            'panel_gtt_jenis_gardu.max' => 'Panjang karakter maksimal panel GTT jenis gardu adalah 10',

            'panel_gtt_tgl_inspeksi.required' => 'Panel GTT tanggal inspeksi wajib diisi',
            'panel_gtt_tgl_inspeksi.date' => 'Panel GTT tanggal inspeksi harus berupa tanggal yang valid',

            'panel_gtt_insp_siang.required' => 'Panel GTT inspeksi siang wajib diisi',
            'panel_gtt_insp_siang.max' => 'Panjang karakter maksimal panel GTT inspeksi siang adalah 10',

            'panel_gtt_pekerjaan_pemeliharaan.max' => 'Panjang karakter maksimal panel GTT pekerjaan pemeliharaan adalah 50',

            'panel_gtt_catatan.max' => 'Panjang karakter maksimal panel GTT catatan adalah 10',

            'tahan_isolasi_trafo_1_pb.required' => 'Tahan solasi trafo 1 p-b wajib di isi',
            'tahan_isolasi_trafo_1_sb.integer' => 'Tahan solasi trafo 1 p-b harus berupa angka',
            'tahan_isolasi_trafo_1_ps.max' => 'Nilai maksimal Tahan solasi trafo 1 p-b adalah 32.767',
            'tahan_isolasi_trafo_2_pb.required' => 'Tahan solasi trafo 2 p-b wajib di isi',
            'tahan_isolasi_trafo_2_sb.integer' => 'Tahan solasi trafo 2 p-b harus berupa angka',
            'tahan_isolasi_trafo_2_ps.max' => 'Nilai maksimal Tahan solasi trafo 2 p-b adalah 32.767',
            'tahan_isolasi_trafo_3_pb.required' => 'Tahan solasi trafo 3 p-b wajib di isi',
            'tahan_isolasi_trafo_3_sb.integer' => 'Tahan solasi trafo 3 p-b harus berupa angka',
            'tahan_isolasi_trafo_3_ps.max' => 'Nilai maksimal Tahan solasi trafo 3 p-b adalah 32.767',

            'keterangan_history.string' => 'Keterangan history harus berupa teks',
            'keterangan_history.max' => 'Panjang karakter maksimal keterangan history adalah 20',
        ]);

        if ($validator->fails()) {
            // AJAX/JSON => JSON, Form biasa => redirect back + error
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
            $pemeliharaan = DB::transaction(function () use ($request) {
                $pemeliharaan = Pemeliharaan::create(
                    $request->only((new Pemeliharaan())->getFillable())
                );

                HistoryDataPemeliharaan::create([
                    'id_pemeliharaan' => $pemeliharaan->id,
                    'data_lama'       => json_encode($pemeliharaan->toArray(), JSON_UNESCAPED_UNICODE),
                    'aksi'            => 'create',
                    'diubah_oleh' => Auth::user()->name,
                    'keterangan'      => $request->input('keterangan_history'),
                ]);

                return $pemeliharaan;
            });

            // === RESPONSE SESUAI TIPE REQUEST ===
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pemeliharaan berhasil ditambahkan',
                    'data'    => $pemeliharaan
                ], 201);
            }

            // FORM biasa: redirect ke form create (konteks gardu sama) + flash success
            return redirect()
                ->route('pemeliharaan.create', ['kd_gardu' => $pemeliharaan->kd_gardu])
                ->with('success', 'Data pemeliharaan berhasil ditambahkan');
        } catch (\Throwable $e) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error'   => env('APP_DEBUG') ? $e->getMessage() : null
                ], 500);
            }

            return back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->filled('waktu_pemeliharaan')) {
        $raw = $request->input('waktu_pemeliharaan');
        try {
            if (Carbon::hasFormat($raw, 'Y-m-d\TH:i')) {
                $dt = Carbon::createFromFormat('Y-m-d\TH:i', $raw);
            } elseif (Carbon::hasFormat($raw, 'Y-m-d H:i:s')) {
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $raw);
            } else {
                $dt = null;
            }
            if ($dt) {
                $request->merge(['waktu_pemeliharaan' => $dt->format('Y-m-d H:i:s')]);
            }
        } catch (\Throwable $e) { /* biarkan validator yang menolak */ }
    }
    if ($request->filled('arus_bocor')) {
        $request->merge([
            'arus_bocor' => number_format((float) $request->input('arus_bocor'), 2, '.', ''),
        ]);
    }

    $pemeliharaan = Pemeliharaan::find($id);
    if (!$pemeliharaan) {
        return $request->wantsJson()
            ? response()->json(['success' => false, 'message' => 'Data gardu tidak ditemukan'], 404)
            : back()->with('error', 'Data gardu tidak ditemukan.');
    }

        $rules = [
            'waktu_pemeliharaan' => 'required|date_format:Y-m-d H:i:s',
            'kd_gardu' => "required|string|max:10|exists:data_gardu,kd_gardu|unique:pemeliharaan,kd_gardu,{$id},id",
            'sutm_mm' => 'required|string|max:15',
            'jumper_sutm_out_fasa_r' => 'required|string|max:10',
            'jumper_sutm_out_fasa_s' => 'required|string|max:10',
            'jumper_sutm_out_fasa_t' => 'required|string|max:10',
            'cond_sutm_co_fasa_r' => 'required|string|max:10',
            'cond_sutm_co_fasa_s' => 'required|string|max:10',
            'cond_sutm_co_fasa_t' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_r' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_s' => 'required|string|max:10',
            'jumper_sutm_co_income_fasa_t' => 'required|string|max:10',
            'fuse_link_fasa_r' => 'required|integer|max:32767',
            'fuse_link_fasa_s' => 'required|integer|max:32767',
            'fuse_link_fasa_t' => 'required|integer|max:32767',
            'keramik_polimer' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_r' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_s' => 'required|string|max:10',
            'jumper_co_trafo_primer_out_fasa_t' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_r' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_s' => 'required|string|max:10',
            'cond_co_trafo_bush_primer_fasa_t' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_r' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_s' => 'required|string|max:10',
            'jumper_co_bush_trafo_primer_income_fasa_t' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_r' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_s' => 'required|string|max:10',
            'jumper_bush_primer_out_arester_fasa_t' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_r' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_s' => 'required|string|max:10',
            'cond_bush_primer_arester_fasa_t' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_r' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_s' => 'required|string|max:10',
            'jumper_bush_primer_income_arester_fasa_t' => 'required|string|max:10',
            'arester_fasa_r' => 'required|in:ada,tidak ada',
            'arester_fasa_s' => 'required|in:ada,tidak ada',
            'arester_fasa_t' => 'required|in:ada,tidak ada',
            'keramik_polimer_lighting_arester' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_r' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_s' => 'required|string|max:10',
            'jumper_dudukan_arester_fasa_t' => 'required|string|max:10',
            'cond_dudukan_la' => 'required|string|max:10',
            'jumper_body_trf_la' => 'required|string|max:10',
            'cond_body_trf_la' => 'required|string|max:10',
            'jumper_cond_la_dg_body_trf' => 'required|string|max:10',
            'cond_ground_la_panel' => 'required|string|max:10',
            'isolasi_fasa_r' => 'required|string|max:10',
            'isolasi_fasa_s' => 'required|string|max:10',
            'isolasi_fasa_t' => 'required|string|max:10',
            'arus_bocor' => 'required|numeric|between:0,999.99|regex:/^\d{1,3}(\.\d{1,2})?$/',
            'jumper_trf_bush_skunder_4x_panel' => 'required|string|max:10',
            'cond_out_trf_panel' => 'required|string|max:10',
            'tahanan_isolasi_pp' => 'required|integer|max:32767',
            'tahanan_isolasi_pg' => 'required|integer|max:32767',
            'jumper_in_panel_saklar' => 'required|string|max:10',
            'jumper_in_nol' => 'required|string|max:10',
            'jumper_nol_ground' => 'required|string|max:10',
            'jenis_saklar_utama' => 'required|string|max:10',
            'jumper_dr_saklar_out' => 'required|string|max:10',
            'jenis_cond_dr_saklar_nh_utama' => 'required|string|max:20',
            'data_proteksi_utama_fasa_r' => 'required|string|max:10',
            'data_proteksi_utama_fasa_s' => 'required|string|max:10',
            'data_proteksi_utama_fasa_t' => 'required|string|max:10',
            'jenis_cond_dr_nh_utama_jurusan' => 'required|string|max:20',
            'jumper_dr_nh_jurusan_in' => 'required|string|max:20',
            'data_proteksi_line_a_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_a_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_a_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_b_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_c_fasa_t' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_r' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_s' => 'required|string|max:10',
            'data_proteksi_line_d_fasa_t' => 'required|string|max:10',
            'jumper_out_dr_nh_jurusan_cond_out_jtr' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_a' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_b' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_c' => 'required|string|max:10',
            'cond_dr_nh_jurusan_out_jtr_line_d' => 'required|string|max:10',
            'cond_jurusan_jtr_line_a' => 'required|string|max:10',
            'cond_jurusan_jtr_line_b' => 'required|string|max:10',
            'cond_jurusan_jtr_line_c' => 'required|string|max:10',
            'cond_jurusan_jtr_line_d' => 'required|string|max:10',
            'jumper_la_body_panel' => 'required|string|max:10',
            'cond_dr_ground_la_body' => 'required|string|max:10',
            'cond_dr_nol_ground' => 'required|string|max:10',
            'cond_dr_kopel_body_dg_la_ground' => 'required|string|max:10',
            'nilai_r_tanah_nol' => 'required|integer|max:32767',
            'nilai_r_tanah_la' => 'required|integer|max:32767',
            'panel_gtt_pintu' => 'required|string|max:10',
            'panel_gtt_kunci' => 'required|string|max:10',
            'panel_gtt_no_gtt' => 'required|string|max:10',
            'panel_gtt_kondisi' => 'required|string|max:10',
            'panel_gtt_lubang_pipa' => 'required|string|max:10',
            'panel_gtt_pondasi' => 'required|string|max:10',
            'panel_gtt_tanda_peringatan' => 'required|string|max:10',
            'panel_gtt_jenis_gardu' => 'required|string|max:10',
            'panel_gtt_tgl_inspeksi' => 'required|date',
            'panel_gtt_insp_siang' => 'required|string|max:10',
            'panel_gtt_pekerjaan_pemeliharaan' => 'required|string|max:50',
            'panel_gtt_catatan' => 'nullable|string|max:50',
            'tahan_isolasi_trafo_1_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_1_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_1_ps' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_2_ps' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_pb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_sb' => 'required|integer|max:32767',
            'tahan_isolasi_trafo_3_ps' => 'required|integer|max:32767',

            // pencatatan perubahan pada tabel history
            'keterangan_history' => 'nullable|string|max:20',
        ];
         $messages = [
            'waktu_pemeliharaan.required' => 'Waktu pemeliharaan wajib diisi',
            'waktu_pemeliharaan.date_format' => 'Format waktu pemeliharaan harus YYYY-MM-DD HH:MM:SS',

            'kd_gardu.required' => 'Kode gardu wajib diisi',
            'kd_gardu.max' => 'Panjang karakter maksimal kode gardu adalah 10',
            'kd_gardu.exists' => 'Kode gardu tidak ditemukan di data master gardu',
            'kd_gardu.unique' => 'Kode gardu sudah digunakan',

            'sutm_mm.required' => 'SUTM mm wajib diisi',
            'sutm_mm.max' => 'Panjang karakter maksimal SUTM mm adalah 15',

            'jumper_sutm_out_fasa_r.required' => 'Jumper SUTM out fasa R wajib diisi',
            'jumper_sutm_out_fasa_r.max' => 'Panjang karakter maksimal jumper SUTM out fasa R adalah 10',

            'jumper_sutm_out_fasa_s.required' => 'Jumper SUTM out fasa S wajib diisi',
            'jumper_sutm_out_fasa_s.max' => 'Panjang karakter maksimal jumper SUTM out fasa S adalah 10',

            'jumper_sutm_out_fasa_t.required' => 'Jumper SUTM out fasa T wajib diisi',
            'jumper_sutm_out_fasa_t.max' => 'Panjang karakter maksimal jumper SUTM out fasa T adalah 10',

            'cond_sutm_co_fasa_r.required' => 'Cond SUTM CO fasa R wajib diisi',
            'cond_sutm_co_fasa_r.max' => 'Panjang karakter maksimal cond SUTM CO fasa R adalah 10',

            'cond_sutm_co_fasa_s.required' => 'Cond SUTM CO fasa S wajib diisi',
            'cond_sutm_co_fasa_s.max' => 'Panjang karakter maksimal cond SUTM CO fasa S adalah 10',

            'cond_sutm_co_fasa_t.required' => 'Cond SUTM CO fasa T wajib diisi',
            'cond_sutm_co_fasa_t.max' => 'Panjang karakter maksimal cond SUTM CO fasa T adalah 10',

            'jumper_sutm_co_income_fasa_r.required' => 'Jumper SUTM CO income fasa R wajib diisi',
            'jumper_sutm_co_income_fasa_r.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa R adalah 10',

            'jumper_sutm_co_income_fasa_s.required' => 'Jumper SUTM CO income fasa S wajib diisi',
            'jumper_sutm_co_income_fasa_s.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa S adalah 10',

            'jumper_sutm_co_income_fasa_t.required' => 'Jumper SUTM CO income fasa T wajib diisi',
            'jumper_sutm_co_income_fasa_t.max' => 'Panjang karakter maksimal jumper SUTM CO income fasa T adalah 10',

            'fuse_link_fasa_r.required' => 'Fuse link fasa R wajib diisi',
            'fuse_link_fasa_r.integer' => 'Fuse link fasa R harus berupa angka',
            'fuse_link_fasa_r.max' => 'Nilai maksimal fuse link fasa R adalah 32.767',

            'fuse_link_fasa_s.required' => 'Fuse link fasa S wajib diisi',
            'fuse_link_fasa_s.integer' => 'Fuse link fasa S harus berupa angka',
            'fuse_link_fasa_s.max' => 'Nilai maksimal fuse link fasa S adalah 32.767',

            'fuse_link_fasa_t.required' => 'Fuse link fasa T wajib diisi',
            'fuse_link_fasa_t.integer' => 'Fuse link fasa T harus berupa angka',
            'fuse_link_fasa_t.max' => 'Nilai maksimal fuse link fasa T adalah 32.767',

            'keramik_polimer.required' => 'Keramik polimer wajib diisi',
            'keramik_polimer.max' => 'Panjang karakter maksimal keramik polimer adalah 10',

            'jumper_co_trafo_primer_out_fasa_r.required' => 'Jumper CO trafo primer out fasa R wajib diisi',
            'jumper_co_trafo_primer_out_fasa_r.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa R adalah 10',

            'jumper_co_trafo_primer_out_fasa_s.required' => 'Jumper CO trafo primer out fasa S wajib diisi',
            'jumper_co_trafo_primer_out_fasa_s.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa S adalah 10',

            'jumper_co_trafo_primer_out_fasa_t.required' => 'Jumper CO trafo primer out fasa T wajib diisi',
            'jumper_co_trafo_primer_out_fasa_t.max' => 'Panjang karakter maksimal jumper CO trafo primer out fasa T adalah 10',

            'cond_co_trafo_bush_primer_fasa_r.required' => 'Cond CO trafo bush primer fasa R wajib diisi',
            'cond_co_trafo_bush_primer_fasa_r.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa R adalah 10',

            'cond_co_trafo_bush_primer_fasa_s.required' => 'Cond CO trafo bush primer fasa S wajib diisi',
            'cond_co_trafo_bush_primer_fasa_s.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa S adalah 10',

            'cond_co_trafo_bush_primer_fasa_t.required' => 'Cond CO trafo bush primer fasa T wajib diisi',
            'cond_co_trafo_bush_primer_fasa_t.max' => 'Panjang karakter maksimal cond CO trafo bush primer fasa T adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_r.required' => 'Jumper CO bush trafo primer income fasa R wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_r.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa R adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_s.required' => 'Jumper CO bush trafo primer income fasa S wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_s.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa S adalah 10',

            'jumper_co_bush_trafo_primer_income_fasa_t.required' => 'Jumper CO bush trafo primer income fasa T wajib diisi',
            'jumper_co_bush_trafo_primer_income_fasa_t.max' => 'Panjang karakter maksimal jumper CO bush trafo primer income fasa T adalah 10',

            'jumper_bush_primer_out_arester_fasa_r.required' => 'Jumper bush primer out arester fasa R wajib diisi',
            'jumper_bush_primer_out_arester_fasa_r.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa R adalah 10',

            'jumper_bush_primer_out_arester_fasa_s.required' => 'Jumper bush primer out arester fasa S wajib diisi',
            'jumper_bush_primer_out_arester_fasa_s.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa S adalah 10',

            'jumper_bush_primer_out_arester_fasa_t.required' => 'Jumper bush primer out arester fasa T wajib diisi',
            'jumper_bush_primer_out_arester_fasa_t.max' => 'Panjang karakter maksimal jumper bush primer out arester fasa T adalah 10',

            'cond_bush_primer_arester_fasa_r.required' => 'Cond bush primer arester fasa R wajib diisi',
            'cond_bush_primer_arester_fasa_r.max' => 'Panjang karakter maksimal cond bush primer arester fasa R adalah 10',

            'cond_bush_primer_arester_fasa_s.required' => 'Cond bush primer arester fasa S wajib diisi',
            'cond_bush_primer_arester_fasa_s.max' => 'Panjang karakter maksimal cond bush primer arester fasa S adalah 10',

            'cond_bush_primer_arester_fasa_t.required' => 'Cond bush primer arester fasa T wajib diisi',
            'cond_bush_primer_arester_fasa_t.max' => 'Panjang karakter maksimal cond bush primer arester fasa T adalah 10',

            'jumper_bush_primer_income_arester_fasa_r.required' => 'Jumper bush primer income arester fasa R wajib diisi',
            'jumper_bush_primer_income_arester_fasa_r.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa R adalah 10',

            'jumper_bush_primer_income_arester_fasa_s.required' => 'Jumper bush primer income arester fasa S wajib diisi',
            'jumper_bush_primer_income_arester_fasa_s.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa S adalah 10',

            'jumper_bush_primer_income_arester_fasa_t.required' => 'Jumper bush primer income arester fasa T wajib diisi',
            'jumper_bush_primer_income_arester_fasa_t.max' => 'Panjang karakter maksimal jumper bush primer income arester fasa T adalah 10',

            'arester_fasa_r.required' => 'Arester fasa R wajib diisi',
            'arester_fasa_r.in' => 'Arester fasa R hanya bisa di isi ada/ tidak ada',

            'arester_fasa_s.required' => 'Arester fasa S wajib diisi',
            'arester_fasa_r.in' => 'Arester fasa S hanya bisa di isi ada/ tidak ada',

            'arester_fasa_t.required' => 'Arester fasa T wajib diisi',
            'arester_fasa_t.in' => 'Arester fasa T hanya bisa di isi ada/ tidak ada',

            'keramik_polimer_lighting_arester.required' => 'Keramik polimer lighting arester wajib diisi',
            'keramik_polimer_lighting_arester.max' => 'Panjang karakter maksimal keramik polimer lighting arester adalah 10',

            'jumper_dudukan_arester_fasa_r.required' => 'Jumper dudukan arester fasa R wajib diisi',
            'jumper_dudukan_arester_fasa_r.max' => 'Panjang karakter maksimal jumper dudukan arester fasa R adalah 10',

            'jumper_dudukan_arester_fasa_s.required' => 'Jumper dudukan arester fasa S wajib diisi',
            'jumper_dudukan_arester_fasa_s.max' => 'Panjang karakter maksimal jumper dudukan arester fasa S adalah 10',

            'jumper_dudukan_arester_fasa_t.required' => 'Jumper dudukan arester fasa T wajib diisi',
            'jumper_dudukan_arester_fasa_t.max' => 'Panjang karakter maksimal jumper dudukan arester fasa T adalah 10',

            'cond_dudukan_la.required' => 'Cond dudukan LA wajib diisi',
            'cond_dudukan_la.max' => 'Panjang karakter maksimal cond dudukan LA adalah 10',

            'jumper_body_trf_la.required' => 'Jumper body TRF LA wajib diisi',
            'jumper_body_trf_la.max' => 'Panjang karakter maksimal jumper body TRF LA adalah 10',

            'cond_body_trf_la.required' => 'Cond body TRF LA wajib diisi',
            'cond_body_trf_la.max' => 'Panjang karakter maksimal cond body TRF LA adalah 10',

            'jumper_cond_la_dg_body_trf.required' => 'Jumper cond LA dg body TRF wajib diisi',
            'jumper_cond_la_dg_body_trf.max' => 'Panjang karakter maksimal jumper cond LA dg body TRF adalah 10',

            'cond_ground_la_panel.required' => 'Cond ground LA panel wajib diisi',
            'cond_ground_la_panel.max' => 'Panjang karakter maksimal cond ground LA panel adalah 10',

            'isolasi_fasa_r.required' => 'Isolasi fasa R wajib diisi',
            'isolasi_fasa_r.max' => 'Panjang karakter maksimal isolasi fasa R adalah 10',

            'isolasi_fasa_s.required' => 'Isolasi fasa S wajib diisi',
            'isolasi_fasa_s.max' => 'Panjang karakter maksimal isolasi fasa S adalah 10',

            'isolasi_fasa_t.required' => 'Isolasi fasa T wajib diisi',
            'isolasi_fasa_t.max' => 'Panjang karakter maksimal isolasi fasa T adalah 10',

            'arus_bocor.required' => 'Arus bocor wajib diisi',
            'arus_bocor.numeric' => 'Arus bocor harus berupa angka',
            'arus_bocor.between' => 'Arus bocor harus antara 0 sampai 999.99',
            'arus_bocor.regex' => 'Sistem hanya menerima data arus bocor dengan 2 angka desimal, contoh max input 999.99',

            'jumper_trf_bush_skunder_4x_panel.required' => 'Jumper TRF bush skunder 4x panel wajib diisi',
            'jumper_trf_bush_skunder_4x_panel.max' => 'Panjang karakter maksimal jumper TRF bush skunder 4x panel adalah 10',

            'cond_out_trf_panel.required' => 'Cond out TRF panel wajib diisi',
            'cond_out_trf_panel.max' => 'Panjang karakter maksimal cond out TRF panel adalah 10',

            'tahanan_isolasi_pp.required' => 'Tahanan isolasi PP wajib diisi',
            'tahanan_isolasi_pp.integer' => 'Tahanan isolasi PP harus berupa angka',
            'tahanan_isolasi_pp.max' => 'Nilai maksimal tahanan isolasi PP adalah 32.767',

            'tahanan_isolasi_pg.required' => 'Tahanan isolasi PG wajib diisi',
            'tahanan_isolasi_pg.integer' => 'Tahanan isolasi PG harus berupa angka',
            'tahanan_isolasi_pg.max' => 'Nilai maksimal tahanan isolasi PG adalah 32.767',

            'jumper_in_panel_saklar.required' => 'Jumper in panel saklar wajib diisi',
            'jumper_in_panel_saklar.max' => 'Panjang karakter maksimal jumper in panel saklar adalah 10',

            'jumper_in_nol.required' => 'Jumper in nol wajib diisi',
            'jumper_in_nol.max' => 'Panjang karakter maksimal jumper in nol adalah 10',

            'jumper_nol_ground.required' => 'Jumper nol ground wajib diisi',
            'jumper_nol_ground.max' => 'Panjang karakter maksimal jumper nol ground adalah 10',

            'jenis_saklar_utama.required' => 'Jenis saklar utama wajib diisi',
            'jenis_saklar_utama.max' => 'Panjang karakter maksimal jenis saklar utama adalah 10',

            'jumper_dr_saklar_out.required' => 'Jumper dr saklar out wajib diisi',
            'jumper_dr_saklar_out.max' => 'Panjang karakter maksimal jumper dr saklar out adalah 10',

            'jenis_cond_dr_saklar_nh_utama.required' => 'Jenis cond dr saklar NH utama wajib diisi',
            'jenis_cond_dr_saklar_nh_utama.max' => 'Panjang karakter maksimal jenis cond dr saklar NH utama adalah 10',

            'data_proteksi_utama_fasa_r.required' => 'Data proteksi utama fasa R wajib diisi',
            'data_proteksi_utama_fasa_r.max' => 'Panjang karakter maksimal data proteksi utama fasa R adalah 10',

            'data_proteksi_utama_fasa_s.required' => 'Data proteksi utama fasa S wajib diisi',
            'data_proteksi_utama_fasa_s.max' => 'Panjang karakter maksimal data proteksi utama fasa S adalah 10',

            'data_proteksi_utama_fasa_t.required' => 'Data proteksi utama fasa T wajib diisi',
            'data_proteksi_utama_fasa_t.max' => 'Panjang karakter maksimal data proteksi utama fasa T adalah 10',

            'jenis_cond_dr_nh_utama_jurusan.required' => 'Jenis cond dr NH utama jurusan wajib diisi',
            'jenis_cond_dr_nh_utama_jurusan.max' => 'Panjang karakter maksimal jenis cond dr NH utama jurusan adalah 20',

            'jumper_dr_nh_jurusan_in.required' => 'Jumper dr NH jurusan in wajib diisi',
            'jumper_dr_nh_jurusan_in.max' => 'Panjang karakter maksimal jumper dr NH jurusan in adalah 20',

            'data_proteksi_line_a_fasa_r.required' => 'Data proteksi line A fasa R wajib diisi',
            'data_proteksi_line_a_fasa_r.max' => 'Panjang karakter maksimal data proteksi line A fasa R adalah 10',

            'data_proteksi_line_a_fasa_s.required' => 'Data proteksi line A fasa S wajib diisi',
            'data_proteksi_line_a_fasa_s.max' => 'Panjang karakter maksimal data proteksi line A fasa S adalah 10',

            'data_proteksi_line_a_fasa_t.required' => 'Data proteksi line A fasa T wajib diisi',
            'data_proteksi_line_a_fasa_t.max' => 'Panjang karakter maksimal data proteksi line A fasa T adalah 10',

            'data_proteksi_line_b_fasa_r.required' => 'Data proteksi line B fasa R wajib diisi',
            'data_proteksi_line_b_fasa_r.max' => 'Panjang karakter maksimal data proteksi line B fasa R adalah 10',

            'data_proteksi_line_b_fasa_s.required' => 'Data proteksi line B fasa S wajib diisi',
            'data_proteksi_line_b_fasa_s.max' => 'Panjang karakter maksimal data proteksi line B fasa S adalah 10',

            'data_proteksi_line_b_fasa_t.required' => 'Data proteksi line B fasa T wajib diisi',
            'data_proteksi_line_b_fasa_t.max' => 'Panjang karakter maksimal data proteksi line B fasa T adalah 10',

            'data_proteksi_line_c_fasa_r.required' => 'Data proteksi line C fasa R wajib diisi',
            'data_proteksi_line_c_fasa_r.max' => 'Panjang karakter maksimal data proteksi line C fasa R adalah 10',

            'data_proteksi_line_c_fasa_s.required' => 'Data proteksi line C fasa S wajib diisi',
            'data_proteksi_line_c_fasa_s.max' => 'Panjang karakter maksimal data proteksi line C fasa S adalah 10',

            'data_proteksi_line_c_fasa_t.required' => 'Data proteksi line C fasa T wajib diisi',
            'data_proteksi_line_c_fasa_t.max' => 'Panjang karakter maksimal data proteksi line C fasa T adalah 10',

            'data_proteksi_line_d_fasa_r.required' => 'Data proteksi line D fasa R wajib diisi',
            'data_proteksi_line_d_fasa_r.max' => 'Panjang karakter maksimal data proteksi line D fasa R adalah 10',

            'data_proteksi_line_d_fasa_s.required' => 'Data proteksi line D fasa S wajib diisi',
            'data_proteksi_line_d_fasa_s.max' => 'Panjang karakter maksimal data proteksi line D fasa S adalah 10',

            'data_proteksi_line_d_fasa_t.required' => 'Data proteksi line D fasa T wajib diisi',
            'data_proteksi_line_d_fasa_t.max' => 'Panjang karakter maksimal data proteksi line D fasa T adalah 10',

            'jumper_out_dr_nh_jurusan_cond_out_jtr.required' => 'Jumper out dr NH jurusan cond out JTR wajib diisi',
            'jumper_out_dr_nh_jurusan_cond_out_jtr.max' => 'Panjang karakter maksimal jumper out dr NH jurusan cond out JTR adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_a.required' => 'Cond dr NH jurusan out JTR line A wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_a.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line A adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_b.required' => 'Cond dr NH jurusan out JTR line B wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_b.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line B adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_c.required' => 'Cond dr NH jurusan out JTR line C wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_c.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line C adalah 10',

            'cond_dr_nh_jurusan_out_jtr_line_d.required' => 'Cond dr NH jurusan out JTR line D wajib diisi',
            'cond_dr_nh_jurusan_out_jtr_line_d.max' => 'Panjang karakter maksimal cond dr NH jurusan out JTR line D adalah 10',

            'cond_jurusan_jtr_line_a.required' => 'Cond jurusan JTR line A wajib diisi',
            'cond_jurusan_jtr_line_a.max' => 'Panjang karakter maksimal cond jurusan JTR line A adalah 10',

            'cond_jurusan_jtr_line_b.required' => 'Cond jurusan JTR line B wajib diisi',
            'cond_jurusan_jtr_line_b.max' => 'Panjang karakter maksimal cond jurusan JTR line B adalah 10',

            'cond_jurusan_jtr_line_c.required' => 'Cond jurusan JTR line C wajib diisi',
            'cond_jurusan_jtr_line_c.max' => 'Panjang karakter maksimal cond jurusan JTR line C adalah 10',

            'cond_jurusan_jtr_line_d.required' => 'Cond jurusan JTR line D wajib diisi',
            'cond_jurusan_jtr_line_d.max' => 'Panjang karakter maksimal cond jurusan JTR line D adalah 10',

            'jumper_la_body_panel.required' => 'Jumper LA body panel wajib diisi',
            'jumper_la_body_panel.max' => 'Panjang karakter maksimal jumper LA body panel adalah 10',

            'cond_dr_ground_la_body.required' => 'Cond dr ground LA body wajib diisi',
            'cond_dr_ground_la_body.max' => 'Panjang karakter maksimal cond dr ground LA body adalah 10',

            'cond_dr_nol_ground.required' => 'Cond dr nol ground wajib diisi',
            'cond_dr_nol_ground.max' => 'Panjang karakter maksimal cond dr nol ground adalah 10',

            'cond_dr_kopel_body_dg_la_ground.required' => 'Cond dr kopel body dg LA ground wajib diisi',
            'cond_dr_kopel_body_dg_la_ground.max' => 'Panjang karakter maksimal cond dr kopel body dg LA ground adalah 10',

            'nilai_r_tanah_nol.required' => 'Nilai R tanah nol wajib diisi',
            'nilai_r_tanah_nol.integer' => 'Nilai R tanah nol harus berupa angka',
            'nilai_r_tanah_nol.max' => 'Nilai maksimal R tanah nol adalah 32.767',

            'nilai_r_tanah_la.required' => 'Nilai R tanah LA wajib diisi',
            'nilai_r_tanah_la.integer' => 'Nilai R tanah LA harus berupa angka',
            'nilai_r_tanah_la.max' => 'Nilai maksimal R tanah LA adalah 32.767',

            'panel_gtt_pintu.required' => 'Panel GTT pintu wajib diisi',
            'panel_gtt_pintu.max' => 'Panjang karakter maksimal panel GTT pintu adalah 10',

            'panel_gtt_kunci.required' => 'Panel GTT kunci wajib diisi',
            'panel_gtt_kunci.max' => 'Panjang karakter maksimal panel GTT kunci adalah 10',

            'panel_gtt_no_gtt.required' => 'Panel GTT no GTT wajib diisi',
            'panel_gtt_no_gtt.max' => 'Panjang karakter maksimal panel GTT no GTT adalah 10',

            'panel_gtt_kondisi.required' => 'Panel GTT kondisi wajib diisi',
            'panel_gtt_kondisi.max' => 'Panjang karakter maksimal panel GTT kondisi adalah 10',

            'panel_gtt_lubang_pipa.required' => 'Panel GTT lubang pipa wajib diisi',
            'panel_gtt_lubang_pipa.max' => 'Panjang karakter maksimal panel GTT lubang pipa adalah 10',

            'panel_gtt_pondasi.required' => 'Panel GTT pondasi wajib diisi',
            'panel_gtt_pondasi.max' => 'Panjang karakter maksimal panel GTT pondasi adalah 10',

            'panel_gtt_tanda_peringatan.required' => 'Panel GTT tanda peringatan wajib diisi',
            'panel_gtt_tanda_peringatan.max' => 'Panjang karakter maksimal panel GTT tanda peringatan adalah 10',

            'panel_gtt_jenis_gardu.required' => 'Panel GTT jenis gardu wajib diisi',
            'panel_gtt_jenis_gardu.max' => 'Panjang karakter maksimal panel GTT jenis gardu adalah 10',

            'panel_gtt_tgl_inspeksi.required' => 'Panel GTT tanggal inspeksi wajib diisi',
            'panel_gtt_tgl_inspeksi.date' => 'Panel GTT tanggal inspeksi harus berupa tanggal yang valid',

            'panel_gtt_insp_siang.required' => 'Panel GTT inspeksi siang wajib diisi',
            'panel_gtt_insp_siang.max' => 'Panjang karakter maksimal panel GTT inspeksi siang adalah 10',

            'panel_gtt_pekerjaan_pemeliharaan.max' => 'Panjang karakter maksimal panel GTT pekerjaan pemeliharaan adalah 50',

            'panel_gtt_catatan.max' => 'Panjang karakter maksimal panel GTT catatan adalah 10',

            'tahan_isolasi_trafo_1_pb.required' => 'Tahan solasi trafo 1 p-b wajib di isi',
            'tahan_isolasi_trafo_1_sb.integer' => 'Tahan solasi trafo 1 p-b harus berupa angka',
            'tahan_isolasi_trafo_1_ps.max' => 'Nilai maksimal Tahan solasi trafo 1 p-b adalah 32.767',
            'tahan_isolasi_trafo_2_pb.required' => 'Tahan solasi trafo 2 p-b wajib di isi',
            'tahan_isolasi_trafo_2_sb.integer' => 'Tahan solasi trafo 2 p-b harus berupa angka',
            'tahan_isolasi_trafo_2_ps.max' => 'Nilai maksimal Tahan solasi trafo 2 p-b adalah 32.767',
            'tahan_isolasi_trafo_3_pb.required' => 'Tahan solasi trafo 3 p-b wajib di isi',
            'tahan_isolasi_trafo_3_sb.integer' => 'Tahan solasi trafo 3 p-b harus berupa angka',
            'tahan_isolasi_trafo_3_ps.max' => 'Nilai maksimal Tahan solasi trafo 3 p-b adalah 32.767',

            'keterangan_history.string' => 'Keterangan history harus berupa teks',
            'keterangan_history.max' => 'Panjang karakter maksimal keterangan history adalah 20',
        ];


        try {
        $validated = \Validator::make($request->all(), $rules, $messages)->validate();
    } catch (ValidationException $e) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors()
            ], 422);
        }
        throw $e;
    }

    // --- Helper normalisasi utk KOMPARASI fair (dipakai ke lama & baru)
    $normalize = function(array $a): array {
        foreach ($a as $k => $v) {
            // string: trim + collapse spaces, kosong -> null
            if (is_string($v)) {
                $v = trim(preg_replace('/\s+/u', ' ', $v));
                if ($v === '') $v = null;
            }

            // tanggal2: samakan format
            if ($k === 'waktu_pemeliharaan' && $v) {
                try { $v = Carbon::parse($v)->format('Y-m-d H:i'); } catch (\Throwable $e) {}
                // HANYA sampai menit, agar perbedaan detik akibat <input datetime-local> diabaikan
            }
            if ($k === 'panel_gtt_tgl_inspeksi' && $v) {
                try { $v = Carbon::parse($v)->format('Y-m-d'); } catch (\Throwable $e) {}
            }

            // numerik spesifik
            if ($k === 'arus_bocor' && $v !== null && $v !== '') {
                $v = number_format((float)$v, 2, '.', '');
            }

            // integer kolom tertentu (maks 32767) – biarkan PHP cast ke int bila numeric
            if (preg_match('/^(fuse_link_|tahanan_isolasi_|nilai_r_tanah_|tahan_isolasi_trafo_)/', $k) && $v !== null && $v !== '') {
                if (is_numeric($v)) $v = (int)$v;
            }

            $a[$k] = $v;
        }
        return $a;
    };

    try {
        DB::beginTransaction();

        // Ambil hanya kolom yang fillable saja
        $fillable = $pemeliharaan->getFillable();
        $dataBaru   = array_intersect_key($validated, array_flip($fillable));
        $dataLama   = $pemeliharaan->only(array_keys($dataBaru));

        // Normalisasi KEDUA sisi
        $normBaru = $normalize($dataBaru);
        $normLama = $normalize($dataLama);

        // Bandingkan per kolom
        $adaPerubahan = false;
        foreach ($normBaru as $k => $vBaru) {
            $vLama = $normLama[$k] ?? null;
            if ($vBaru !== $vLama) { $adaPerubahan = true; break; }
        }

        if (!$adaPerubahan) {
            DB::rollBack();
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Tidak ada perubahan data, update dibatalkan'], 200)
                : back()->with('info', 'Tidak ada perubahan data, update dibatalkan.');
        }

        // Simpan perubahan asli (bukan yang dipotong menit dll) supaya data tetap utuh
        $pemeliharaan->update($dataBaru);

        // Snapshot baru + normalisasi utk history
        $afterRaw   = $pemeliharaan->fresh()->only(array_keys($dataBaru));
        $normAfter  = $normalize($afterRaw);

        // History (pastikan model History sudah punya data_baru)
        HistoryDataPemeliharaan::create([
            'id_pemeliharaan' => $pemeliharaan->id,
            'data_lama'       => json_encode($normLama, JSON_UNESCAPED_UNICODE),
            'data_baru'       => json_encode($normAfter, JSON_UNESCAPED_UNICODE),
            'aksi'            => 'update',
            'diubah_oleh'     => auth()->user()->name ?? '-',
            'keterangan'      => $validated['keterangan_history'] ?? null,
        ]);

        DB::commit();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pemeliharaan berhasil diperbarui',
                'data'    => $pemeliharaan
            ], 200);
        }

        return redirect()
            ->route('pemeliharaan.create', ['kd_gardu' => $pemeliharaan->kd_gardu])
            ->with('success', 'Data pemeliharaan berhasil diperbarui.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Pemeliharaan Update Error', ['message' => $e->getMessage()]);
        return $request->wantsJson()
            ? response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui data', 'error' => (config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan internal')], 500)
            : back()->with('error', config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat memperbarui data.');
    }
}


    public function show($id)
    {
        try {
            // Ambil data omt pengukuran beserta 1 history perubahan terbaru
            $pemeliharaan = Pemeliharaan::with(['history' => function ($q) {
                $q->latest()->limit(1);
            }])->find($id);

            if (!$pemeliharaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data omt pengukuran tidak ditemukan'
                ], 404);
            }

            $dataSaatIni = $pemeliharaan->toArray();

            $dataYangDirubah = [];

            foreach ($pemeliharaan->history as $history) {
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
            $pemeliharaan = Pemeliharaan::find($id);

            if (!$pemeliharaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemeliharaan tidak ditemukan'
                ], 404);
            }

            $pemeliharaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pemeliharaan berhasil dihapus. History tetap tersimpan.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data pemeliharaan',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Terjadi kesalahan internal'
            ], 500);
        }
    }
    public function historyIndex(Request $request)
{
    $perPage = max(5, min((int)$request->get('per_page', 20), 100));
    $q = trim((string)$request->get('q', ''));

    $histTable = (new \App\Models\HistoryData\HistoryDataPemeliharaan())->getTable(); // ex: history_pemeliharaan
    $pmTable   = (new \App\Models\ManajemenData\Pemeliharaan())->getTable();            // ex: pemeliharaan
    $dgTable   = (new \App\Models\ManajemenData\DataGardu())->getTable();               // ex: data_gardu

    // subquery rekap per id_pemeliharaan
    $recapSub = \App\Models\HistoryData\HistoryDataPemeliharaan::select([
            'id_pemeliharaan',
            DB::raw('MAX(id) as last_id'),
            DB::raw('MAX(created_at) as last_at'),
            DB::raw('COUNT(*) as total_logs'),
        ])
        ->groupBy('id_pemeliharaan');

    $query = DB::query()
        ->fromSub($recapSub, 'recaps')
        ->join("$histTable as hlast", 'hlast.id', '=', 'recaps.last_id')
        ->leftJoin("$pmTable as pm", 'pm.id', '=', 'recaps.id_pemeliharaan')
        ->leftJoin("$dgTable as dg", 'dg.kd_gardu', '=', 'pm.kd_gardu')
        ->select([
            'recaps.id_pemeliharaan',
            'recaps.total_logs',
            'recaps.last_at',
            'hlast.aksi as last_aksi',
            'hlast.diubah_oleh as last_by',
            'hlast.keterangan as last_notes',
            'hlast.data_lama as last_snapshot',
            'pm.kd_gardu as kd_gardu_now',
            'pm.waktu_pemeliharaan as waktu_pemeliharaan_now',
            'dg.kd_pylg as kd_pylg_now',
            'dg.alamat as alamat_now',
            'dg.gardu_induk as gi_now',
        ])
        ->orderByDesc('recaps.last_at');

    if ($q !== '') {
        $query->where(function ($qq) use ($q) {
            $qq->where('pm.kd_gardu', 'like', "%{$q}%")
               ->orWhere('hlast.data_lama', 'like', '%"kd_gardu":"'.$q.'"%')
               ->orWhere('hlast.data_lama', 'like', '%"kd_gardu":'.$q.'%');
        });
    }

    $recaps = $query->paginate($perPage)->withQueryString();

    return view('manajemen-data.historis.history_pemeliharaan.recap_index', compact('recaps'));
}
public function historyShow(Request $request, $id)
{
    // Normalisasi & pagination
    $idPemeliharaan = (int) $id;
    $perPage = max(5, min((int) $request->get('per_page', 20), 100));

    // Data pemeliharaan saat ini (untuk header & perbandingan)
    $pemeliharaan = Pemeliharaan::find($idPemeliharaan);

    // Data gardu saat ini (join manual via kd_gardu)
    $gardu = null;
    if ($pemeliharaan && $pemeliharaan->kd_gardu) {
        $gardu = DataGardu::where('kd_gardu', $pemeliharaan->kd_gardu)->first();
    }

    // Riwayat perubahan untuk ID ini (urut terbaru dulu)
    $histories = HistoryDataPemeliharaan::where('id_pemeliharaan', $idPemeliharaan)
        ->orderByDesc('created_at')
        ->paginate($perPage)
        ->withQueryString();

    // Jika tidak ada data sama sekali, kembalikan ke rekap dengan pesan
    if (!$pemeliharaan && ($histories->isEmpty())) {
        return redirect()
            ->route('pemeliharaan.history.index')
            ->with('error', 'Riwayat untuk pemeliharaan ini tidak ditemukan.');
    }

    // Kirim ke view detail
    return view(
        'manajemen-data.historis.history_pemeliharaan.detail_by_pemeliharaan',
        compact('histories', 'pemeliharaan', 'gardu', 'idPemeliharaan')
    );
}
private function normalizeDatetime(Request $request, string $field): void
{
    $val = $request->input($field);
    if (!$val) return;

    // Ganti 'T' -> ' ' dan tambahkan detik jika belum ada
    $v = str_replace('T', ' ', $val);
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $v)) {
        $v .= ':00';
    }

    try {
        $dt = Carbon::parse($v);
        $request->merge([$field => $dt->format('Y-m-d H:i:s')]);
    } catch (\Throwable $e) {
        // biarkan validator gagal jika format benar2 tidak bisa diparse
    }
}
}
