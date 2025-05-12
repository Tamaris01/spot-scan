<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class PengelolaParkir extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'pengelola_parkir'; // Nama tabel
    protected $primaryKey = 'id_pengelola'; // Primary key
    public $incrementing = false; // Menandakan primary key bukan auto-increment
    protected $keyType = 'string'; // Jenis primary key

    protected $fillable = [
        'id_pengelola',
        'password',
        'nama',
        'foto',
        'email',

    ];

    // Mutator untuk mengenkripsi password sebelum disimpan
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Mengembalikan nama kolom untuk autentikasi
    public function getAuthIdentifierName()
    {
        return 'id_pengelola'; // Nama kolom yang digunakan untuk autentikasi
    }

    // Mengembalikan password yang di-hash untuk pemeriksaan
    public function getAuthPassword()
    {
        return $this->attributes['password']; // Password yang di-hash
    }

    // Fungsi untuk memeriksa apakah pengguna adalah Pengelola
    public function isPengelola()
    {
        // Hapus atau sesuaikan pemeriksaan ini jika Anda tidak menggunakan role
        return true; // Selalu mengembalikan true jika Anda hanya memiliki satu tipe pengguna
    }
}
