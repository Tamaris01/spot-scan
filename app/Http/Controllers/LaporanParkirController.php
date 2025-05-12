<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatParkir;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanParkirExport;

class LaporanParkirController extends Controller
{
    /**
     * Menampilkan halaman Laporan Parkir tanpa filter pencarian dan hanya status_parkir 'keluar'.
     */
  public function index(Request $request)
    {
        // Mendapatkan jumlah baris per halaman dari input pengguna, default 10
        $perPage = $request->input('rows', 10);
    
        // Mendapatkan tanggal hari ini dalam format lokal
        $date = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
    
        // Mendapatkan tanggal hari ini menggunakan Carbon
        $today = Carbon::today();
    
        // Menampilkan data riwayat parkir dengan status_parkir 'keluar' dan tanggal hari ini
        // dan mengurutkan berdasarkan waktu_keluar terbaru
        $riwayatParkir = RiwayatParkir::with(['pengguna', 'kendaraan'])
            ->where('status_parkir', 'keluar') // Filter berdasarkan status_parkir
            ->whereDate('waktu_keluar', $today) // Filter berdasarkan tanggal waktu_keluar
            ->orderBy('waktu_keluar', 'desc') // Urutkan berdasarkan waktu_keluar terbaru
            ->paginate($perPage);
    
        // Mengirim data ke view
        return view('pengelola.laporan_parkir', compact('riwayatParkir', 'date', 'perPage'));
    }



    /**
     * Menampilkan hasil pencarian berdasarkan query dan hanya status_parkir 'keluar'.
     */
    public function search(Request $request)
    {
        $query = $request->get('query', ''); // Dapatkan input pencarian
        $perPage = $request->get('rows', 10); // Default 10 rows per page
        $date = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        // Menampilkan data yang sesuai dengan pencarian dan status_parkir 'keluar'
        $riwayatParkir = RiwayatParkir::with(['pengguna', 'kendaraan'])
            ->where('status_parkir', 'keluar') // Filter berdasarkan status_parkir 'keluar'
            ->where(function ($queryBuilder) use ($query) {
                // Pencarian berdasarkan plat nomor atau ID pengguna
                $queryBuilder->where('plat_nomor', 'like', "%$query%")
                    ->orWhere('id_pengguna', 'like', "%$query%");
            })
            ->paginate($perPage);

        // Mengirim data ke view
        return view('pengelola.laporan_parkir', compact('riwayatParkir', 'query', 'date', 'perPage'));
    }

    /**
     * Unduh laporan parkir dalam format PDF.
     */
    public function unduhLaporanParkir(Request $request)
    {
        $format = $request->get('format');
        $filterType = $request->get('filter_type', 'semua');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Jika end_date dipilih tapi start_date belum dipilih, beri notifikasi
        if ($filterType == 'tanggal' && $endDate && !$startDate) {
            return redirect()->back()->with('message', 'Silakan pilih tanggal mulai terlebih dahulu.');
        }

        // Ambil data parkir yang sudah keluar
        $query = RiwayatParkir::whereNotNull('waktu_keluar'); // Filter hanya yang sudah keluar

        // Jika filter tanggal dipilih
        if ($filterType == 'tanggal') {
            // Cek apakah start_date ada
            if ($startDate) {
                // Jika end_date tidak diisi, maka filter berdasarkan start_date saja
                if ($endDate) {
                    // Jika end_date ada, gunakan rentang antara start_date dan end_date
                    $query->whereBetween('waktu_keluar', [$startDate, $endDate]);
                } else {
                    // Jika hanya start_date yang ada, filter berdasarkan start_date saja
                    $query->whereDate('waktu_keluar', '=', $startDate);
                }
            }
        }

        $riwayatParkir = $query->get(); // Ambil data berdasarkan filter

        // Cek apakah data parkir kosong
        if ($riwayatParkir->isEmpty()) {
            // Jika tidak ada data, kembalikan pesan ke pengguna
            return redirect()->back()->with('message', 'Tidak ada laporan parkir yang ditemukan untuk rentang waktu yang dipilih.');
        }

        // Unduh file berdasarkan format yang dipilih
        switch ($format) {
            case 'pdf':
                $pdf = PDF::loadView('pengelola.laporan_parkir_pdf', ['riwayatParkir' => $riwayatParkir]);
                return $pdf->download('laporan_parkir.pdf');
            case 'csv':
                return response()->stream(function () use ($riwayatParkir) {
                    $handle = fopen('php://output', 'w');
                    // Menulis header CSV
                    fputcsv($handle, ['ID Parkir', 'Nama Pengguna', 'Kategori Pengguna', 'Plat Nomor', 'Waktu Masuk', 'Waktu Keluar', 'Lama Parkir']);
                    // Menulis data parkir
                    foreach ($riwayatParkir as $riwayat) {
                        fputcsv($handle, [
                            $riwayat->id_riwayat_parkir,
                            $riwayat->pengguna->nama,
                            $riwayat->pengguna->kategori,
                            $riwayat->kendaraan->plat_nomor,
                            $riwayat->kendaraan->jenis,
                            $riwayat->waktu_masuk,
                            $riwayat->waktu_keluar,
                            $riwayat->lama_parkir,
                        ]);
                    }
                    fclose($handle);
                }, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="laporan_parkir.csv"',
                ]);
            case 'excel':
                return Excel::download(new LaporanParkirExport($riwayatParkir), 'laporan_parkir.xlsx');
            default:
                return redirect()->route('laporan_parkir.index');
        }
    }
}
