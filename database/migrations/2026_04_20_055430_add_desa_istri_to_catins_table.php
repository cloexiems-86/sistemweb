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
        Schema::table('catins', function (Blueprint $table) {
            $table->string('desa_istri')->nullable()->after('id'); // sesuaikan posisinya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catins', function (Blueprint $table) {
            //
        });
    }
};
