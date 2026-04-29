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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel catin
            $table->foreignId('catin_id')->constrained('catins')->onDelete('cascade');
            
            $table->integer('skor');
            $table->integer('jawaban_benar');
            $table->integer('jawaban_salah');
            
            // Enum untuk status, biar keren kayak dashboard kamu tadi
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus'])->default('tidak_lulus');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
