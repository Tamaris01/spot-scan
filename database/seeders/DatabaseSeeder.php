<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenggunaParkir;
use App\Models\Kendaraan;
use App\Models\PengelolaParkir;
use App\Models\RiwayatParkir;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Pengelola Parkir (Harus dimasukkan terlebih dahulu)
        PengelolaParkir::create([
            'id_pengelola' => '1234567890',
            'nama' => 'Admin Parkir',
            'email' => 'adminparkir@gmail.com',
            'password' => 'Admin123!',
            'foto' => 'profil/default.png',
        ]);

        // 2. Data Pengguna
        $penggunaData = [
            // Mahasiswa
            ['id_pengguna' => '4342211050', 'nama' => 'Tamaris Roulina S', 'email' => 'tama@gmail.com', 'password' => 'Tama123!', 'foto' => 'profil/Tamaris.png', 'kategori' => 'mahasiswa', 'status' => 'aktif'],
            ['id_pengguna' => '4342211036', 'nama' => 'Elsa Marina S', 'email' => 'elsa@gmail.com', 'password' => 'Elsa123!', 'foto' => 'profil/Elsa.jpg', 'kategori' => 'mahasiswa', 'status' => 'aktif'],
            ['id_pengguna' => '4342211041', 'nama' => 'Elicia Sandova', 'email' => 'elicia@gmail.com', 'password' => 'Elicia123!', 'foto' => 'profil/Elicia.jpg', 'kategori' => 'mahasiswa', 'status' => 'aktif'],
            ['id_pengguna' => '4342211045', 'nama' => 'Alifzidan Rizky', 'email' => 'alif@gmail.com', 'password' => 'Alifzidan123!', 'foto' => 'profil/Alifzidan.jpg', 'kategori' => 'mahasiswa', 'status' => 'aktif'],
            ['id_pengguna' => '4342211046', 'nama' => 'Maulana Arianto', 'email' => 'maulana@gmail.com', 'password' => 'Maulana123!', 'foto' => 'profil/Maulana.jpg', 'kategori' => 'mahasiswa', 'status' => 'aktif'],
            // Dosen/Karyawan
            ['id_pengguna' => '222331', 'nama' => 'Gilang Bagus Ramadhan, A.Md. Kom', 'email' => 'gilang@polibatam.ac.id', 'password' => 'Gilang123!', 'foto' => 'profil/Gilang.jpg', 'kategori' => 'dosen/karyawan', 'status' => 'aktif'],
            ['id_pengguna' => '222332', 'nama' => 'Iqbal Afif, A.Md.Kom', 'email' => 'iqbal@polibatam.ac.id', 'password' => 'Iqbal123!', 'foto' => 'profil/Iqbal.jpg', 'kategori' => 'dosen/karyawan', 'status' => 'aktif'],

            ['id_pengguna' => '4342211082', 'nama' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'password' => 'Budi123!', 'foto' => 'profil/Budi.jpg', 'kategori' => 'tamu', 'status' => 'aktif'],
            ['id_pengguna' => '4342211083', 'nama' => 'Anna Wati', 'email' => 'anna@gmail.com', 'password' => 'Anna123!', 'foto' => 'profil/Anna.jpg', 'kategori' => 'tamu', 'status' => 'aktif'],
        ];

        foreach ($penggunaData as $data) {
            PenggunaParkir::create($data);
        }

        // 3. Data Kendaraan
        $kendaraanData = [
            ['plat_nomor' => 'BP 1234 TA', 'jenis' => 'motor', 'warna' => 'putih', 'foto' => 'kendaraan/motor1.jpg', 'id_pengguna' => '4342211050'],
            ['plat_nomor' => 'BP 5678 EL', 'jenis' => 'mobil', 'warna' => 'putih', 'foto' => 'kendaraan/mobil1.jpg', 'id_pengguna' => '4342211036'],
            ['plat_nomor' => 'BP 9101 EC', 'jenis' => 'motor', 'warna' => 'putih', 'foto' => 'kendaraan/motor2.jpg', 'id_pengguna' => '4342211041'],
            ['plat_nomor' => 'BP 1121 AZ', 'jenis' => 'mobil', 'warna' => 'putih', 'foto' => 'kendaraan/mobil2.jpg', 'id_pengguna' => '4342211045'],
            ['plat_nomor' => 'BP 3141 MA', 'jenis' => 'motor', 'warna' => 'putih', 'foto' => 'kendaraan/motor3.jpg', 'id_pengguna' => '4342211046'],
            ['plat_nomor' => 'BP 0001 GA', 'jenis' => 'mobil', 'warna' => 'kuning', 'foto' => 'kendaraan/mobil3.jpg', 'id_pengguna' => '222331'],
            ['plat_nomor' => 'BP 0002 IQ', 'jenis' => 'motor', 'warna' => 'merah', 'foto' => 'kendaraan/motor4.jpg', 'id_pengguna' => '222332'],
            ['plat_nomor' => 'BP 0003 BW', 'jenis' => 'motor', 'warna' => 'biru', 'foto' => 'kendaraan/motor5.jpg', 'id_pengguna' => '4342211082'],
            ['plat_nomor' => 'BP 0004 AW', 'jenis' => 'mobil', 'warna' => 'merah', 'foto' => 'kendaraan/mobil4.jpg', 'id_pengguna' => '4342211083'],
        ];

        // if (!Storage::disk('public')->exists('images/qrcodes')) {
        //     Storage::disk('public')->makeDirectory('images/qrcodes');
        // }

        foreach ($kendaraanData as $data) {
            $kendaraan = Kendaraan::create($data);

            // // Membuat QR Code untuk kendaraan
            // $qrCodePath = 'images/qrcodes/' . $kendaraan->plat_nomor . '.png';
            // QrCode::format('png')->size(300)->generate($kendaraan->plat_nomor, storage_path('app/public/' . $qrCodePath));
            // $kendaraan->qr_code = $qrCodePath;
            $kendaraan->save();
        }

        $riwayatParkirData = [
            // Data sebelumnya yang sudah ada
            ['id_pengguna' => '4342211050', 'plat_nomor' => 'BP 1234 TA',  'waktu_masuk' => Carbon::today()->setTime(7, 0, 0), 'waktu_keluar' => Carbon::today()->setTime(6, 0, 0), 'status_parkir' => 'keluar'],
            ['id_pengguna' => '4342211036', 'plat_nomor' => 'BP 5678 EL',  'waktu_masuk' => Carbon::today()->setTime(7, 0, 0), 'waktu_keluar' => Carbon::today()->setTime(5, 0, 0), 'status_parkir' => 'keluar'],
            ['id_pengguna' => '4342211041', 'plat_nomor' => 'BP 9101 EC',  'waktu_masuk' => Carbon::today()->setTime(8, 0, 0), 'waktu_keluar' => Carbon::today()->setTime(4, 0, 0), 'status_parkir' => 'keluar'],
            ['id_pengguna' => '4342211045', 'plat_nomor' => 'BP 1121 AZ', 'waktu_masuk' => Carbon::today()->setTime(7, 0, 0), 'waktu_keluar' => Carbon::today()->setTime(7, 0, 0), 'status_parkir' => 'keluar'],
            ['id_pengguna' => '4342211046', 'plat_nomor' => 'BP 3141 MA', 'waktu_masuk' => Carbon::today()->setTime(9, 0, 0), 'waktu_keluar' => Carbon::today()->setTime(8, 0, 0), 'status_parkir' => 'keluar'],

            // Rentang pagi, siang, sore, malam
            ['id_pengguna' => '4342211050', 'plat_nomor' => 'BP 1234 TA',  'waktu_masuk' => Carbon::today()->setTime(7, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211036', 'plat_nomor' => 'BP 5678 EL',  'waktu_masuk' => Carbon::today()->setTime(8, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211041', 'plat_nomor' => 'BP 9101 EC',  'waktu_masuk' => Carbon::today()->setTime(12, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211045', 'plat_nomor' => 'BP 1121 AZ',  'waktu_masuk' => Carbon::today()->setTime(13, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211050', 'plat_nomor' => 'BP 1234 TA', 'waktu_masuk' => Carbon::today()->setTime(15, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211036', 'plat_nomor' => 'BP 5678 EL', 'waktu_masuk' => Carbon::today()->setTime(15, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211041', 'plat_nomor' => 'BP 9101 EC',  'waktu_masuk' => Carbon::today()->setTime(17, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211045', 'plat_nomor' => 'BP 1121 AZ',  'waktu_masuk' => Carbon::today()->setTime(18, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211082', 'plat_nomor' => 'BP 0003 BW', 'waktu_masuk' => Carbon::today()->setTime(18, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '4342211083', 'plat_nomor' => 'BP 0004 AW', 'waktu_masuk' => Carbon::today()->setTime(18, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '222331', 'plat_nomor' => 'BP 0001 GA', 'waktu_masuk' => Carbon::today()->setTime(19, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
            ['id_pengguna' => '222332', 'plat_nomor' => 'BP 0002 IQ', 'waktu_masuk' => Carbon::today()->setTime(20, 0, 0), 'waktu_keluar' => null, 'status_parkir' => 'masuk'],
        ];

        // Get the last ID and increment it
        $lastRecord = RiwayatParkir::latest('id_riwayat_parkir')->first();
        $lastId = $lastRecord ? (int)substr($lastRecord->id_riwayat_parkir, 4) : 0;

        foreach ($riwayatParkirData as $data) {
            // Increment ID by 1
            $lastId++;
            $id_riwayat_parkir = 'PARK' . str_pad($lastId, 3, '0', STR_PAD_LEFT); // Generate the next ID

            // Insert the record with the generated ID
            RiwayatParkir::create(array_merge($data, ['id_riwayat_parkir' => $id_riwayat_parkir]));
        }
    }
}
