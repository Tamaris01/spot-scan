<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaParkirTable extends Migration
{
    public function up()
    {
        Schema::create('pengguna_parkir', function (Blueprint $table) {
            $table->string('id_pengguna')->primary(); // Menggunakan string sebagai primary key
            $table->enum('kategori', ['Mahasiswa', 'Dosen/Karyawan', 'Tamu']); // Menggunakan enum untuk kategori
            $table->string('nama');
            $table->string('foto');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif'); // Status default

        });
    }

    public function down()
    {
        Schema::dropIfExists('pengguna_parkir'); // Hapus tabel jika rollback
    }
}
