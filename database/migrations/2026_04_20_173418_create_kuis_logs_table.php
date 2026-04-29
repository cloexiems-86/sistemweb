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
        Schema::create('kuis_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis_logs');
    }
};
