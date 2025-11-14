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
        Schema::table('apotek', function (Blueprint $table) {
            $table->string('link_lokasi', 255)->nullable()->after('foto_apotek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apotek', function (Blueprint $table) {
            //
        });
    }
};
