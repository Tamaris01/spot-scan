<?php

namespace App\Http\Controllers;

use App\Http\Requests\KendaraanRequest;
use Illuminate\Http\Request;
use App\Models\PenggunaParkir;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    protected $jenisKendaraanArray = [];
    protected $warnaKendaraanArray = [];

    public function __construct()
    {
        // Initialize enum values for vehicle type and color
        $this->jenisKendaraanArray = $this->getEnumValues('kendaraan', 'jenis');
        $this->warnaKendaraanArray = $this->getEnumValues('kendaraan', 'warna');
    }

    /**
     * Display vehicle data for the logged-in user.
     */
    public function showKendaraanUser()
    {
        $user = Auth::guard('pengguna')->user(); // Use the 'pengguna' guard for authentication
        $kendaraan = $user->kendaraan; // Assuming a hasOne relationship in PenggunaParkir

        return view('pengguna.kendaraan', [
            'kendaraan' => $kendaraan,
            'jenisKendaraanArray' => $this->jenisKendaraanArray,
            'warnaKendaraanArray' => $this->warnaKendaraanArray,
        ]);
    }

    /**
     * Update the logged-in user's vehicle data.
     */
    public function update(KendaraanRequest $request)
    {
        $user = Auth::guard('pengguna')->user();
        $kendaraan = $user->kendaraan;

        Log::info('User attempting to update vehicle:', ['user_id' => $user->id, 'vehicle' => $kendaraan]);

        if (!$kendaraan) {
            Log::warning('Vehicle not found for user:', ['user_id' => $user->id]);
            return redirect()->back()->with('error', 'Kendaraan tidak ditemukan.');
        }

        Log::info('Received data for update:', $request->all());

        try {
            Log::info('Vehicle data before update:', $kendaraan->toArray());

            // Update vehicle data
            $kendaraan->jenis = $request->jenis;
            $kendaraan->warna = ucwords(strtolower($request->warna));

            // Handle vehicle photo upload
            if ($request->hasFile('foto_kendaraan')) {
                $path = public_path('images/kendaraan');

                // Ensure directory exists
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                // Delete old photo if a new one is uploaded
                if ($kendaraan->foto && File::exists($path . '/' . $kendaraan->foto)) {
                    File::delete($path . '/' . $kendaraan->foto);
                    Log::info('Old photo deleted:', ['photo' => $kendaraan->foto]);
                }

                // Store new photo
                $filename = time() . '_' . $request->file('foto_kendaraan')->getClientOriginalName();
                $request->file('foto_kendaraan')->move($path, $filename);
                $kendaraan->foto = 'images/kendaraan/' . $filename;

                Log::info('New photo saved:', ['photo' => $kendaraan->foto]);
            }

            $kendaraan->save();

            Log::info('Vehicle data after update:', $kendaraan->toArray());

            return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update vehicle data: ' . $e->getMessage(), ['user_id' => $user->id]);
            return redirect()->back()->with('error', 'Gagal memperbarui data kendaraan.');
        }
    }

    /**
     * Get enum values from a specific table column.
     *
     * @param string $table
     * @param string $column
     * @return array
     */
    protected function getEnumValues($table, $column)
    {
        $result = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column]);
        if (count($result) > 0) {
            $type = $result[0]->Type;
            if (preg_match('/^enum\((.*)\)$/', $type, $matches)) {
                $enum = [];

                foreach (explode(',', $matches[1]) as $value) {
                    $enum[] = trim($value, "'");
                }

                return $enum;
            }
        }

        return []; // Return an empty array if no results or if no match is found
    }
}
