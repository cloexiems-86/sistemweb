<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catins', function (Blueprint $table) {

            $table->id();

            $table->string('username')->unique();
            $table->string('password');

            // DATA SUAMI
            $table->string('nik_suami',16);
            $table->string('nama_suami');
            $table->string('phone_suami');
            $table->string('email_suami');
            $table->text('alamat_suami');

            // DATA ISTRI
            $table->string('nik_istri',16);
            $table->string('nama_istri');
            $table->string('phone_istri');
            $table->string('email_istri');
            $table->text('alamat_istri');

            // JADWAL
            $table->date('wedding_date');

            // DOKUMEN
            $table->string('ktp_suami')->nullable();
            $table->string('ktp_istri')->nullable();
            $table->string('kk_suami')->nullable();
            $table->string('kk_istri')->nullable();

            // STATUS
            $table->enum('status',['aktif','nonaktif'])->default('aktif');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catins');
    }
};