<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $view = Auth::guard('pengelola')->check() ? 'pengelola.profile' : 'pengguna.profile';
        return view($view, compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        try {
            $this->updateUserData($user, $request);
            $userId = Auth::guard('pengelola')->check() ? $user->id_pengelola : $user->id_pengguna;
            Log::info('Profil berhasil diperbarui untuk pengguna ID: ' . $userId);
            return back()->with('success', 'Profile berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui profil untuk pengguna ID: ' . ($user->id_pengguna ?? $user->id_pengelola) . ' - Error: ' . $e->getMessage());
            return back()->withErrors('Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    private function updateUserData($user, $request)
    {
        $user->nama = $request->nama;
        $user->email = $request->email;

        // Upload foto ke Cloudinary
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            // Hapus foto lama dari Cloudinary jika bukan default
            if ($user->foto && !str_contains($user->foto, 'default.jpg')) {
                try {
                    // Ambil public ID dari URL Cloudinary sebelumnya (jika kamu simpan di DB)
                    $publicId = pathinfo(parse_url($user->foto, PHP_URL_PATH), PATHINFO_FILENAME);
                    Cloudinary::destroy("profil/$publicId");
                    Log::info("Foto lama Cloudinary dihapus: profil/$publicId");
                } catch (\Exception $e) {
                    Log::warning("Gagal menghapus foto lama dari Cloudinary: " . $e->getMessage());
                }
            }

            // Upload ke Cloudinary
            $uploaded = Cloudinary::upload($foto->getRealPath(), [
                'folder' => 'profil',
                'public_id' => time() . '_' . pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME),
                'overwrite' => true,
                'resource_type' => 'image'
            ]);

            // Simpan URL foto baru
            $user->foto = $uploaded->getSecurePath();
            Log::info("Foto baru diunggah ke Cloudinary: " . $user->foto);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = $request->password; // encrypt jika perlu
        }

        if (!$user->save()) {
            throw new \Exception('Gagal memperbarui profil.');
        }
    }
}
