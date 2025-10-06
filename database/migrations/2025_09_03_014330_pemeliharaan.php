<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->datetime('waktu_pemeliharaan');                        
            $table->string('kd_gardu', 10)->unique()->nullable();
            $table->foreign('kd_gardu')
                ->references('kd_gardu')
                ->on('data_gardu')
                ->nullOnDelete(); // field menjadi null saat data master terhapus
            $table->string('sutm_mm', 15);
            $table->string('jumper_sutm_out_fasa_r', 10);
            $table->string('jumper_sutm_out_fasa_s', 10);
            $table->string('jumper_sutm_out_fasa_t', 10);
            $table->string('cond_sutm_co_fasa_r', 10);
            $table->string('cond_sutm_co_fasa_s', 10);
            $table->string('cond_sutm_co_fasa_t', 10);
            $table->string('jumper_sutm_co_income_fasa_r', 10);
            $table->string('jumper_sutm_co_income_fasa_s', 10);
            $table->string('jumper_sutm_co_income_fasa_t', 10);        
            $table->smallInteger('fuse_link_fasa_r');
            $table->smallInteger('fuse_link_fasa_s');
            $table->smallInteger('fuse_link_fasa_t');
            $table->string('keramik_polimer', 10);            
            $table->string('jumper_co_trafo_primer_out_fasa_r', 10);
            $table->string('jumper_co_trafo_primer_out_fasa_s', 10);
            $table->string('jumper_co_trafo_primer_out_fasa_t', 10);
            $table->string('cond_co_trafo_bush_primer_fasa_r', 10);
            $table->string('cond_co_trafo_bush_primer_fasa_s', 10);
            $table->string('cond_co_trafo_bush_primer_fasa_t', 10);
            $table->string('jumper_co_bush_trafo_primer_income_fasa_r', 10);
            $table->string('jumper_co_bush_trafo_primer_income_fasa_s', 10);
            $table->string('jumper_co_bush_trafo_primer_income_fasa_t', 10);            
            $table->string('jumper_bush_primer_out_arester_fasa_r', 10);
            $table->string('jumper_bush_primer_out_arester_fasa_s', 10);
            $table->string('jumper_bush_primer_out_arester_fasa_t', 10);
            $table->string('cond_bush_primer_arester_fasa_r', 10);
            $table->string('cond_bush_primer_arester_fasa_s', 10);
            $table->string('cond_bush_primer_arester_fasa_t', 10);
            $table->string('jumper_bush_primer_income_arester_fasa_r', 10);
            $table->string('jumper_bush_primer_income_arester_fasa_s', 10);
            $table->string('jumper_bush_primer_income_arester_fasa_t', 10);        
            $table->enum('arester_fasa_r', ['ada', 'tidak ada']);
            $table->enum('arester_fasa_s', ['ada', 'tidak ada']);
            $table->enum('arester_fasa_t', ['ada', 'tidak ada']);
            $table->string('keramik_polimer_lighting_arester', 10);
            $table->string('jumper_dudukan_arester_fasa_r', 10);
            $table->string('jumper_dudukan_arester_fasa_s', 10);
            $table->string('jumper_dudukan_arester_fasa_t', 10);
            $table->string('cond_dudukan_la', 10);
            $table->string('jumper_body_trf_la', 10);
            $table->string('cond_body_trf_la', 10);
            $table->string('jumper_cond_la_dg_body_trf', 10);
            $table->string('cond_ground_la_panel', 10);            
            $table->smallInteger('isolasi_fasa_r');
            $table->smallInteger('isolasi_fasa_s');
            $table->smallInteger('isolasi_fasa_t');
            $table->decimal('arus_bocor', 5, 2);            
            $table->string('jumper_trf_bush_skunder_4x_panel', 10);
            $table->string('cond_out_trf_panel', 10);
            $table->smallInteger('tahanan_isolasi_pp');
            $table->smallInteger('tahanan_isolasi_pg');
            $table->string('jumper_in_panel_saklar', 10);
            $table->string('jumper_in_nol', 10);
            $table->string('jumper_nol_ground', 10);
            $table->string('jenis_saklar_utama', 10);
            $table->string('jumper_dr_saklar_out', 10);
            $table->string('jenis_cond_dr_saklar_nh_utama', 20);            
            $table->string('data_proteksi_utama_fasa_r', 10);
            $table->string('data_proteksi_utama_fasa_s', 10);
            $table->string('data_proteksi_utama_fasa_t', 10);
            $table->string('jenis_cond_dr_nh_utama_jurusan', 20);
            $table->string('jumper_dr_nh_jurusan_in', 20);        
            $table->string('data_proteksi_line_a_fasa_r', 10);
            $table->string('data_proteksi_line_a_fasa_s', 10);
            $table->string('data_proteksi_line_a_fasa_t', 10);
            $table->string('data_proteksi_line_b_fasa_r', 10);
            $table->string('data_proteksi_line_b_fasa_s', 10);
            $table->string('data_proteksi_line_b_fasa_t', 10);
            $table->string('data_proteksi_line_c_fasa_r', 10);
            $table->string('data_proteksi_line_c_fasa_s', 10);
            $table->string('data_proteksi_line_c_fasa_t', 10);
            $table->string('data_proteksi_line_d_fasa_r', 10);
            $table->string('data_proteksi_line_d_fasa_s', 10);
            $table->string('data_proteksi_line_d_fasa_t', 10);            
            $table->string('jumper_out_dr_nh_jurusan_cond_out_jtr', 10);            
            $table->string('cond_dr_nh_jurusan_out_jtr_line_a', 10);
            $table->string('cond_dr_nh_jurusan_out_jtr_line_b', 10);
            $table->string('cond_dr_nh_jurusan_out_jtr_line_c', 10);
            $table->string('cond_dr_nh_jurusan_out_jtr_line_d', 10);
            $table->string('cond_jurusan_jtr_line_a', 10);
            $table->string('cond_jurusan_jtr_line_b', 10);
            $table->string('cond_jurusan_jtr_line_c', 10);
            $table->string('cond_jurusan_jtr_line_d', 10);            
            $table->string('jumper_la_body_panel', 10);
            $table->string('cond_dr_ground_la_body', 10);
            $table->string('cond_dr_nol_ground', 10);
            $table->string('cond_dr_kopel_body_dg_la_ground', 10);
            $table->smallInteger('nilai_r_tanah_nol');
            $table->smallInteger('nilai_r_tanah_la');            
            $table->string('panel_gtt_pintu', 10);
            $table->string('panel_gtt_kunci', 10);
            $table->string('panel_gtt_no_gtt', 10);
            $table->string('panel_gtt_kondisi', 10);
            $table->string('panel_gtt_lubang_pipa', 10);
            $table->string('panel_gtt_pondasi', 10);
            $table->string('panel_gtt_tanda_peringatan', 10);
            $table->string('panel_gtt_jenis_gardu', 10);            
            $table->date('panel_gtt_tgl_inspeksi');//perubuahan tipe data
            $table->string('panel_gtt_insp_siang', 10);
            $table->string('panel_gtt_pekerjaan_pemeliharaan', 50);
            $table->string('panel_gtt_catatan', 50)->nullable();
            $table->smallInteger('tahan_isolasi_trafo_1_pb');
            $table->smallInteger('tahan_isolasi_trafo_1_sb');
            $table->smallInteger('tahan_isolasi_trafo_1_ps');
            $table->smallInteger('tahan_isolasi_trafo_2_pb');
            $table->smallInteger('tahan_isolasi_trafo_2_sb');
            $table->smallInteger('tahan_isolasi_trafo_2_ps');
            $table->smallInteger('tahan_isolasi_trafo_3_pb');
            $table->smallInteger('tahan_isolasi_trafo_3_sb');
            $table->smallInteger('tahan_isolasi_trafo_3_ps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};
