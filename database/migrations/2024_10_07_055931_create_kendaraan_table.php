<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKendaraanTable extends Migration
{
    public function up()
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            // Mengatur plat nomor sebagai primary key
            $table->string('plat_nomor')->primary(); // Primary key kendaraan
            $table->string('id_pengguna'); // Foreign key, tipe data string
            $table->enum('jenis', ['Mobil', 'Motor']); // Tipe data jenis kendaraan ENUM
            $table->string('qr_code'); // QR Code kendaraan
            $table->string('foto'); // Foto kendaraan

            // Enum untuk warna kendaraan dengan daftar lengkap
            $table->enum('warna', [
                'Merah',
                'Biru',
                'Hijau',
                'Kuning',
                'Hitam',
                'Putih',
                'Abu-abu',
                'Silver',
                'Oranye',
                'Cokelat',
                'Ungu',
                'Emas',
                'Pink'

            ]);

            // Definisikan foreign key
            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna_parkir')
                ->onDelete('cascade'); // Hapus kendaraan jika pengguna dihapus
        });
    }

    public function down()
    {
        Schema::dropIfExists('kendaraan'); // Hapus tabel jika rollback
    }
}
