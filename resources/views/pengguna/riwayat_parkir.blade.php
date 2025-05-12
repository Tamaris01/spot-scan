@extends('layouts.pengguna')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Roboto', Arial, sans-serif;
    }

    .card {
        margin-bottom: 20px;
        border: 1px solid #000;
    }

    .card-body {
        padding: 15px;
    }

    .table th {
        width: 30%;
        font-weight: 500;
        text-align: left;
        white-space: nowrap;
    }

    .table td:first-child {
        width: 5%;
        text-align: center;
    }

    .table td:last-child {
        width: 65%;
        word-break: break-word;
    }

    .riwayat-title {
        font-weight: bold;
    }

    @media (max-width: 576px) {

        .table th,
        .table td {
            font-size: 13px;
            white-space: nowrap;
        }

        .table {
            font-size: 14px;
        }

        .card-body {
            padding: 10px;
        }

        .riwayat-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 class="riwayat-title black">Riwayat Parkir Anda</h4>
        <p class="date-display mb-0">{{ $date }}</p>
    </div>

    @forelse ($riwayatParkir as $riwayat)
    <div class="card">
        <div class="card-body">
            <div class="details">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>Nomor Plat</th>
                            <td>:</td>
                            <td>{{ $riwayat->plat_nomor }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Masuk</th>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->waktu_masuk)->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Keluar</th>
                            <td>:</td>
                            <td>
                                {{ $riwayat->waktu_keluar 
                                        ? \Carbon\Carbon::parse($riwayat->waktu_keluar)->format('d-m-Y H:i:s') 
                                        : 'Masih Parkir' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Lama Parkir</th>
                            <td>:</td>
                            <td>
                                {{ $riwayat->waktu_keluar 
                                        ? \Carbon\Carbon::parse($riwayat->waktu_masuk)->diff(\Carbon\Carbon::parse($riwayat->waktu_keluar))->format('%H:%I:%S') 
                                        : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center mt-4">Belum ada riwayat parkir.</p>
    @endforelse
</div>

@endsection