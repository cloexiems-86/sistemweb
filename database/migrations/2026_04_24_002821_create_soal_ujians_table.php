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
        Schema::create('soal_ujians', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->string('pil_a');
            $table->string('pil_b');
            $table->string('pil_c');
            $table->string('pil_d');
            $table->string('kunci_jawaban'); // simpan 'a', 'b', 'c', atau 'd'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ujians');
    }
};
