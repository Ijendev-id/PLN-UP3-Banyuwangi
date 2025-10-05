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
        Schema::create('history_pemeliharaan', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('id_pemeliharaan')
                ->nullable()
                ->constrained('pemeliharaan')
                ->nullOnDelete(); // field menjadi null saat data master terhapus
            $table->json('data_lama');
            $table->enum('aksi', ['create', 'update', 'delete']);
            $table->string('diubah_oleh', 20);
            $table->string('keterangan', 50)->nullable();
            $table->index(['id_pemeliharaan', 'created_at']);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pemeliharaan');
    }
};
