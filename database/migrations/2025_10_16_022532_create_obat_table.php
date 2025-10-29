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
            $table->id(); // id obat (kolom 'id')
            // pastikan tipe sama dengan primary key apotek (biasanya unsignedBigInteger)
            $table->unsignedBigInteger('id_apotek');

            $table->string('nama_obat', 100);
            $table->string('kategori', 100);
            $table->integer('harga'); // simpan angka
            $table->integer('stok');
            $table->enum('status', ['Tersedia', 'Menipis', 'Habis']);
            $table->timestamps();

            // referensi ke kolom id_apotek (bukan id)
            $table->foreign('id_apotek')
                ->references('id_apotek')
                ->on('apotek')
                ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
