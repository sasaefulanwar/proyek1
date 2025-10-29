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
        Schema::create('obat', function (Blueprint $table) {
            $table->id('id_obat'); // Primary key
            $table->unsignedBigInteger('id_apotek')->nullable();
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->string('nama_obat', 100);
            $table->string('kategori', 50);
            $table->decimal('harga', 10, 2);
            $table->integer('stok');
            $table->enum('status', ['Tersedia', 'Menipis', 'Habis'])->default('Tersedia');
            $table->timestamps();

            // Relasi ke tabel apotek
            $table->foreign('id_apotek')->references('id_apotek')->on('apotek')->onDelete('cascade');

            // Relasi ke tabel admin
            $table->foreign('id_admin')->references('id')->on('admin')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
