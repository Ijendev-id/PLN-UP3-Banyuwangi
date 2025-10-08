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
        Schema::create('omt_pengukuran', function (Blueprint $table) {
            $table->id();            
            $table->string('kd_gardu', 10)->unique()->nullable();
            $table->foreign('kd_gardu')
                ->references('kd_gardu')
                ->on('data_gardu')
                ->nullOnDelete(); // field menjadi null saat data master terhapus            
            $table->smallInteger('ian')->default(0);
            $table->smallInteger('iar')->default(0);
            $table->smallInteger('ias')->default(0);
            $table->smallInteger('iat')->default(0);
            $table->smallInteger('ibn')->default(0);
            $table->smallInteger('ibr')->default(0);
            $table->smallInteger('ibs')->default(0);
            $table->smallInteger('ibt')->default(0);
            $table->smallInteger('icn')->default(0);
            $table->smallInteger('icr')->default(0);
            $table->smallInteger('ics')->default(0);
            $table->smallInteger('ict')->default(0);
            $table->smallInteger('idn')->default(0);
            $table->smallInteger('idr')->default(0);
            $table->smallInteger('ids')->default(0);
            $table->smallInteger('idt')->default(0);
            $table->smallInteger('vrn');
            $table->smallInteger('vrs');
            $table->smallInteger('vsn');
            $table->smallInteger('vst');
            $table->smallInteger('vtn');
            $table->smallInteger('vtr');
            $table->datetime('waktu_pengukuran');
            $table->smallInteger('iun'); //update field baru
            $table->smallInteger('iur');
            $table->smallInteger('ius');
            $table->smallInteger('iut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omt_pengukuran');
    }
};
