@extends('layouts.pengelola')
@section('title', 'Kelola Kendaraan')

@section('content')
<style>
    .border-black {
        border-color: black;
    }

    .bg-putih {
        background-color: #ffff;
    }

    .jarak-button {
        margin-right: 10px;
    }


    #qrCodeImage {
        max-width: 100%;
        /* Ensure the image is responsive */
        max-height: 300px;
        /* Limit the maximum height of the QR code */
    }


    /* Modal */
    .upload-icon {
        position: absolute;
        bottom: 0;
        background-color: #FFDC40;
        width: 100%;
        text-align: center;
        color: black;
        font-size: 24px;
        line-height: 40px;
        cursor: pointer;
    }

    .upload-icon span {
        font-weight: bold;
    }

    /* ini upload foto */

    .upload-area {
        border: 1px solid black;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        cursor: pointer;
        position: relative;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }

    .upload-area img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 5px;
        display: none;
    }

    .upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .upload-label i {
        font-size: 30px;
        margin-top: 20px;
        margin-bottom: 10px;
        color: #000000;
    }

    .upload-area p {
        font-size: 14px;
        color: #000000;
    }

    /* ini input text */
    .input-group-text {
        border: 1px solid black;
        border-radius: 5px 0 0 5px;
    }

    .input-group-text i {
        border-radius: 0px 5px 5px 0;
    }

    .form-control {
        border: 1px solid black;
        color: black;
    }


    .input-group {
        position: relative;
    }

    .input-group .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid black;
    }

    .input-group .input-group-text i {
        color: black;
        padding: 0 5px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: black;
    }
</style>

<div class="container">
    <h3>Kelola Kendaraan</h3>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 d-flex justify-content-end">
                <button class="btn" style="background-color: #ffdc40; font-weight:bold;" onclick="openFormTambah()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-black">
                <div class="card-body">
                    <div class="header-container d-flex justify-content-between mb-3">
                        <div>
                            <span class="ml-2">Tampilkan</span>
                            <form id="paginationForm" method="GET" action="{{ route('pengelola.kelola_kendaraan.index') }}" class="d-inline">
                                <select id="rows" class="custom-select d-inline border-black" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                </select>
                                <span class="ml-2">Baris</span>
                            </form>
                        </div>
                        <div class="search-container">
                            <form method="GET" action="{{ route('pengelola.kelola_kendaraan.search') }}" class="d-flex align-items-center">
                                <input type="text" name="query" class="form-control border-black" placeholder="Pencarian" style="width: auto;" value="{{ request()->get('query') }}">
                                <button class="btn search-btn ml-2" style="background-color: #ffdc40;" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($kendaraan->isEmpty())
                    <p class="mt-3">Tidak ada data yang ditemukan untuk "{{ request()->get('query') }}"</p>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #ffdc40;">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pengguna</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>QR Code</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-putih">
                                @foreach ($kendaraan as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->penggunaParkir->id_pengguna }}</td>
                                    <td>{{ $data->plat_nomor }}</td>
                                    <td>{{ $data->jenis }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="lihatQR('{{ url($data->qr_code) }}')">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <button
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                        data-plat-nomor="{{ $data->plat_nomor }}"
                                        data-jenis="{{ $data->jenis }}"
                                        data-warna="{{ $data->warna }}"
                                        data-foto=" {{ asset($data->foto) }}"
                                        data-id-pengguna="{{ $data->penggunaParkir->id_pengguna ?? '' }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>


                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $data->plat_nomor }}', '{{ $data->penggunaParkir->nama}}')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal untuk QR Code -->
                                <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style=" display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="qrCodeModalLabel">QR Code Kendaraan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal untuk Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center d-flex flex-column align-items-center">
                                                <div class="d-flex justify-content-center align-items-center" style="padding-bottom:10px;">
                                                    <i class="fas fa-question-circle" style="color: red; font-size: 3em; animation: bounce 1s infinite;"></i>
                                                </div>
                                                <p>Apakah Anda yakin ingin menghapus <br>data kendaraan pengguna ini?</p>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <form id="deleteForm" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn" style="background-color: #FFFFFF; color: black; border: 1px solid black; width: 100px;" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn" style="background-color: #FFDC40; color: black; width: 100px;">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Tambah Kendaraan -->
                                <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="tambahModalLabel">Tambah Kendaraan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Begin Form -->
                                                <form method="POST" action="{{ route('pengelola.kelola_kendaraan.store') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 text-center" style="border-right: 1px solid black;">
                                                            <div class="upload-area" onclick="document.getElementById('uploadPhotoVehicle').click()">
                                                                <img id="previewVehicle" src="https://tse1.mm.bing.net/th?id=OIP.Mmwcms1DWRWNLhXw8uEEhgHaFo&pid=Api&P=0&h=180" alt="Preview Foto Kendaraan" style="display:block; max-width:60%; border-radius: 5px;" class="img-fluid mb-3" />
                                                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background-color: #FFDC40; color: black; padding: 10px; border-top: 1px solid black; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; cursor: pointer;">
                                                                    <i class="fas fa-plus-circle fa-2x"></i>
                                                                </div>
                                                                <input type="file" id="uploadPhotoVehicle" name="foto_kendaraan" style="display: none;" accept="image/*" onchange="previewImage(event, 'previewVehicle', 'labelPhotoVehicle')" />
                                                            </div>
                                                            @error('foto_kendaraan')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>

                                                        <!-- Right Column: Input Fields -->
                                                        <div class="col-md-6">
                                                            <!-- ID Pengguna -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                    <select id="id_pengguna" name="id_pengguna" class="form-control @error('id_pengguna') is-invalid @enderror" required>
                                                                        <option value="">Pilih ID Pengguna</option>
                                                                        @foreach($penggunaDropdown as $pengguna)
                                                                        <option value="{{ $pengguna->id_pengguna }}" {{ old('id_pengguna') == $pengguna->id_pengguna ? 'selected' : '' }}>
                                                                            {{ $pengguna->id_pengguna }} - {{ $pengguna->nama }} <!-- Misalnya menampilkan id_pengguna dan nama -->
                                                                        </option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                                @error('id_pengguna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Plat Nomor -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                    <input type="text" name="plat_nomor" class="form-control @error('plat_nomor') is-invalid @enderror" required placeholder="Masukkan Plat Nomor" value="{{ old('plat_nomor') }}">
                                                                </div>
                                                                @error('plat_nomor')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Jenis Kendaraan -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                                    <select id="jenis" name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                                                        <option value="">Pilih Jenis Kendaraan</option>
                                                                        @foreach($jenisArray as $value)
                                                                        <option value="{{ $value }}" {{ old('jenis') == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('jenis')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Warna Kendaraan -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-paint-brush"></i></span>
                                                                    <select id="warna" name="warna" class="form-control @error('warna') is-invalid @enderror" required>
                                                                        <option value="">Pilih Warna Kendaraan</option>
                                                                        @foreach($warnaArray as $value)
                                                                        <option value="{{ $value }}" {{ old('warna') == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('warna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer: Batal and Simpan Buttons -->
                                                    <div class="modal-footer" style="border-top: 1px solid black;">
                                                        <!-- Cancel Button -->
                                                        <button type="button" class="btn" style="background-color: #fff; color: #000; margin-right: 8px; border: 1px solid #000; width: 100px;" data-dismiss="modal">Batal</button>

                                                        <!-- Save Button -->
                                                        <button type="submit" class="btn" style="background-color: #FFDC40; color: black; width: 100px">Simpan</button>
                                                    </div>

                                                </form>
                                                <!-- End Form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Kendaraan -->
                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="editModalLabel">Edit Kendaraan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <!-- Begin Form -->
                                                <form method="POST" id="editForm" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                    <!-- Left Column: Upload Foto Kendaraan -->
                                                    <div class="col-md-6 text-center" style="border-right: 1px solid black;">
                                                        <div class="upload-area" onclick="document.getElementById('uploadPhotoVehicleEdit').click()">
                                                            <img 
                                                                id="previewUserEdit{{ $data->plat_nomor }}"
                                                                src="{{ $data->foto ? asset($data->foto) : asset('images/kendaraan/default.jpg') }}" 
                                                                alt="Preview Foto Kendaraan" 
                                                                style="display:block; max-width:100%; border-radius: 5px;" 
                                                                class="img-fluid mb-3"/>
                                                    
                                                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background-color: #FFDC40; color: black; padding: 10px; border-top: 1px solid black; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; cursor: pointer;">
                                                                <i class="fas fa-plus-circle fa-2x"></i>
                                                            </div>
                                                            
                                                            <input 
                                                                type="file" 
                                                                id="uploadPhotoVehicleEdit" 
                                                                name="foto_kendaraan" 
                                                                style="display: none;" 
                                                                accept="image/*" 
                                                                onchange="previewImage(event, 'previewVehicleEdit')"
                                                            >
                                                        </div>
                                                        @error('foto_kendaraan')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>


                                                        <!-- Right Column: Input Fields -->
                                                        <div class="col-md-6">
                                                            <!-- ID Pengguna (Opsional) -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                    <select id="id_pengguna_edit" name="id_pengguna" class="form-control">
                                                                        <option value="" disabled selected>Pilih ID Pengguna</option> <!-- Placeholder option -->
                                                                        @foreach($penggunaDropdown as $pengguna)
                                                                        <option value="{{ $pengguna->id_pengguna }}"
                                                                            @if($pengguna->id_pengguna == old('id_pengguna', $data->id_pengguna))
                                                                            selected
                                                                            @endif
                                                                            >
                                                                            {{ $pengguna->id_pengguna }} - {{ $pengguna->nama }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                    <!-- Input field for plat_nomor (disabled to prevent editing) -->
                                                                    <input type="text" name="plat_nomor_lama" id="plat_nomor_edit"
                                                                        class="form-control"
                                                                        value="{{ old('plat_nomor', $data->plat_nomor) }}"
                                                                        disabled>
                                                                </div>
                                                            </div>

                                                            <!-- Jenis Kendaraan -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                                    <select id="jenis_edit" name="jenis" class="form-control" required>
                                                                        @foreach($jenisArray as $jenis)
                                                                        <option value="{{ $jenis }}" {{ $data->jenis == $jenis ? 'selected' : '' }}>
                                                                            {{ ucfirst($jenis) }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Warna Kendaraan -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-paint-brush"></i></span>
                                                                    <select id="warna_edit" name="warna" class="form-control" required>
                                                                        @foreach($warnaArray as $warna)
                                                                        <option value="{{ $warna }}" {{ $data->warna == $warna ? 'selected' : '' }}>
                                                                            {{ ucfirst($warna) }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer" style="border-top: 1px solid black;">
                                                        <!-- Cancel Button -->
                                                        <button type="button" class="btn" style="background-color: #fff; color: #000; margin-right: 8px; border: 1px solid #000; width: 100px;" data-dismiss="modal">Batal</button>

                                                        <!-- Save Button -->
                                                        <button type="submit" class="btn" style="background-color: #FFDC40; color: black; width: 100px">Simpan</button>
                                                    </div>
                                                </form>
                                                <!-- End Form -->
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
                            <li class="page-item {{ $kendaraan->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $kendaraan->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($kendaraan->getUrlRange(1, $kendaraan->lastPage()) as $page => $url)
                            <li class="page-item {{ $kendaraan->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $kendaraan->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $kendaraan->nextPageUrl() }}" aria-label="Next">
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
    // Fungsi untuk membuka modal tambah
    function openFormTambah() {
        $('#tambahModal').modal('show');
    }


// Fungsi untuk memuat data ke modal edit
// Fungsi untuk memuat data ke modal edit
$('#editModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Tombol yang memicu modal
    var platNomor = button.data('plat-nomor');
    var jenis = button.data('jenis');
    var warna = button.data('warna');
    var foto = button.data('foto'); // Ambil URL foto yang benar
    var idPengguna = button.data('id-pengguna');

    var modal = $(this);

    modal.find('#plat_nomor_edit').val(platNomor);
    modal.find('#plat_nomor_hidden').val(platNomor);
    modal.find('#jenis_edit').val(jenis);
    modal.find('#warna_edit').val(warna);
    modal.find('#id_pengguna_edit').val(idPengguna);

    // Menampilkan foto kendaraan yang sesuai
    if (foto) {
        modal.find('#foto_kendaraan_edit').attr('src', '/' + foto); // Set foto sesuai dengan path yang benar
        modal.find('#foto_kendaraan_edit').show(); // Menampilkan foto
    } else {
        modal.find('#foto_kendaraan_edit').hide(); // Menyembunyikan foto jika tidak ada
    }

    modal.find('#editForm').attr('action', '/kelola-kendaraan/update/' + platNomor);
});





    // Fungsi untuk menampilkan QR Code modal
    function lihatQR(qrCodeUrl) {
        $('#qrCodeImage').attr('src', qrCodeUrl);
        $('#qrCodeModal').modal('show');
    }

    // Fungsi untuk konfirmasi hapus kendaraan
    function confirmDelete(plat_nomor) {
        var actionUrl = "{{ route('pengelola.kelola_kendaraan.delete', '') }}/" + plat_nomor;
        $('#deleteForm').attr('action', actionUrl);
        $('#deleteModal').modal('show');
    }

    // Preview the user photo on upload
    function previewImage(event, previewId, labelId) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            // Tampilkan gambar pratinjau
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';

            // Ubah label jika ada gambar
            document.getElementById(labelId).style.display = 'none';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    //Rows
    function changeRows() {
        var rows = document.getElementById('rows').value;
        window.location.href = '?rows=' + rows; // Menyertakan parameter rows dalam URL
    }

   
</script>
<!-- jQuery and Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection