<?php

namespace App\Models\ManajemenData;

use App\Models\HistoryData\HistoryDataPemeliharaan;
use App\Models\ManajemenData\DataGardu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaan';

    protected $fillable = [
        'waktu_pemeliharaan',
        'kd_gardu',
        'sutm_mm',
        'jumper_sutm_out_fasa_r',
        'jumper_sutm_out_fasa_s',
        'jumper_sutm_out_fasa_t',
        'cond_sutm_co_fasa_r',
        'cond_sutm_co_fasa_s',
        'cond_sutm_co_fasa_t',
        'jumper_sutm_co_income_fasa_r',
        'jumper_sutm_co_income_fasa_s',
        'jumper_sutm_co_income_fasa_t',
        'fuse_link_fasa_r',
        'fuse_link_fasa_s',
        'fuse_link_fasa_t',
        'keramik_polimer',
        'jumper_co_trafo_primer_out_fasa_r',
        'jumper_co_trafo_primer_out_fasa_s',
        'jumper_co_trafo_primer_out_fasa_t',
        'cond_co_trafo_bush_primer_fasa_r',
        'cond_co_trafo_bush_primer_fasa_s',
        'cond_co_trafo_bush_primer_fasa_t',
        'jumper_co_bush_trafo_primer_income_fasa_r',
        'jumper_co_bush_trafo_primer_income_fasa_s',
        'jumper_co_bush_trafo_primer_income_fasa_t',
        'jumper_bush_primer_out_arester_fasa_r',
        'jumper_bush_primer_out_arester_fasa_s',
        'jumper_bush_primer_out_arester_fasa_t',
        'cond_bush_primer_arester_fasa_r',
        'cond_bush_primer_arester_fasa_s',
        'cond_bush_primer_arester_fasa_t',
        'jumper_bush_primer_income_arester_fasa_r',
        'jumper_bush_primer_income_arester_fasa_s',
        'jumper_bush_primer_income_arester_fasa_t',
        'arester_fasa_r',
        'arester_fasa_s',
        'arester_fasa_t',
        'keramik_polimer_lighting_arester',
        'jumper_dudukan_arester_fasa_r',
        'jumper_dudukan_arester_fasa_s',
        'jumper_dudukan_arester_fasa_t',
        'cond_dudukan_la',
        'jumper_body_trf_la',
        'cond_body_trf_la',
        'jumper_cond_la_dg_body_trf',
        'cond_ground_la_panel',
        'isolasi_fasa_r',
        'isolasi_fasa_s',
        'isolasi_fasa_t',
        'arus_bocor',
        'jumper_trf_bush_skunder_4x_panel',
        'cond_out_trf_panel',
        'tahanan_isolasi_pp',
        'tahanan_isolasi_pg',
        'jumper_in_panel_saklar',
        'jumper_in_nol',
        'jumper_nol_ground',
        'jenis_saklar_utama',
        'jumper_dr_saklar_out',
        'jenis_cond_dr_saklar_nh_utama',
        'data_proteksi_utama_fasa_r',
        'data_proteksi_utama_fasa_s',
        'data_proteksi_utama_fasa_t',
        'jenis_cond_dr_nh_utama_jurusan',
        'jumper_dr_nh_jurusan_in',
        'data_proteksi_line_a_fasa_r',
        'data_proteksi_line_a_fasa_s',
        'data_proteksi_line_a_fasa_t',
        'data_proteksi_line_b_fasa_r',
        'data_proteksi_line_b_fasa_s',
        'data_proteksi_line_b_fasa_t',
        'data_proteksi_line_c_fasa_r',
        'data_proteksi_line_c_fasa_s',
        'data_proteksi_line_c_fasa_t',
        'data_proteksi_line_d_fasa_r',
        'data_proteksi_line_d_fasa_s',
        'data_proteksi_line_d_fasa_t',
        'jumper_out_dr_nh_jurusan_cond_out_jtr',
        'cond_dr_nh_jurusan_out_jtr_line_a',
        'cond_dr_nh_jurusan_out_jtr_line_b',
        'cond_dr_nh_jurusan_out_jtr_line_c',
        'cond_dr_nh_jurusan_out_jtr_line_d',
        'cond_jurusan_jtr_line_a',
        'cond_jurusan_jtr_line_b',
        'cond_jurusan_jtr_line_c',
        'cond_jurusan_jtr_line_d',
        'jumper_la_body_panel',
        'cond_dr_ground_la_body',
        'cond_dr_nol_ground',
        'cond_dr_kopel_body_dg_la_ground',
        'nilai_r_tanah_nol',
        'nilai_r_tanah_la',
        'panel_gtt_pintu',
        'panel_gtt_kunci',
        'panel_gtt_no_gtt',
        'panel_gtt_kondisi',
        'panel_gtt_lubang_pipa',
        'panel_gtt_pondasi',
        'panel_gtt_tanda_peringatan',
        'panel_gtt_jenis_gardu',
        'panel_gtt_tgl_inspeksi',
        'panel_gtt_insp_siang',
        'panel_gtt_pekerjaan_pemeliharaan',
        'panel_gtt_catatan',
        'tahan_isolasi_trafo_1_pb',
        'tahan_isolasi_trafo_1_sb',
        'tahan_isolasi_trafo_1_ps',
        'tahan_isolasi_trafo_2_pb',
        'tahan_isolasi_trafo_2_sb',
        'tahan_isolasi_trafo_2_ps',
        'tahan_isolasi_trafo_3_pb',
        'tahan_isolasi_trafo_3_sb',
        'tahan_isolasi_trafo_3_ps',
    ];

    public function gardu()
    {
        return $this->belongsTo(DataGardu::class, 'kd_gardu', 'kd_gardu');
    }

    public function history()
    {
        return $this->hasMany(HistoryDataPemeliharaan::class, 'id_pemeliharaan');
    }
}
