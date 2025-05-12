<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenggunaParkirRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan user diizinkan melakukan request ini
    }

    public function rules()
    {
        return [
            'id_pengguna' => 'required_if:kategori,!Tamu|string|max:255|unique:pengguna_parkir,id_pengguna',
            'kategori' => 'required|string|in:' . implode(',', app('App\Http\Controllers\PenggunaParkirController')->getEnumValues('pengguna_parkir', 'kategori')),
            'nama' => 'required|string|regex:/^[A-Z][a-zA-Z\s]*$/|max:50',
            'email' => 'required|email|unique:pengguna_parkir,email|max:255',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'nama.regex' => 'Nama harus dimulai dengan huruf besar dan hanya mengandung huruf.',
            'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.',
        ];
    }
}
