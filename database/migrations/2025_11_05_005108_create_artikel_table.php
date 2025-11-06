<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id('id_artikel');                // Primary Key
            $table->string('judul');                 // Judul artikel
            $table->string('slug')->unique();        // URL slug unik
            $table->text('konten');                  // Isi artikel
            $table->string('gambar')->nullable();    // Nama file gambar (opsional)
            $table->date('tanggal_publikasi');       // Tanggal artikel diterbitkan
            $table->timestamps();                    // created_at & updated_at
        });
    }

    /**
     * Hapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
