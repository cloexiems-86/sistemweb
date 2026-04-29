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
        Schema::table('kuis', function (Blueprint $table) {
            // Menambah kolom catin_id setelah kolom id
            // constrained('catins') artinya kolom ini terhubung ke tabel catins
            // cascade artinya jika data catin dihapus, data kuisnya juga ikut terhapus
            $table->foreignId('catin_id')->after('id')->nullable()->constrained('catins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            // Menghapus constraint foreign key dan kolomnya jika migration di-rollback
            $table->dropForeign(['catin_id']);
            $table->dropColumn('catin_id');
        });
    }
};