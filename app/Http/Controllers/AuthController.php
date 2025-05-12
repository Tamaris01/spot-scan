<?php

namespace App\Http\Controllers;

use App\Models\PenggunaParkir;
use App\Models\PengelolaParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Mengarahkan ke halaman login
    }

    /**
     * Proses login untuk Pengguna dan Pengelola Parkir.
     */
    public function login(Request $request)
    {
        // Validasi input form login
        $credentials = $request->validate([
            'id' => ['required'], // ID bisa untuk Pengguna atau Pengelola
            'password' => ['required'], // Kata sandi
        ]);

        // Log informasi saat mencoba login
        Log::info('Login attempt', ['id' => $credentials['id']]);

        // Cek apakah ID pengguna ada di model PenggunaParkir
        $user = PenggunaParkir::where('id_pengguna', $credentials['id'])->first();

        // Jika pengguna tidak ditemukan, cek di model PengelolaParkir
        $guard = 'pengguna'; // Default guard pengguna
        if (!$user) {
            $user = PengelolaParkir::where('id_pengelola', $credentials['id'])->first();
            $guard = 'pengelola'; // Ubah guard jika yang login adalah pengelola
        }

        // Jika pengguna atau pengelola ditemukan
        if ($user) {
            Log::info('User found', ['id' => $credentials['id']]);

            // Jika yang ditemukan adalah pengguna, cek status aktif
            if ($guard === 'pengguna' && $user->status !== 'aktif') {
                Log::warning('Login attempt for non-active user', ['id_pengguna' => $user->id_pengguna]);
                return back()->with('status', 'error')->with('message', 'Maaf,akun anda belum aktif!')->onlyInput('id');
            }

            // Cek apakah password cocok dengan yang di-hash di database
            if (Hash::check($credentials['password'], $user->password)) {
                // Login berhasil, autentikasi pengguna dengan guard yang sesuai
                Auth::guard($guard)->login($user);
                $request->session()->regenerate(); // Regenerasi session

                // Cek tipe user dan arahkan ke dashboard yang sesuai
                if ($guard === 'pengguna') {
                    Log::info('Pengguna logged in', ['id_pengguna' => $user->id_pengguna]);
                    return redirect()->route('pengguna.dashboard')->with('status', 'success')->with('message', 'Selamat datang, Anda berhasil masuk!');
                } elseif ($guard === 'pengelola') {
                    Log::info('Pengelola logged in', ['id_pengelola' => $user->id_pengelola]);
                    return redirect()->route('pengelola.dashboard')->with('status', 'success')->with('message', 'Selamat datang, Anda berhasil masuk!');
                }
            } else {
                // Password salah
                Log::warning('Password salah untuk pengguna/pengelola', ['id' => $credentials['id']]);
                return back()->with('status', 'error')->with('message', 'Password anda salah.')->onlyInput('id');
            }
        } else {
            // Pengguna atau pengelola tidak ditemukan
            Log::warning('User tidak ditemukan', ['id' => $credentials['id']]);
            return back()->with('status', 'error')->with('message', 'ID tidak ditemukan.')->onlyInput('id');
        }
    }

    public function logout(Request $request)
    {
        $userId = null; // Initialize user ID variable for logging

        // Check which guard is currently authenticated and log out accordingly
        if (Auth::guard('pengguna')->check()) {
            $user = Auth::guard('pengguna')->user(); // Get authenticated user
            if ($user) {
                $userId = $user->id_pengguna; // Get id_pengguna
                Auth::guard('pengguna')->logout(); // Logout pengguna
                Log::info('Pengguna logged out', ['id_pengguna' => $userId]);
            }
        } elseif (Auth::guard('pengelola')->check()) {
            $user = Auth::guard('pengelola')->user(); // Get authenticated user
            if ($user) {
                $userId = $user->id_pengelola; // Get id_pengelola
                Auth::guard('pengelola')->logout(); // Logout pengelola
                Log::info('Pengelola logged out', ['id_pengelola' => $userId]);
            }
        }

        // Hapus session yang ada
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Regenerasi token CSRF untuk keamanan

        // Redirect ke halaman login setelah logout dengan notifikasi
        return redirect('/login')->with('status', 'success')->with('message', 'Anda berhasil logout.');
    }
}
