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
        Schema::create('data_gardu', function (Blueprint $table) {
            $table->id();
            $table->enum('gardu_induk', ['banyuwangi', 'genteng']);
            $table->enum('kd_trf_gi', ['1', '2', '3', '4']);
            $table->string('kd_pylg', 20);
            $table->string('kd_gardu', 10)->unique();
            $table->smallInteger('daya_trafo');
            $table->tinyInteger('jml_trafo');
            $table->string('alamat', 30);
            $table->string('desa', 20);
            $table->string('no_seri', 20);
            $table->smallInteger('berat_total');
            $table->smallInteger('berat_minyak');
            $table->string('hubungan', 10);
            $table->decimal('impedansi', 3, 1);
            $table->smallInteger('tegangan_tm');
            $table->smallInteger('tegangan_tr');
            $table->string('frekuensi', 20);
            $table->year('tahun');
            $table->string('merek_trafo', 30);
            $table->decimal('beban_kva_trafo', 5, 1)->default(0);
            $table->decimal('persentase_beban', 4, 1)->default(0);
            $table->string('section_lbs', 30);
            $table->tinyInteger('fasa');
            $table->smallInteger('nilai_sdk_utama');
            $table->decimal('nilai_primer', 3, 1);
            $table->tinyInteger('tap_no');
            $table->decimal('tap_kv', 3, 1);            
            $table->enum('rekondisi_preman', ['rek', 'pre']);
            $table->enum('bengkel', ['wep', 'mar']);
            $table->string('merek_trafo_2', 30)->nullable();//update field baru
            $table->string('merek_trafo_3', 30)->nullable();
            $table->string('no_seri_2', 20)->nullable();
            $table->string('no_seri_3', 20)->nullable();
            $table->year('tahun_2')->nullable();
            $table->year('tahun_3')->nullable();
            $table->smallInteger('berat_minyak_2')->nullable();
            $table->smallInteger('berat_minyak_3')->nullable();
            $table->smallInteger('berat_total_2')->nullable();
            $table->smallInteger('berat_total_3')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gardu');
    }
};
