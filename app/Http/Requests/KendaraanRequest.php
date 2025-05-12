<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class KendaraanRequest extends FormRequest
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
     * Mendefinisikan aturan validasi untuk penambahan dan pembaruan data kendaraan.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            // Aturan untuk menambahkan kendaraan
            return [
                'id_pengguna' => 'required|exists:pengguna_parkir,id_pengguna', // memvalidasi id_pengguna yang dipilih
                'jenis' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'jenis')),
                'warna' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'warna')),
                'foto_kendaraan' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Foto wajib diupload untuk penambahan kendaraan
            ];
        }

        if ($this->isMethod('put')) {
            // Aturan untuk memperbarui kendaraan
            return [
                'id_pengguna' => 'nullable|exists:pengguna_parkir,id_pengguna', // id_pengguna bersifat opsional untuk pembaruan
                'jenis' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'jenis')),
                'warna' => 'required|string|in:' . implode(',', $this->getEnumValues('kendaraan', 'warna')),
                'foto_kendaraan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto bersifat opsional untuk pembaruan
            ];
        }

        return [];
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
            'id_pengguna.required' => 'ID Pengguna harus dipilih.',
            'id_pengguna.exists' => 'ID Pengguna yang dipilih tidak valid.',
            'jenis.required' => 'Jenis kendaraan harus dipilih.',
            'jenis.in' => 'Jenis kendaraan yang dipilih tidak valid.',
            'warna.required' => 'Warna kendaraan harus dipilih.',
            'warna.in' => 'Warna kendaraan yang dipilih tidak valid.',
            'foto_kendaraan.required' => 'Foto kendaraan harus diupload.',
            'foto_kendaraan.image' => 'Foto kendaraan harus berupa gambar.',
            'foto_kendaraan.mimes' => 'Foto kendaraan harus berformat jpeg, png, atau jpg.',
            'foto_kendaraan.max' => 'Ukuran foto kendaraan tidak boleh lebih dari 2 MB.',
        ];
    }
}
