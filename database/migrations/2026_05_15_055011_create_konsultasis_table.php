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
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel catin dan pendamping yang sudah kamu punya
            $table->foreignId('catin_id')->constrained('catins')->onDelete('cascade');
            $table->foreignId('pendamping_id')->constrained('pendamping')->onDelete('cascade');
            $table->enum('pengirim', ['catin', 'pendamping']);
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};
