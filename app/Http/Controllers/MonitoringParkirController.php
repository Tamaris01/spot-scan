<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatParkir;
use App\Models\Kendaraan;
use App\Models\PenggunaParkir;
use Carbon\Carbon;

class MonitoringParkirController extends Controller
{
    /**
     * Menampilkan halaman monitoring parkir tanpa filter pencarian.
     */
  public function index(Request $request)
    {
        // Mendapatkan jumlah baris per halaman dari input pengguna, default 10
        $perPage = $request->input('rows', 10);
    
        // Mendapatkan tanggal hari ini menggunakan Carbon
        $today = Carbon::today();
    
        // Menampilkan data riwayat parkir yang sesuai dengan tanggal hari ini
        // dan mengurutkan berdasarkan waktu masuk terbaru
        $riwayatParkir = RiwayatParkir::with(['pengguna', 'kendaraan'])
            ->whereDate('waktu_masuk', $today)
            ->orderBy('waktu_masuk', 'desc') // Urutkan dari yang terbaru
            ->paginate($perPage);
    
        // Mengirim data ke view
        return view('pengelola.monitoring', compact('riwayatParkir', 'perPage'));
    }



    /**
     * Menampilkan hasil pencarian berdasarkan query.
     */
    public function search(Request $request)
    {
        $query = $request->get('query', ''); // Dapatkan input pencarian
        $perPage = $request->get('rows', 10); // Default 10 rows per page

        // Menampilkan data yang sesuai dengan pencarian
        $riwayatParkir = RiwayatParkir::with(['pengguna', 'kendaraan'])
            ->where('plat_nomor', 'like', "%$query%")
            ->orWhere('id_pengguna', 'like', "%$query%")
            ->paginate($perPage);

        // Mengirim data ke view
        return view('pengelola.monitoring', compact('riwayatParkir', 'query', 'perPage'));
    }

    /**
     * Fungsi untuk menangani pemindaian QR Code kendaraan
     */
    public function scanQRCode(Request $request)
    {
        // Ambil QR Code dari request
        $qrCode = $request->input('qr_code');

        // Cari kendaraan berdasarkan QR Code
        $kendaraan = Kendaraan::where('qr_code', $qrCode)->first();

        if (!$kendaraan) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau kendaraan tidak terdaftar'
            ], 404);
        }

        // Cari pengguna terkait dengan kendaraan
        $pengguna = PenggunaParkir::where('id_pengguna', $kendaraan->id_pengguna)->first();

        if (!$pengguna) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Cek riwayat parkir apakah kendaraan sudah masuk
        $riwayatParkir = RiwayatParkir::where('plat_nomor', $kendaraan->plat_nomor)
            ->whereNull('waktu_keluar')
            ->first();

        if ($riwayatParkir) {
            // Kendaraan keluar, perbarui riwayat parkir
            $riwayatParkir->waktu_keluar = Carbon::now();
            $riwayatParkir->status_parkir = 'keluar';
            $riwayatParkir->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Kendaraan keluar, riwayat diperbarui'
            ], 200);
        } else {
            // Generate ID parkir baru, misal PARK001
            $lastRiwayat = RiwayatParkir::latest()->first();
            $newId = 'PARK' . str_pad(substr($lastRiwayat->id_riwayat_parkir, 4) + 1, 3, '0', STR_PAD_LEFT);

            // Kendaraan masuk, buat riwayat parkir baru dengan ID baru
            RiwayatParkir::create([
                'id_riwayat_parkir' => $newId,
                'id_pengguna' => $pengguna->id_pengguna,
                'waktu_masuk' => Carbon::now(),
                'status_parkir' => 'masuk',
                'plat_nomor' => $kendaraan->plat_nomor,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kendaraan masuk, riwayat parkir tercatat dengan ID ' . $newId
            ], 200);
        }
    }
}
