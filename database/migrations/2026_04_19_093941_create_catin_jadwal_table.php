<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('catin_jadwal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
        $table->foreignId('catin_id')->constrained('catins')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catin_jadwal');
    }
};
