<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PenggunaParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenggunaParkirController extends Controller
{
    protected $kategoriArray;

    public function __construct()
    {
        // Inisialisasi nilai enum kategori
        $this->kategoriArray = $this->getEnumValues('pengguna_parkir', 'kategori');
    }
    
    public function index(Request $request)
    {
        // Mendapatkan jumlah item per halaman dari parameter 'rows' (default ke 10)
        $perPage = $request->input('rows', 10);

        // Mendapatkan data pengguna dengan status aktif dan paginasi
        $pengguna = PenggunaParkir::where('status', 'aktif')->paginate($perPage);

        // Mengirimkan data pengguna dan kategori ke view
        return view('pengelola.kelola_pengguna', [
            'pengguna' => $pengguna,
            'kategoriArray' => $this->kategoriArray,
            'perPage' => $perPage // Menyertakan perPage untuk form filter jumlah per halaman
        ]);
    }

    public function search(Request $request)
    {
        // Mengambil query dari input pencarian
        $perPage = $request->get('rows', 10); // Default 10 rows per page
        $query = $request->input('query');

        // Mencari data pengguna berdasarkan nama atau email
        $pengguna = PenggunaParkir::where('nama', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->paginate($perPage);

        // Mengembalikan hasil pencarian ke view yang sama
        return view('pengelola.kelola_pengguna', compact('pengguna', 'query', 'perPage'));
    }

    protected function getEnumValues($table, $column)
    {
        // Ambil nilai enum dari kolom yang diberikan
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

    public function create()
    {
        return view('pengelola.modal.edit_pengguna', [
            'kategoriArray' => $this->kategoriArray // Pass the kategoriArray for form options
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'id_pengguna' => 'required_if:kategori,!Tamu|string|max:255|unique:pengguna_parkir,id_pengguna',
            'kategori' => 'required|string|in:' . implode(',', $this->getEnumValues('pengguna_parkir', 'kategori')),
            'nama' => 'required|string|regex:/^[A-Z][a-zA-Z\s]*$/|max:50',
            'email' => 'required|email|unique:pengguna_parkir,email|max:255',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Validasi dimensi foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $dimensions = getimagesize($foto);
                $width = $dimensions[0];
                $height = $dimensions[1];

                // Periksa dimensi gambar (472x472 piksel sebagai contoh 4x4 cm pada 300 DPI)
                if ($width !== 472 || $height !== 472) {
                    return redirect()->back()->withErrors(['foto' => 'Dimensi foto harus berukuran 472x472 piksel (4x4 cm pada 300 DPI).'])->withInput();
                }

                if ($request->hasFile('foto')) {
                    // Mendapatkan file dari request
                    $foto = $request->file('foto');

                    // Menentukan path penyimpanan langsung di folder public/images/profil
                    $fotoPath = 'images/profil/' . uniqid() . '_' . $foto->getClientOriginalName();

                    // Memindahkan file ke folder public/images/profil
                    $foto->move(public_path('images/profil'), $fotoPath);
                } else {
                    $fotoPath = null;
                }
            }

            // Membuat instance PenggunaParkir baru
            $pengguna = new PenggunaParkir();
            $pengguna->id_pengguna = $request->kategori !== 'Tamu'
                ? $request->id_pengguna
                : 'Tamu_' . mt_rand(10000000, 99999999);

            $pengguna->nama = $request->nama;
            $pengguna->email = $request->email;
            $pengguna->password = $request->password; // Enkripsi password
            $pengguna->foto = $fotoPath;
            $pengguna->kategori = $request->kategori;
            $pengguna->status = 'aktif';
            $pengguna->save();

            return redirect()->route('pengelola.kelola_pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi error, log error dan tampilkan pesan kegagalan
            Log::error('Error saving pengguna: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengguna.');
        }
    }

    public function edit($id_pengguna)
    {
        // Menggunakan find untuk mencari berdasarkan id_pengguna
        $pengguna = PenggunaParkir::findOrFail($id_pengguna);

        return response()->json([
            'view' => view('pengelola.kelola_pengguna.edit', compact('pengguna'))->render()
        ]);
    }

public function update(Request $request, $id_pengguna)
{
    // Validasi data yang diterima
    $validated = $request->validate([
        'kategori' => 'required|string|in:' . implode(',', $this->getEnumValues('pengguna_parkir', 'kategori')),
        'nama' => 'required|string|regex:/^[A-Z][a-zA-Z\s,\.]*$/|max:50',
        'email' => 'required|email|unique:pengguna_parkir,email,' . $id_pengguna . ',id_pengguna|max:255',
        'password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // Password bisa kosong jika tidak diubah
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto bisa kosong jika tidak diubah
    ]);

    try {
        // Menemukan pengguna berdasarkan id_pengguna
        $pengguna = PenggunaParkir::findOrFail($id_pengguna);

        // Menyimpan path foto lama sebelum diubah (jika ada)
        $oldFoto = $pengguna->foto;

        // Menangani upload foto jika ada file baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan tidak menggunakan foto default
            if ($oldFoto && $oldFoto !== 'images/profil/default.jpg' && file_exists(public_path($oldFoto))) {
                unlink(public_path($oldFoto)); // Menghapus file lama
                Log::info("Foto lama pengguna dengan id {$id_pengguna} dihapus: {$oldFoto}");
            }

            // Simpan foto baru
            $fotoProfil = $request->file('foto');
            $fotoProfilPath = 'images/profil/' . time() . '_' . $fotoProfil->getClientOriginalName();
            $fotoProfil->move(public_path('images/profil'), $fotoProfilPath);
            $pengguna->foto = $fotoProfilPath;
            Log::info("Foto baru untuk pengguna dengan id {$id_pengguna} disimpan: {$fotoProfilPath}");
        }

        // Menangani perubahan password jika ada
        if ($request->filled('password')) {
            $pengguna->password = $request->password; // Menyimpan password secara langsung (tanpa enkripsi)
        }

        // Update data pengguna selain ID Pengguna
        $pengguna->update([
            'kategori' => $validated['kategori'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        // Redirect setelah sukses
        return redirect()->route('pengelola.kelola_pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    } catch (\Exception $e) {
        // Log error jika terjadi kesalahan
        Log::error('Error updating pengguna: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengguna.');
    }
}



public function destroy($id_pengguna)
{
    Log::info("Menghapus pengguna dengan ID: $id_pengguna");

    // Cari pengguna berdasarkan ID
    $pengguna = PenggunaParkir::find($id_pengguna);

    if ($pengguna) {
        // Jika pengguna memiliki foto, hapus foto tersebut
        if ($pengguna->foto && $pengguna->foto !== 'images/profil/default.jpg' && file_exists(public_path($pengguna->foto))) {
            unlink(public_path($pengguna->foto)); // Menghapus file foto
            Log::info("Foto pengguna dengan ID $id_pengguna berhasil dihapus: {$pengguna->foto}");
        }

        // Hapus data pengguna dari database
        $pengguna->delete();
        Log::info("Pengguna berhasil dihapus.");

        return redirect()->route('pengelola.kelola_pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    } else {
        Log::error("Pengguna dengan ID $id_pengguna tidak ditemukan.");
        return redirect()->route('pengelola.kelola_pengguna.index')->with('error', 'Pengguna tidak ditemukan.');
    }
}

}
