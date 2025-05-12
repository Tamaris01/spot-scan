<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatParkirTable extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel riwayat_parkir.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_parkir', function (Blueprint $table) {
            $table->string('id_riwayat_parkir'); // ID Riwayat Parkir (Auto-incrementing primary key)
            $table->string('id_pengguna'); // Referencing the pengguna_parkir table
            $table->dateTime('waktu_masuk'); // Timestamp for entry time
            $table->dateTime('waktu_keluar')->nullable(); // Timestamp for exit time, nullable
            $table->enum('status_parkir', ['masuk', 'keluar']); // Status of parking
            $table->string('plat_nomor'); // Kolom untuk menyimpan plat nomor kendaraan

            // Foreign key constraints
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna_parkir')->onDelete('cascade');
            $table->foreign('plat_nomor')->references('plat_nomor')->on('kendaraan')->onDelete('cascade');
        });
    }

    /**
     * Membalikkan perubahan migration (rollback).
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_parkir'); // Drop the table if rollback
    }
}
