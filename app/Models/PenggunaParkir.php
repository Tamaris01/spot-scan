<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Ganti Model dari Model ke Authenticatable
use Illuminate\Notifications\Notifiable; // Jika perlu menggunakan notifikasi
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class PenggunaParkir extends Authenticatable // Mengimplementasikan Authenticatable untuk autentikasi
{
    use HasFactory, Notifiable; // Menambahkan trait Notifiable jika menggunakan notifikasi

    protected $table = 'pengguna_parkir'; // Tabel yang digunakan

    public $timestamps = false;
    protected $primaryKey = 'id_pengguna'; // Menggunakan id_pengguna sebagai primary key
    public $incrementing = false; // Jika id_pengguna bukan auto-increment
    protected $keyType = 'string'; // Jika id_pengguna adalah string

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'id_pengguna',
        'nama',
        'email',
        'password',
        'foto',
        'kategori',
        'status',
    ];

    // Mutator untuk mengenkripsi password sebelum disimpan
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value); // Hashing password
    }

    // Mengembalikan nama kolom yang digunakan untuk autentikasi
    public function getAuthIdentifierName()
    {
        return 'id_pengguna'; // Gunakan id_pengguna sebagai identifier untuk autentikasi
    }

    // Mengembalikan password yang di-hash untuk pemeriksaan autentikasi
    public function getAuthPassword()
    {
        return $this->attributes['password']; // Password yang telah di-hash
    }

    // Relasi one-to-one dengan tabel kendaraan
    public function kendaraan()
    {
        return $this->hasOne(Kendaraan::class, 'id_pengguna', 'id_pengguna'); // Relasi ke model Kendaraan
    }
}
