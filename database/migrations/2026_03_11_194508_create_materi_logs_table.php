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
    Schema::create('materi_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('catin_id');
        $table->unsignedBigInteger('materi_id');
        $table->string('peran'); // Untuk menyimpan 'suami' atau 'istri'
        $table->timestamps(); // Ini otomatis membuat created_at dan updated_at

        // Opsional: Tambahkan foreign key agar data lebih aman
        $table->foreign('catin_id')->references('id')->on('catins')->onDelete('cascade');
        $table->foreign('materi_id')->references('id')->on('materis')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_logs');
    }
};
