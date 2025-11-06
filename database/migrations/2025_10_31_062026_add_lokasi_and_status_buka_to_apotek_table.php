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
        Schema::table('apotek', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('jam_operasional'); // misal setelah jam_operasional
            $table->enum('status_buka', ['Buka', 'Tutup'])->default('Tutup')->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('apotek', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'status_buka']);
        });
    }
};
