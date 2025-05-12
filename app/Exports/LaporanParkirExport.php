<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanParkirExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $riwayatParkir;

    public function __construct($riwayatParkir)
    {
        $this->riwayatParkir = $riwayatParkir;
    }

    public function collection()
    {
        return $this->riwayatParkir->map(function ($riwayat) {
            return [
                $riwayat->id_riwayat_parkir,
                $riwayat->pengguna->nama,
                $riwayat->pengguna->kategori,
                $riwayat->kendaraan->plat_nomor,
                $riwayat->kendaraan->jenis,
                date('d-m-Y H:i:s', strtotime($riwayat->waktu_masuk)),
                date('d-m-Y H:i:s', strtotime($riwayat->waktu_keluar)),
                $riwayat->lama_parkir . ' jam',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Parkir',
            'Nama Pengguna',
            'Kategori Pengguna',
            'Plat Nomor',
            'Jenis Kendaraan',
            'Waktu Masuk',
            'Waktu Keluar',
            'Lama Parkir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style for header row
            1 => ['font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']]],
            'A1:H1' => ['fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '007BFF']]],
            // Style for entire sheet
            'A:H' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
