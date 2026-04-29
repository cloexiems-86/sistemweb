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
    Schema::table('jadwals', function (Blueprint $table) {
        $table->string('lokasi')->after('status');
        $table->unsignedBigInteger('pendamping_id')->nullable()->after('lokasi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            //
        });
    }
};
