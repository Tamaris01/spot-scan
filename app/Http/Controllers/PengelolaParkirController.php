<?php

namespace App\Http\Controllers;

use App\Models\PenggunaParkir;
use Illuminate\Http\Request;

class PengelolaParkirController extends Controller
{
    public function dashboard()
    {
        // Ambil data pengelola yang diperlukan
        $manager = auth()->user();

        return view('pengelola.dashboard', compact('manager'));
    }

    public function kelolaPengguna()
    {
        // Ambil data pengguna parkir dari database
        $pengguna = PenggunaParkir::all(); // Sesuaikan query dengan kebutuhan

        // Tampilkan halaman dengan data pengguna
        return view('pengelola.kelolaPengguna', compact('pengguna'));
    }
}
