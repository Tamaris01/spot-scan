<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RiwayatParkir;
use App\Models\Kendaraan;

class RiwayatParkirController extends Controller
{
    /**
     * Handle QR Code scanning for parking
     */
    public function scanQR(Request $request)
    {
        // Ambil plat nomor yang dipindai
        $platNomor = $request->input('plat_nomor');

        // Validasi input plat nomor
        if (!$platNomor) {
            return response()->json(['message' => 'Plat nomor tidak ditemukan'], 400);
        }

        // Cari kendaraan berdasarkan plat nomor di tabel Kendaraan
        $kendaraan = Kendaraan::where('plat_nomor', $platNomor)->first();

        // Jika kendaraan tidak ditemukan
        if (!$kendaraan) {
            return response()->json(['message' => 'Plat nomor tidak terdaftar di sistem'], 404);
        }

        // Ambil id_pengguna dari kendaraan yang ditemukan (relasi satu-ke-satu dengan pengguna_parkir)
        $idPengguna = $kendaraan->penggunaParkir->id_pengguna; // mengambil id_pengguna dari relasi kendaraan

        // Cek apakah kendaraan sudah terparkir dan masih status masuk
        $riwayatParkir = RiwayatParkir::where('plat_nomor', $platNomor)
            ->where('status_parkir', 'masuk')
            ->first();

        // Ambil waktu sekarang
        $waktuSekarang = Carbon::now();

        if ($riwayatParkir) {
            // Jika kendaraan ditemukan dan statusnya masih masuk, maka ubah menjadi keluar
            $riwayatParkir->waktu_keluar = $waktuSekarang;
            $riwayatParkir->status_parkir = 'keluar';
            $riwayatParkir->save();

            return response()->json([
                'message' => 'Kendaraan keluar',
                'plat_nomor' => $platNomor,
                'status' => 'keluar',
                'waktu_keluar' => $waktuSekarang
            ]);
        } else {
            // Jika kendaraan belum terparkir (status masuk), maka simpan sebagai kendaraan masuk
            RiwayatParkir::create([
                'id_pengguna' => $idPengguna, // menggunakan id_pengguna yang diambil dari relasi kendaraan
                'plat_nomor' => $platNomor,
                'waktu_masuk' => $waktuSekarang,
                'status_parkir' => 'masuk',
            ]);

            return response()->json([
                'message' => 'Kendaraan masuk',
                'plat_nomor' => $platNomor,
                'status' => 'masuk',
                'waktu_masuk' => $waktuSekarang
            ]);
        }
    }

    /**
     * Display user parking history for today
     */
    public function riwayatParkir()
    {
        $user = auth()->user();
        $date = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        // Mendapatkan tanggal hari ini
        $today = Carbon::today();

        // Mengambil riwayat parkir pengguna berdasarkan ID pengguna dan hari ini
        $riwayatParkir = RiwayatParkir::whereHas('kendaraan', function ($query) use ($user) {
            $query->where('id_pengguna', $user->id_pengguna);
        })
            ->where(function ($query) use ($today) {
                $query->whereDate('waktu_masuk', $today)
                    ->orWhereDate('waktu_keluar', $today);
            })
            ->select('id_riwayat_parkir', 'plat_nomor', 'waktu_masuk', 'waktu_keluar')
            ->orderByRaw("CASE WHEN waktu_keluar IS NULL THEN 0 ELSE 1 END, id_riwayat_parkir DESC")
            ->get();

        return view('pengguna.riwayat_parkir', compact('riwayatParkir', 'date'));
    }
}
