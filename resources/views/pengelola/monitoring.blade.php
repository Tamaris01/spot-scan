@extends('layouts.pengelola')
@section('title', 'Monitoring Parkir')

@section('content')
<style>
    .border-black {
        border-color: black;
    }

    /* .table th,
    .table td {
        vertical-align: middle;
    } */

    .btn-info,
    .btn-danger {
        font-weight: bold;
    }

    /* / */
    /* Styling untuk div utama */
    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    /* h3 {
        font-size: 1.75rem;
        font-weight: bold;
        color: #333;
        margin: 0;
    } */

    /* Styling untuk jam */
    .date-display {
        font-size: 2rem;
        /* Mengatur ukuran font lebih kecil */
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        color: white;
        /* Mengubah warna teks menjadi hitam */
        padding: 8px 18px;
        background-color: #FFDC40;
        /* Warna latar belakang kuning */
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .date-display:hover {
        transform: scale(1.05);
        /* Efek sedikit pembesaran saat hover */
        color: white;
        /* Warna teks saat hover */
        background-color: #FFDC40;
        /* Latar belakang tetap kuning saat hover */
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }

    /* Styling border dan padding */
    .date-display {
        display: inline-block;
        padding: 8px 16px;
        background: black;
        /* Warna latar belakang kuning */
        color: #FFDC40;
        /* Teks hitam */
        font-size: 1rem;
        /* Ukuran font lebih kecil */
        border-radius: 5px;
        text-align: center;
        letter-spacing: 1px;
        transition: transform 0.3s ease;
    }

    .date-display:after {
        content: '';
        display: block;
        width: 100%;
        height: 3px;
        background-color: #FFDC40;
        /* Warna garis bawah tetap kuning */
        margin-top: 5px;
        border-radius: 50%;
    }

    /* Animasi perubahan jam */
    @keyframes pulse {
        0% {
            transform: scale(1);
            background-color: #f0f0f0;
        }

        50% {
            transform: scale(1.05);
            background-color: #ff6347;
            /* Warna merah saat animasi */
        }

        100% {
            transform: scale(1);
            background-color: #FFDC40;
            /* Warna latar belakang kuning kembali */
        }
    }


    /* .date-display {
        animation: pulse 10s infinite;
    } */
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Monitoring</h3>
        <p class="date-display mb-0" id="current-time"></p>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-black">
                <div class="card-body">
                    <div class="header-container d-flex justify-content-between mb-1">
                        <div class="d-flex align-items-center">
                            <span class="ml-2">Tampilkan</span>
                            <form method="GET" action="{{ route('pengelola.monitoring.index') }}" style="display: inline;">
                                <select id="rows" name="rows" class="custom-select d-inline border-black ml-2" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </form>
                            <span class="ml-2">Baris</span>
                        </div>
                        <div class="search-container">
                            <form method="GET" action="{{ route('pengelola.monitoring.search') }}" class="d-flex align-items-center">
                                <input type="text" name="query" class="form-control border-black" placeholder="Pencarian" style="width: auto;" value="{{ request()->get('query') }}">
                                <button class="btn search-btn ml-2" style="background-color: #ffdc40;" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($riwayatParkir->isEmpty())
                    <p class="mt-3">Tidak ada Monitoring Parkir yang ditemukan untuk "{{ request()->get('query') }}"</p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #ffdc40;">
                                <tr>
                                    <th>ID Parkir</th>
                                    <th>ID Pengguna</th>
                                    <th>Plat Nomor</th>
                                    <th>Waktu Masuk</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody class="bg-putih">
                                @foreach($riwayatParkir as $riwayat)
                                <tr>
                                    <td>{{ $riwayat->id_riwayat_parkir }}</td>
                                    <td>{{ $riwayat->pengguna->id_pengguna }}</td>
                                    <td>{{ $riwayat->kendaraan->plat_nomor }}</td>
                                    <td>{{ $riwayat->waktu_masuk }}</td>
                                    <td>
                                        @if($riwayat->status_parkir == 'masuk')
                                        <span style="color: green;">{{ $riwayat->status_parkir }}</span>
                                        @elseif($riwayat->status_parkir == 'keluar')
                                        <span style="color: red;">{{ $riwayat->status_parkir }}</span>
                                        @else
                                        {{ $riwayat->status_parkir }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <!-- Pagination -->
                    <div>
                        <ul class="pagination d-flex justify-content-end">
                            <li class="page-item {{ $riwayatParkir->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $riwayatParkir->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($riwayatParkir->getUrlRange(1,$riwayatParkir->lastPage()) as $page => $url)
                            <li class="page-item {{ $riwayatParkir->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{$riwayatParkir->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $riwayatParkir->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateTime() {
        // Mendapatkan waktu sekarang
        let now = new Date();

        // Mendapatkan jam, menit, dan detik
        let hours = now.getHours().toString().padStart(2, '0'); // Format 2 digit
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');

        // Format jam: HH:MM:SS
        let formattedTime = `${hours}:${minutes}:${seconds}`;

        // Menampilkan waktu di elemen dengan id 'current-time'
        document.getElementById("current-time").textContent = formattedTime;
    }

    // Memperbarui waktu setiap detik
    setInterval(updateTime, 1000);

    // Panggil fungsi sekali untuk menampilkan waktu segera setelah halaman dimuat
    updateTime();
</script>
<script>
    // Fungsi untuk memuat data tabel secara dinamis
    function loadTableData() {
        $.ajax({
            url: "{{ route('pengelola.monitoring.index') }}", // Ganti URL dengan endpoint yang sesuai
            type: "GET",
            dataType: "html",
            success: function(response) {
                // Cari elemen tbody dan perbarui dengan data baru
                const newTableBody = $(response).find('tbody').html();
                $('tbody').html(newTableBody);
            },
            error: function(xhr, status, error) {
                console.error("Gagal memuat data tabel:", error);
            }
        });
    }

    // Panggil fungsi setiap 2 detik
    setInterval(loadTableData, 2000);

    // Panggil fungsi sekali saat halaman dimuat
    loadTableData();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection