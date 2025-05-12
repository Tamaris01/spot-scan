@extends('layouts.pengguna')

@section('content')
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Roboto', Arial, sans-serif;
    }

    .kendaraan-title {
        /* Default font size for desktop */
        font-weight: bold;

        .dropdown-icon {
            pointer-events: none;
            /* Ensures the icon doesn’t interfere with clicks */
            color: #333;
            /* Adjust icon color */
        }

    }

    .dropdown-icon {

        /* Ensures the icon doesn’t interfere with clicks */
        color: #333;
        /* Adjust icon color */
    }

    @media (max-width: 768px) {

        /* Adjust font size for tablets and smaller devices */
        .kendaraan-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {

        /* Adjust font size for mobile screens */
        .kendaraan-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 class="kendaraan-title black">Data Kendaraaan Anda</h4>

    </div>
    <!-- Display success or error messages -->
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($kendaraan)
    <div class="row" id="viewCard">
        <div class="col-12">
            <div class="card border border-dark">
                <div class="card-header text-center text-black border-bottom border-dark" style="font-weight: 500;">
                    Data Kendaraan
                </div>


                <div class="card-body row">
                    <!-- Left section: Image display -->
                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 h-100">
                        <div class="upload-photo text-center p-2">
                            <img id="previewKendaraan" src="{{ asset($kendaraan->foto) }}" alt="Preview Foto Kendaraan" class="img-thumbnail">
                        </div>
                    </div>

                    <!-- Right section: Form fields (read-only) -->
                    <div class="col-12 col-md-8">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="plat_nomor" id="plat_nomor_view" value="{{ $kendaraan->plat_nomor }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-list"></i></span>
                                <input type="text" name="jenis" id="jenis_view" value="{{ $kendaraan->jenis }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-paint-brush"></i></span>
                                <input type="text" name="warna" id="warna_view" value="{{ $kendaraan->warna }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn" style="background-color: #FFDC40; color: #000;" onclick="showEditForm()">
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit form card (hidden initially) -->
    <div id="editCard" class="row" style="display:none;">
        <div class="col-12">
            <div class="card border border-dark">
                <div class="card-header text-center text-black border-bottom border-dark" style="font-weight: 500;">
                    Ubah Data Kendaraan
                </div>

                <div class="card-body">
                    <form action="{{ route('pengguna.kendaraan.update', ['plat_nomor' => $kendaraan->plat_nomor]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Image upload section -->
                            <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 h-100">
                                <div class="upload-photo text-center p-2" onclick="document.getElementById('uploadPhotoKendaraanEdit').click()">
                                    <img id="previewKendaraanEdit" src="{{asset($kendaraan->foto) }}" alt="Preview Foto Kendaraan" class="img-thumbnail">
                                    <input type="file" id="uploadPhotoKendaraanEdit" name="foto_kendaraan" style="display:none;" accept="image/*" onchange="previewImage(event, 'previewKendaraanEdit')">
                                </div>
                            </div>

                            <!-- Form fields for editing -->
                            <div class="col-12 col-md-8">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text border border-dark text-black"><i class="fas fa-id-card"></i></span>
                                        <input type="text" name="plat_nomor" value="{{ $kendaraan->plat_nomor }}" class="form-control border border-dark text-black" readonly>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group position-relative">
                                        <span class="input-group-text border border-dark text-black">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <select name="jenis" class="form-select border border-dark text-black" required>
                                            <option value="">Pilih Jenis Kendaraan</option>
                                            @foreach($jenisKendaraanArray as $jenis)
                                            <option value="{{ $jenis }}" {{ $kendaraan->jenis == $jenis ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                            @endforeach
                                            <span class="dropdown-icon position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events: none;">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group position-relative">
                                        <span class="input-group-text border border-dark text-black">
                                            <i class="fas fa-paint-brush"></i>
                                        </span>
                                        <select name="warna" class="form-select border border-dark text-black" required>
                                            <option value="">Pilih Warna Kendaraan</option>
                                            @foreach($warnaKendaraanArray as $warna)
                                            <option value="{{ $warna }}" {{ $kendaraan->warna == $warna ? 'selected' : '' }}>{{ $warna }}</option>
                                            @endforeach
                                            <span class="dropdown-icon position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events: none;">
                                                <i class=" fas fa-chevron-down"></i>
                                            </span>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn" style="background-color: #fff; color: #000; margin-right: 8px; border: 1px solid #000;" onclick="cancelEditForm()">Batal</button>
                                    <button type="submit" class="btn" style="background-color: #FFDC40; color: #000;">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <p>Belum ada kendaraan yang terdaftar.</p>
    @endif
</div>

@endsection

@section('scripts')
<script>
    function showEditForm() {
        document.getElementById('viewCard').style.display = 'none';
        document.getElementById('editCard').style.display = 'block';
    }

    function cancelEditForm() {
        document.getElementById('editCard').style.display = 'none';
        document.getElementById('viewCard').style.display = 'block';
    }

    function previewImage(event, previewId) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById(previewId).src = URL.createObjectURL(file);
        }
    }
</script>

<!-- Pastikan Anda menyertakan Bootstrap 5 CSS dan JS di halaman Anda -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endsection

<style>
    .card-header {
        color: black;
    }

    .upload-photo {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        border: 1px dashed grey;
        border-radius: 2px;
    }

    .upload-photo img {
        max-height: 100%;
        width: auto;
    }

    .input-group-text {
        border: 1px solid black;
        border-radius: 5px 0 0 5px;
        /* Radius hanya di kiri atas dan kiri bawah */
    }

    .input-group-text i {
        border-radius: 0px 5px 5px 0;
    }

    .form-control {
        border: 1px solid black;
        color: black;
    }

    .card {
        border: 1px solid black;
    }

    .card-header {
        border-bottom: 1px solid black;
        /* Border bawah hitam */
    }

    .input-group {
        position: relative;
        /* Untuk penempatan posisi yang lebih baik */
    }

    .input-group .input-group-text {
        background-color: #f8f9fa;
        /* Warna latar belakang yang lebih lembut */
        border: 1px solid black;
        /* Border tetap hitam */
    }

    .input-group .input-group-text i {
        color: black;
        /* Warna ikon */
        padding: 0 5px;
        /* Tambahkan padding agar tidak terlalu dekat dengan tepi */
    }

    .form-control {
        border-radius: 0;
        /* Menghilangkan border radius untuk kesan lebih bersih */
    }

    .form-control:focus {
        box-shadow: none;
        /* Menghilangkan bayangan saat fokus */
        border-color: black;
        /* Memastikan border tetap hitam saat fokus */
    }
</style>