<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengelolaParkirTable extends Migration
{
    public function up()
    {
        Schema::create('pengelola_parkir', function (Blueprint $table) {
            $table->string('id_pengelola')->primary(); // ID Pengelola as a string primary key
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('foto'); // Foto Pengelola
        });
    }
    public function down()
    {
        Schema::dropIfExists('pengelola_parkir');
    }
}
