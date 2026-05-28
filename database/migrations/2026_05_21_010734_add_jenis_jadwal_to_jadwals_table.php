<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Menambahkan kolom jenis_jadwal setelah kolom id
            $table->enum('jenis_jadwal', ['Bimbingan', 'Rapak'])->default('Bimbingan')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('jenis_jadwal');
        });
    }
};