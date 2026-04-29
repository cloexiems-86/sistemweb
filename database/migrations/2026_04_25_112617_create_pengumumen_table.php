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
    Schema::create('pengumumans', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('isi');
        $table->enum('target', ['catin', 'pendamping', 'semua'])->default('semua');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};
