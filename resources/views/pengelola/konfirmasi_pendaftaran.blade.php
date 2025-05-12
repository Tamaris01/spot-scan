@extends('layouts.pengelola')
@section('title', 'Konfirmasi Pendaftaran')

@section('content')
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Roboto', Arial, sans-serif;
    }

    .border-black {
        border-color: black;
    }

    .bg-putih {
        background-color: #ffff;
    }

    .jarak-button {
        margin-right: 10px;
    }

    #userImage {
        width: 100%;
        max-width: 300px;
        height: auto;
        object-fit: cover;
        max-height: 300px;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }
</style>

<div class="container">
    <h3 class="mb-4">Konfirmasi Pendaftaran</h3>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card border-black">
                <div class="card-body">
                    <div class="header-container d-flex justify-content-between mb-3">
                        <div>
                            <span class="ml-2">Tampilkan</span>
                            <select id="rows" class="custom-select d-inline border-black" style="width: auto;">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                            <span class="ml-2">Baris</span>
                        </div>
                        <div class="search-container d-flex">
                            <form method="GET" action="{{ route('pengelola.konfirmasi_pendaftaran.search') }}" class="d-flex">
                                <input type="text" name="query" class="form-control border-black" placeholder="Pencarian" style="width: 250px;" value="{{ request()->get('query') }}">
                                <button class="btn ml-2" style="background-color: #ffdc40;" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($pendaftar->isEmpty())
                    <p class="mt-3">Tidak ada data yang ditemukan untuk "{{ request()->get('query') }}"</p>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #ffdc40;">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pengguna</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-putih">
                                @foreach ($pendaftar as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->id_pengguna }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td class="text-center">
                                        <!-- Button Lihat -->
                                        <button class="btn btn-success btn-sm" onclick="lihat('{{ $data->id_pengguna }}', '{{ Storage::url($data->foto) }}')">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm jarak-button" onclick="showConfirmationModal('terima', '{{ $data->id_pengguna }}')">
                                            <i class="fa-sharp fa-solid fa-circle-check"></i> Terima
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="showConfirmationModal('tolak', '{{ $data->id_pengguna }}')">
                                            <i class="fa-sharp fa-solid fa-circle-xmark"></i> Tolak
                                        </button>

                                    </td>
                                </tr>
                                <!-- Modal untuk lihat Pengguna-->
                                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style=" display: flex;  justify-content: center;  align-items: center;  text-align: center;" role="document"> <!-- Center the modal -->
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="imageModalLabel">Foto Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img id="userImage" src="" alt="User Image" class="img-fluid" style="height: 50%; width: 50%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div>
                        <ul class="pagination d-flex justify-content-end">
                            <li class="page-item {{ $pendaftar->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pendaftar->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($pendaftar->getUrlRange(1, $pendaftar->lastPage()) as $page => $url)
                            <li class="page-item {{ $pendaftar->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $pendaftar->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $pendaftar->nextPageUrl() }}" aria-label="Next">
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

<!-- Modal for confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Center the modal -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #FFDC40;">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Pendaftaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center d-flex flex-column align-items-center">
                <div class="d-flex justify-content-center align-items-center" style="padding-bottom:10px;">
                    <i class="fas fa-question-circle" style="color: red; font-size: 3em; animation: bounce 1s infinite;"></i>
                </div>
                <p class="text-center">Apakah Anda yakin ingin <span id="actionText"></span> pendaftaran ini? <br> ID Pengguna : <span id="userId"></span></p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn" style="background-color: #FFFFFF; color: black; border: 1px solid black; width: 100px;" data-dismiss="modal">Batal</button>
                <button type="button" class="btn" id="confirmAction" style="background-color: #FFDC40; color: black; width: 100px;">Ya</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showConfirmationModal(action, id_pengguna) {
        const actionText = action === 'terima' ? 'menerima' : 'menolak';
        document.getElementById('actionText').textContent = actionText;
        document.getElementById('userId').textContent = id_pengguna;

        $('#confirmationModal').modal('show');

        document.getElementById('confirmAction').onclick = function() {
            const routeUrl = action === 'terima' ?
                `{{ route('pengelola.konfirmasi_pendaftaran.terima', ':id') }}` :
                `{{ route('pengelola.konfirmasi_pendaftaran.tolak', ':id') }}`;

            const url = routeUrl.replace(':id', id_pengguna);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'POST';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit(); // Kirim form
        };
    }

    // Melihat gambar pengguna di modal
    function lihat(id_pengguna, imageUrl) {
        var imgElement = document.getElementById('userImage'); // pastikan ID-nya benar
        imgElement.src = imageUrl; // Menetapkan URL gambar
        $('#imageModal').modal('show'); // Menampilkan modal
    }
    
   
    // Fungsi untuk memuat data tabel secara dinamis
    function loadTableData() {
        $.ajax({
            url: "{{ route('pengelola.konfirmasi_pendaftaran') }}", // Ganti URL dengan endpoint yang sesuai
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

<!-- jQuery dan Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

@endsection