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
            $table->smallInteger('ian');
            $table->smallInteger('iar');
            $table->smallInteger('ias');
            $table->smallInteger('iat');
            $table->smallInteger('ibn');
            $table->smallInteger('ibr');
            $table->smallInteger('ibs');
            $table->smallInteger('ibt');
            $table->smallInteger('icn');
            $table->smallInteger('icr');
            $table->smallInteger('ics');
            $table->smallInteger('ict');
            $table->smallInteger('idn');
            $table->smallInteger('idr');
            $table->smallInteger('ids');
            $table->smallInteger('idt');
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
