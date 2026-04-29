<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            // Menambahkan kolom materi_id sebagai foreign key
            // constrained('materis') artinya dia nyambung ke tabel materis
            $table->foreignId('materi_id')->after('id')->constrained('materis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            // Hapus relasi dan kolom jika migration di-rollback
            $table->dropForeign(['materi_id']);
            $table->dropColumn('materi_id');
        });
    }
};