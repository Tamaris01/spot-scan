<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RegisterRequest extends FormRequest
{
    /**
     * Mengizinkan semua pengguna untuk mengakses request ini.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk form pendaftaran.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'kategori' => 'required|string|in:' . implode(',', $this->getEnumValues('pengguna_parkir', 'kategori')),
            'nama' => 'required|string|regex:/^[A-Z][a-zA-Z\s,\.]*$/|max:50',
            'email' => 'required|email|unique:pengguna_parkir,email|max:255',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor',
            'jenis' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'jenis')),
            'warna' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'warna')),
            'foto_kendaraan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_pengguna' => 'required_if:kategori,!Tamu|string|max:255|unique:pengguna_parkir,id_pengguna'
        ];
    }

    /**
     * Mendapatkan nilai enum dari kolom tertentu dalam tabel.
     *
     * @param string $table
     * @param string $column
     * @return array
     */
    protected function getEnumValues($table, $column): array
    {
        $result = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column]);
        if (count($result) > 0) {
            $type = $result[0]->Type;
            preg_match('/^enum\((.*)\)$/', $type, $matches);
            $enum = [];

            foreach (explode(',', $matches[1]) as $value) {
                $enum[] = trim($value, "'");
            }

            return $enum;
        }

        return []; // Kembalikan array kosong jika tidak ada hasil
    }

    /**
     * Mendefinisikan pesan kesalahan untuk tiap aturan validasi.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori harus dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',

            'nama.required' => 'Nama wajib diisi.',
            'nama.regex' => 'Nama harus diawali dengan huruf kapital dan hanya boleh berisi huruf, koma, titik dan spasi.',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.regex' => 'Password harus terdiri dari huruf besar, huruf kecil, angka, dan karakter khusus.',

            'foto.required' => 'Foto profil wajib diunggah.',
            'foto.image' => 'Foto profil harus berupa gambar.',
            'foto.mimes' => 'Foto profil harus berformat jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto profil tidak boleh lebih dari 2 MB.',

            'plat_nomor.required' => 'Plat nomor wajib diisi.',
            'plat_nomor.unique' => 'Plat nomor ini sudah terdaftar.',
            'plat_nomor.max' => 'Plat nomor tidak boleh lebih dari 20 karakter.',

            'jenis.required' => 'Jenis kendaraan harus dipilih.',
            'jenis.in' => 'Jenis kendaraan yang dipilih tidak valid.',

            'warna.required' => 'Warna kendaraan harus dipilih.',
            'warna.in' => 'Warna kendaraan yang dipilih tidak valid.',

            'foto_kendaraan.required' => 'Foto kendaraan wajib diunggah.',
            'foto_kendaraan.image' => 'Foto kendaraan harus berupa gambar.',
            'foto_kendaraan.mimes' => 'Foto kendaraan harus berformat jpeg, png, atau jpg.',
            'foto_kendaraan.max' => 'Ukuran foto kendaraan tidak boleh lebih dari 2 MB.',

            'id_pengguna.required_if' => 'ID Pengguna wajib diisi jika kategori bukan Tamu.',
            'id_pengguna.unique' => 'ID Pengguna ini sudah terdaftar.',
            'id_pengguna.max' => 'ID Pengguna tidak boleh lebih dari 255 karakter.'
        ];
    }
}
