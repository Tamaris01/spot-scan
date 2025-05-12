@extends('layouts.pengelola')
@section('title', 'Laporan Parkir')

@section('content')
<style>
    .border-black {
        border-color: black;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .btn-info,
    .btn-danger {
        font-weight: bold;
    }

    /* Tata letak form */
    .d-flex {
        display: flex;
        align-items: center;
        /* Sejajarkan elemen secara vertikal */
        gap: 1rem;
        /* Jarak antar elemen */
    }

    .flex-wrap {
        flex-wrap: wrap;
        /* Elemen akan turun ke baris berikut jika terlalu panjang */
    }

    .gap-3 {
        gap: 1.5rem;
        /* Jarak antar elemen lebih besar */
    }

    .gap-2 {
        gap: 0.5rem;
        /* Jarak antar tombol */
    }

    /* Margin untuk tombol */
    .mt-3 {
        margin-top: 1rem;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Laporan Parkir</h3>
    </div>

    <div class="mb-3">
        <form method="GET" action="{{ route('laporan_parkir.unduh') }}" id="downloadForm">
            <!-- Baris pertama: Label dan Input -->
            <div class="d-flex align-items-center gap-3">
                <!-- Unduh Berdasarkan -->
                <div class="d-flex align-items-center">
                    <label for="filter_type" class="me-2">Unduh Berdasarkan:</label>
                    <select id="filter_type" name="filter_type" class="form-control" onchange="toggleDateInputs()">
                        <option value="tanggal">Tanggal</option>
                        <option value="semua">Semua Laporan Parkir</option>
                    </select>
                </div>

                <!-- Tanggal Mulai -->
                <div id="start-date-group" class="d-flex align-items-center">
                    <label for="start_date" class="me-2">Tanggal Mulai:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" oninput="validateDates()">
                </div>

                <!-- Tanggal Akhir -->
                <div id="end-date-group" class="d-flex align-items-center">
                    <label for="end_date" class="me-2">Tanggal Akhir:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" oninput="validateDates()">
                </div>
            </div>


            <div class="d-flex justify-content-between align-items-center mt-3">
                <!-- Elemen tanggal -->
                <div id="start-date-group" class="d-flex align-items-center">
                    <p class="date-display mb-0">{{ $date }}</p>
                </div>

                <!-- Tombol -->
                <div class="d-flex gap-2">
                    <button type="submit" name="format" value="pdf" class="btn btn-danger font-weight-bold unduh-btn" disabled>
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="submit" name="format" value="csv" class="btn btn-success font-weight-bold unduh-btn" disabled>
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button type="submit" name="format" value="excel" class="btn btn-primary font-weight-bold unduh-btn" disabled>
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            </div>


        </form>
    </div>



    <div class="container mb-3">
        @if(session('message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-black">
                <div class="card-body">
                    <div class="header-container d-flex justify-content-between mb-3">
                        <div>
                            <span class="ml-2">Tampilkan</span>
                            <form method="GET" action="{{ route('pengelola.laporan_parkir.index') }}" style="display: inline;">
                                <select id="rows" name="rows" class="custom-select d-inline border-black" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </form>
                            <span class="ml-2">Baris</span>
                        </div>

                        <div class="search-container">
                            <form method="GET" action="{{ route('pengelola.laporan_parkir.search') }}" class="d-flex align-items-center">
                                <input type="text" name="query" class="form-control border-black" placeholder="Pencarian" style="width: auto;" value="{{ request()->get('query') }}">
                                <button class="btn search-btn ml-2" style="background-color: #ffdc40;" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($riwayatParkir->isEmpty())
                    <p class="mt-3">Tidak ada Laporan Parkir yang ditemukan untuk "{{ request()->get('query') }}"</p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #ffdc40;">
                                <tr>
                                    <th>ID Parkir</th>
                                    <th>ID Pengguna</th>
                                    <th>Plat Nomor</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Lama Parkir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-putih">
                                @foreach($riwayatParkir as $riwayat)
                                <tr>
                                    <td>{{ $riwayat->id_riwayat_parkir }}</td>
                                    <td>{{ $riwayat->pengguna->id_pengguna }}</td>
                                    <td>{{ $riwayat->kendaraan->plat_nomor  }}</td>
                                    <td>{{ $riwayat->waktu_masuk }}</td>
                                    <td>{{ $riwayat->waktu_keluar }}</td>
                                    <td>{{ $riwayat->lama_parkir }}</td>
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
    function openUnduh() {
        // Ganti dengan URL yang benar sesuai dengan route Anda
        window.location.href = "{{ route('laporan_parkir.unduh', ['rows' => $perPage]) }}";
    }



    function toggleDateInputs() {
        const filterType = document.getElementById('filter_type').value;
        const startGroup = document.getElementById('start-date-group');
        const endGroup = document.getElementById('end-date-group');

        if (filterType === 'semua') {
            startGroup.style.display = 'none';
            endGroup.style.display = 'none';
            toggleButtons(true); // Aktifkan tombol karena tidak perlu tanggal
        } else {
            startGroup.style.display = 'flex';
            endGroup.style.display = 'flex';
            validateDates(); // Validasi tanggal
        }
    }

    function validateDates() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const isValid = startDate && endDate;

        toggleButtons(isValid);
    }

    function toggleButtons(enabled) {
        // Hanya memengaruhi tombol dengan kelas "unduh-btn"
        const unduhButtons = document.querySelectorAll("button.unduh-btn");
        unduhButtons.forEach(button => {
            button.disabled = !enabled;
        });
    }


    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', () => {
        toggleDateInputs();
    });
    
    
    // Fungsi untuk memuat data tabel secara dinamis
    function loadTableData() {
        $.ajax({
            url: "{{ route('pengelola.laporan_parkir.index') }}", // Ganti URL dengan endpoint yang sesuai
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