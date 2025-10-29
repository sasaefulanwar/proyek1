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
        Schema::create('apotek', function (Blueprint $table) {
            $table->id('id_apotek'); // ID unik apotek
            $table->string('nama_apotek', 150);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->unique();
            $table->string('jam_operasional', 255)->nullable(); // contoh: "Senin - Jumat: 08.00 - 21.00 | Sabtu - Minggu: 09.00 - 17.00"
            $table->text('deskripsi')->nullable();
            $table->string('foto_apotek')->nullable(); // ganti dari logo menjadi foto apotek
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apotek');
    }
};
