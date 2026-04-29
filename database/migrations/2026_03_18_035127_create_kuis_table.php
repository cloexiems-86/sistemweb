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
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel materis
            // Pastikan tabel 'materis' sudah ada sebelum menjalankan ini
            $table->foreignId('materi_id')
                  ->constrained('materis') 
                  ->onDelete('cascade'); 
            
            $table->string('judul');
            $table->integer('durasi')->default(15); // Durasi dalam menit
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};