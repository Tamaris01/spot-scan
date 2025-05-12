<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardPengelolaController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $date = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $today = Carbon::now()->toDateString();

        // Jumlah pengguna unik dari tabel riwayat_parkir berdasarkan hari ini
        $jumlahPengguna = DB::table('riwayat_parkir')
            ->whereDate('waktu_masuk', $today)
            ->distinct('id_pengguna')
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

        // Data untuk Bar Chart (Kategori Pengguna)
        $kategoriData = DB::table('riwayat_parkir')
            ->join('pengguna_parkir', 'riwayat_parkir.id_pengguna', '=', 'pengguna_parkir.id_pengguna')
            ->selectRaw('pengguna_parkir.kategori, COUNT(DISTINCT riwayat_parkir.id_pengguna) as jumlah')
            ->whereDate('riwayat_parkir.waktu_masuk', $today)
            ->groupBy('pengguna_parkir.kategori')
            ->get();

        // Data untuk Line Chart (Waktu Puncak Penggunaan berdasarkan Kategori Pengguna, hari ini)
        $waktuPuncakData = DB::table('riwayat_parkir')
            ->join('pengguna_parkir', 'riwayat_parkir.id_pengguna', '=', 'pengguna_parkir.id_pengguna')
            ->selectRaw("HOUR(waktu_masuk) as jam, 
            pengguna_parkir.kategori, 
            COUNT(*) as jumlah")
            ->whereDate('waktu_masuk', $today)
            ->groupBy('jam', 'kategori')
            ->orderBy('jam', 'asc')
            ->get();

        $kategoriDataChart = $kategoriData->map(function ($kategori) {
            return [
                'kategori' => $kategori->kategori,
                'jumlah' => $kategori->jumlah,
            ];
        })->toArray();

        // Memisahkan data berdasarkan kategori secara dinamis untuk line chart
        $waktuPuncakChartData = [];
        foreach ($waktuPuncakData as $data) {
            if (!isset($waktuPuncakChartData[$data->kategori])) {
                $waktuPuncakChartData[$data->kategori] = array_fill(0, 24, 0); // Jam 0-23
            }
            $waktuPuncakChartData[$data->kategori][$data->jam] = $data->jumlah;
        }

        // Data untuk Pie Chart (Jenis Kendaraan, berdasarkan hari ini)
        $jenisKendaraanData = DB::table('kendaraan')
            ->join('riwayat_parkir', 'kendaraan.plat_nomor', '=', 'riwayat_parkir.plat_nomor')
            ->selectRaw('jenis, COUNT(*) as jumlah')
            ->whereDate('riwayat_parkir.waktu_masuk', $today)
            ->groupBy('jenis')
            ->get();

        // Data untuk Doughnut Chart (Statistik Kendaraan Masuk berdasarkan waktu, hari ini)
        $kendaraanMasukWaktuData = DB::table('riwayat_parkir')
            ->selectRaw("CASE
                WHEN HOUR(waktu_masuk) BETWEEN 5 AND 9 THEN 'Pagi'
                WHEN HOUR(waktu_masuk) BETWEEN 10 AND 14 THEN 'Siang'
                WHEN HOUR(waktu_masuk) BETWEEN 15 AND 18 THEN 'Sore'
                WHEN HOUR(waktu_masuk) BETWEEN 19 AND 23 THEN 'Malam'
            END as waktu,
            COUNT(*) as jumlah")
            ->whereDate('waktu_masuk', $today)
            ->where('status_parkir', 'masuk')
            ->groupBy('waktu')
            ->get();

        // Return data as JSON for AJAX
        if ($request->ajax()) {
            return response()->json([
                'jumlahPengguna' => $jumlahPengguna,
                'jumlahParkirMasuk' => $jumlahParkirMasuk,
                'jumlahParkirKeluar' => $jumlahParkirKeluar,
                'kategoriDataChart' => $kategoriDataChart,
                'waktuPuncakChartData' => $waktuPuncakChartData,
                'jenisKendaraanData' => $jenisKendaraanData,
                'kendaraanMasukWaktuData' => $kendaraanMasukWaktuData
            ]);
        }

        return view('pengelola.dashboard', compact(
            'user',
            'date',
            'jumlahPengguna',
            'jumlahParkirMasuk',
            'jumlahParkirKeluar',
            'kategoriDataChart',
            'waktuPuncakChartData',
            'jenisKendaraanData',
            'kendaraanMasukWaktuData'
        ));
    }
}
