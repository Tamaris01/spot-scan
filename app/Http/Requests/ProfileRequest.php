<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => 'required|string|regex:/^[A-Z][a-zA-Z\s]*$/|max:50',
            'email' => 'required|email|unique:pengguna_parkir,email,' . $this->user()->id_pengguna . ',id_pengguna|unique:pengelola_parkir,email,' . $this->user()->id_pengelola . ',id_pengelola|max:255',
            'password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.regex' => 'Nama harus diawali dengan huruf kapital dan hanya boleh berisi huruf dan spasi.',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',

            'foto.required' => 'Foto profil wajib diunggah.',
            'foto.image' => 'Foto profil harus berupa gambar.',
            'foto.mimes' => 'Foto profil harus berformat jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto profil tidak boleh lebih dari 2 MB.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.regex' => 'Password harus terdiri dari huruf besar, huruf kecil, angka, dan karakter khusus.',
        ];
    }
}
