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
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel users (untuk Catin)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Menghubungkan ke tabel jadwal_bimbingans (sesuaikan nama tabel jadwalmu)
        $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
        $table->enum('status', ['hadir', 'alfa', 'izin'])->default('hadir');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
