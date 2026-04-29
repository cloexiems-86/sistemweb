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

            $table->timestamp('accessed_at')->useCurrent();

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
