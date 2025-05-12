<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPenggunaController extends Controller
{
    public function dashboard()
    {
        // Pastikan pengguna login
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $date = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $today = Carbon::now()->toDateString();

        // Mengambil detail pengguna dari tabel pengguna_parkir
        $penggunaDetail = DB::table('pengguna_parkir')
            ->where('id_pengguna', $user->id_pengguna) // Ambil data pengguna berdasarkan id_pengguna
            ->select(['id_pengguna', 'nama', 'kategori', 'email', 'foto']) // Tentukan kolom yang diambil
            ->first();

        // Mengambil kendaraan yang terkait dengan pengguna
        $kendaraan = DB::table('kendaraan')
            ->where('id_pengguna', $user->id_pengguna) // Ambil kendaraan berdasarkan pengguna
            ->select(['plat_nomor']) // Ambil kolom plat_nomor
            ->first();

        // Buat path QR code jika kendaraan ada
        $qrCodePath = $kendaraan ? 'images/qrcodes/' . $kendaraan->plat_nomor . '.png' : null;

        // Jumlah pengguna unik dari tabel riwayat_parkir berdasarkan hari ini
        $jumlahPengguna = DB::table('riwayat_parkir')
            ->whereDate('waktu_masuk', $today)
            ->distinct('id_pengguna') // Hitung pengguna unik
            ->count('id_pengguna');

        // Jumlah parkir yang statusnya 'masuk' dan 'keluar' berdasarkan hari ini
        $jumlahParkirMasuk = DB::table('riwayat_parkir')
            ->whereDate('waktu_masuk', $today)
            ->where('status_parkir', 'masuk')
            ->count();

        $jumlahParkirKeluar = DB::table('riwayat_parkir')
            ->whereDate('waktu_keluar', $today)
            ->where('status_parkir', 'keluar')
            ->count();

        // Kirim semua data ke tampilan
        return view('pengguna.dashboard', compact(
            'user',
            'date',
            'penggunaDetail',
            'kendaraan',
            'qrCodePath',
            'jumlahPengguna',
            'jumlahParkirMasuk',
            'jumlahParkirKeluar'
        ));
    }
}
