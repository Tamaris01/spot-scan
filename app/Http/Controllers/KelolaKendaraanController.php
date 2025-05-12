<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\PenggunaParkir; // Menggunakan model PenggunaParkir
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\KendaraanRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KelolaKendaraanController extends Controller
{
    protected $jenisArray;
    protected $warnaArray;

    public function __construct()
    {
        // Inisialisasi nilai enum jenis kendaraan dan warna kendaraan
        $this->jenisArray = $this->getEnumValues('kendaraan', 'jenis');
        $this->warnaArray = $this->getEnumValues('kendaraan', 'warna');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);

        // Ambil enum jenis kendaraan dan warna kendaraan
        $this->jenisArray = $this->getEnumValues('kendaraan', 'jenis');
        $this->warnaArray = $this->getEnumValues('kendaraan', 'warna');

        // Ambil semua kendaraan dengan pagination dan pengguna yang terkait
        $kendaraan = Kendaraan::with('penggunaParkir')->paginate($perPage);

        // Ambil plat nomor kendaraan jika ada di request
        $platNomor = $request->input('plat_nomor');

        // Tentukan kendaraan terkait berdasarkan plat nomor
        $kendaraanTerkait = null;
        if ($platNomor) {
            $kendaraanTerkait = Kendaraan::where('plat_nomor', $platNomor)->first();
        }

        // Tentukan pengguna terkait dengan kendaraan tersebut
        $penggunaTerkait = null;
        if ($kendaraanTerkait) {
            $penggunaTerkait = $kendaraanTerkait->penggunaParkir;
        }

        // Tentukan pengguna yang belum memiliki kendaraan
        $penggunaTanpaKendaraan = PenggunaParkir::doesntHave('kendaraan')->get();

        // Gabungkan pengguna terkait dengan pengguna tanpa kendaraan
        $penggunaDropdown = collect();

        if ($penggunaTerkait) {
            $penggunaDropdown->push($penggunaTerkait); // Masukkan pengguna yang terkait
        }

        $penggunaDropdown = $penggunaDropdown->merge($penggunaTanpaKendaraan);

        // Tentukan nilai id_penggunaTerkait (id pengguna yang sedang diedit)
        $idPenggunaTerkait = $penggunaTerkait ? $penggunaTerkait->id_pengguna : null;

        // Kirim ke view dengan data yang dibutuhkan
        return view('pengelola.kelola_kendaraan', [
            'kendaraan' => $kendaraan,
            'jenisArray' => $this->jenisArray,
            'warnaArray' => $this->warnaArray,
            'perPage' => $perPage,
            'penggunaDropdown' => $penggunaDropdown,
            'idPenggunaTerkait' => $idPenggunaTerkait,
        ]);
    }


    public function search(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $query = $request->get('query');

        // Mencari kendaraan berdasarkan id_pengguna, plat_nomor, atau jenis
        $kendaraan = Kendaraan::where('id_pengguna', 'LIKE', "%$query%")
            ->orWhere('plat_nomor', 'LIKE', "%$query%")
            ->orWhere('jenis', 'LIKE', "%$query%")
            ->paginate($perPage);

        // Kembalikan hasil pencarian ke tampilan
        return view('pengelola.kelola_kendaraan', [
            'kendaraan' => $kendaraan,
            'query' => $query,
            'perPage' => $perPage,
            'jenisArray' => $this->jenisArray,
            'warnaArray' => $this->warnaArray,
        ]);
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        // Get a list of all pengguna_parkir (assuming you have a PenggunaParkir model)
        $penggunaParkir = PenggunaParkir::all(); // You can add more filtering if necessary

        return view('pengelola.kelola_kendaraan.create', compact('penggunaParkir'));
    }

    /**
     * Store a newly created vehicle in the database.
     */
    public function store(KendaraanRequest $request)
    {
        try {
            // Validasi ID Pengguna dan cek apakah ID Pengguna ada di database
            if (is_null($request->id_pengguna)) {
                return redirect()->back()->with('error', 'ID Pengguna tidak boleh kosong.');
            }

            // Cek apakah pengguna sudah memiliki kendaraan
            $existingVehicle = Kendaraan::where('id_pengguna', $request->id_pengguna)->first();
            if ($existingVehicle) {
                return redirect()->back()->with('error', 'Pengguna ini sudah memiliki kendaraan, tidak dapat menambahkan lebih dari satu.');
            }

            // Validasi dan simpan detail kendaraan
            $kendaraan = new Kendaraan();
            $kendaraan->plat_nomor = $request->plat_nomor;
            $kendaraan->jenis = $request->jenis;
            $kendaraan->warna = ucwords(strtolower($request->warna));
            $kendaraan->id_pengguna = $request->id_pengguna;

            // Tangani unggahan foto kendaraan jika ada
            if ($request->hasFile('foto_kendaraan')) {
                // Pastikan foto yang diupload valid
                $request->validate([
                    'foto_kendaraan' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Contoh validasi gambar
                ]);

                // Simpan foto kendaraan di folder 'kendaraan' pada storage public
                $kendaraan->foto = $request->file('foto_kendaraan')->store('kendaraan', 'public');
            }

            // Simpan data kendaraan ke database
            $kendaraan->save();

            // Redirect ke halaman kendaraan dengan pesan sukses
            return redirect()->route('pengelola.kelola_kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan');
        } catch (\Exception $e) {
            // Log error jika ada kegagalan
            Log::error('Failed to store vehicle: ' . $e->getMessage());

            // Berikan pesan kesalahan yang lebih informatif
            return redirect()->back()->with('error', 'Gagal menambahkan kendaraan. Silakan coba lagi atau hubungi admin jika kesalahan berlanjut.');
        }
    }


    public function edit($platNomor)
    {
        try {
            // Ambil data kendaraan beserta pengguna terkait
            $kendaraan = Kendaraan::with('penggunaParkir')->where('plat_nomor', $platNomor)->firstOrFail();

            // Ambil semua data pengguna untuk dropdown
            $penggunaParkir = PenggunaParkir::select('id_pengguna', 'nama')->get();

            // Return ke view dengan data
            return view('pengelola.kelola_kendaraan.edit', compact('kendaraan', 'penggunaParkir'));
        } catch (\Exception $e) {
            // Redirect jika data tidak ditemukan
            return redirect()->route('pengelola.kelola_kendaraan.index')->with('error', 'Kendaraan tidak ditemukan.');
        }
    }

public function update(KendaraanRequest $request, $plat_nomor)
{
    // Mencari kendaraan berdasarkan plat_nomor
    $kendaraan = Kendaraan::where('plat_nomor', $plat_nomor)->first();

    if (!$kendaraan) {
        Log::warning("Kendaraan dengan plat nomor {$plat_nomor} tidak ditemukan.");
        return redirect()->route('pengelola.kelola_kendaraan.index')->with('error', 'Kendaraan tidak ditemukan!');
    }

    // Mencatat log awal pembaruan
    Log::info("Memulai pembaruan kendaraan dengan plat nomor {$plat_nomor}");

    // Menyimpan data lama untuk log perubahan
    $oldJenis = $kendaraan->jenis;
    $oldWarna = $kendaraan->warna;
    $oldFoto = $kendaraan->foto;
    $oldIdPengguna = $kendaraan->id_pengguna;

    // Validasi id_pengguna jika diubah
    if ($request->id_pengguna && $request->id_pengguna != $oldIdPengguna) {
        $newIdPengguna = $request->id_pengguna;

        // Pastikan pengguna yang dipilih belum memiliki kendaraan
        $userHasVehicle = Kendaraan::where('id_pengguna', $newIdPengguna)->exists();
        if ($userHasVehicle) {
            Log::warning("Pengguna dengan ID {$newIdPengguna} sudah memiliki kendaraan. Pembaruan ditolak.");
            return redirect()->back()->with('error', 'Pengguna yang dipilih sudah memiliki kendaraan!');
        }

        // Update id_pengguna
        $kendaraan->id_pengguna = $newIdPengguna;
        Log::info("Id pengguna kendaraan dengan plat nomor {$plat_nomor} diubah menjadi {$newIdPengguna}");
    }

    // Update jenis dan warna kendaraan
    $kendaraan->jenis = $request->jenis;
    $kendaraan->warna = $request->warna;

    // Mengelola upload foto kendaraan jika ada
    if ($request->hasFile('foto_kendaraan')) {
        // Hapus foto lama jika ada
        if ($oldFoto && file_exists(public_path($oldFoto))) {
            unlink(public_path($oldFoto));
            Log::info("Foto lama kendaraan dengan plat nomor {$plat_nomor} dihapus: {$oldFoto}");
        }

        // Simpan foto baru di public/images/kendaraan
        $newFotoName = time() . '_' . $request->file('foto_kendaraan')->getClientOriginalName();
        $filePath = 'images/kendaraan/' . $newFotoName;
        $request->file('foto_kendaraan')->move(public_path('images/kendaraan'), $newFotoName);

        // Update path foto ke database
        $kendaraan->foto = $filePath;
        Log::info("Foto baru kendaraan dengan plat nomor {$plat_nomor} disimpan: {$filePath}");
    }

    // Simpan perubahan ke database
    $kendaraan->save();

    // Catat perubahan yang dilakukan
    Log::info("Kendaraan dengan plat nomor {$plat_nomor} berhasil diperbarui. Perubahan: " .
        "Jenis dari {$oldJenis} menjadi {$kendaraan->jenis}, " .
        "Warna dari {$oldWarna} menjadi {$kendaraan->warna}, " .
        "Id Pengguna dari {$oldIdPengguna} menjadi {$kendaraan->id_pengguna}, " .
        "Foto dari {$oldFoto} menjadi {$kendaraan->foto}.");

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('pengelola.kelola_kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui!');
}




    /**
     * Delete the specified vehicle.
     */
    public function destroy($platNomor)
    {
        // Find the vehicle by plat nomor
        $kendaraan = Kendaraan::where('plat_nomor', $platNomor)->firstOrFail();

        try {
            // Delete the vehicle's photo if it exists
            if ($kendaraan->foto) {
                Storage::disk('public')->delete($kendaraan->foto);
            }

            // Delete the QR code if it exists
            if ($kendaraan->qr_code_url) {
                Storage::disk('public')->delete($kendaraan->qr_code_url);
            }

            // Delete the vehicle record
            $kendaraan->delete();

            return redirect()->route('pengelola.kelola_kendaraan.index')->with('success', 'Kendaraan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete vehicle: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kendaraan');
        }
    }

    /**
     * Generate QR code for the vehicle.
     */

    /**
     * Get enum values from a given table and column.
     */
    protected function getEnumValues($table, $column)
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

        return []; // Return an empty array if no result
    }
}
