@extends('layouts.pengguna')

@section('content')
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Roboto', Arial, sans-serif;
    }

    .profile-title {
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .profile-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .profile-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 class="profile-title black">Profil Anda</h4>
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if ($user) <!-- Cek apakah $user ada -->
    <div class="row" id="viewCard">
        <div class="col-12">
            <div class="card border border-dark">
                <div class="card-header text-center text-black border-bottom border-dark" style="font-weight: 500;">
                    Data Profil
                </div>

                <div class="card-body row">
                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 h-100">
                        <div class="upload-photo text-center p-2">
                            <img id="previewProfil" src="{{asset($user->foto) }}" alt="Preview Foto Profil" class="img-thumbnail">
                             
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="id_pengguna" id="id_pengguna_view" value="{{ $user->id_pengguna }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>
                        <!-- Kategori -->
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-list"></i></span>
                                <input type="text" name="kategori" id="kategori_view" value="{{ $user->kategori }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-user"></i></span>
                                <input type="text" name="nama" id="nama_view" value="{{ $user->nama }}" class="form-control border border-dark text-black" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text border border-dark text-black"><i class="fas fa-envelope"></i></span>
                                <input type="text" name="email" id="email_view" value="{{ $user->email }}" class="form-control border border-dark text-black" readonly>
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

    <div id="editCard" class="row" style="display:none;">
        <div class="col-12">
            <div class="card border border-dark">
                <div class="card-header text-center text-black border-bottom border-dark" style="font-weight: 500;">
                    Ubah Data Profil
                </div>

                <div class="card-body">
                    <form action="{{ route('pengguna.profile.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 h-100">
                                <div class="upload-photo text-center p-2" onclick="document.getElementById('uploadPhotoProfilEdit').click()">
                                    <img id="previewProfilEdit" src="{{ asset ($user->foto) }}" alt="Preview Foto Profil" class="img-thumbnail">
                                    <input type="file" id="uploadPhotoProfilEdit" name="foto" style="display:none;" accept="image/*" onchange="previewImage(event, 'previewProfilEdit')">
                                </div>
                            </div>

                            <div class="col-12 col-md-8">

                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text border border-dark text-black"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nama" value="{{ $user->nama }}" class="form-control border border-dark text-black" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text border border-dark text-black"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" value="{{ $user->email }}" class="form-control border border-dark text-black" required>
                                    </div>
                                </div>

                                <!-- Ganti Password Jika Ingin -->
                                <div class="form-group mb-3">
                                    <label for="password">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text border border-dark text-black">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" id="password" name="password" class="form-control border border-dark text-black" placeholder="Masukkan Password Baru" aria-describedby="passwordHelp" onkeyup="validatePassword()">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                            <i id="password_icon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small id="passwordHelp" class="form-text text-muted">Masukkan password baru Anda jika ingin mengganti.</small>
                                    <div class="progress mt-2">
                                        <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100">Kekuatan: </div>
                                    </div>
                                    <small id="passwordError" class="form-text text-danger" style="display: none;">Password tidak sesuai!</small>
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
    <p>Data profil tidak tersedia.</p>
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

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const passwordIcon = document.getElementById("password_icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove("fa-eye");
            passwordIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            passwordIcon.classList.remove("fa-eye-slash");
            passwordIcon.classList.add("fa-eye");
        }
    }
    // validasi
    function validatePassword() {
        const passwordInput = document.getElementById("password");
        const passwordError = document.getElementById("passwordError");
        const passwordStrengthBar = document.getElementById("passwordStrengthBar");
        const password = passwordInput.value;

        let strength = 0; // 0 - 100
        let strengthText = "Kekuatan: ";
        let strengthClass = "";

        if (password.length >= 8) {
            strength += 25; // Minimal 8 karakter
        }
        if (/[A-Z]/.test(password)) {
            strength += 25; // Minimal 1 huruf kapital
        }
        if (/[0-9]/.test(password)) {
            strength += 25; // Minimal 1 angka
        }
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 25; // Minimal 1 karakter khusus
        }

        // Update progress bar
        passwordStrengthBar.style.width = strength + "%";

        if (strength === 0) {
            strengthText += "Sangat Lemah";
            strengthClass = "bg-danger"; // Merah
            passwordError.style.display = "none";
        } else if (strength < 50) {
            strengthText += "Lemah";
            strengthClass = "bg-danger"; // Merah
            passwordError.style.display = "block";
        } else if (strength < 75) {
            strengthText += "Sedang";
            strengthClass = "bg-warning"; // Kuning
            passwordError.style.display = "none";
        } else {
            strengthText += "Kuat";
            strengthClass = "bg-success"; // Hijau
            passwordError.style.display = "none";
        }

        passwordStrengthBar.className = "progress-bar " + strengthClass;
        passwordStrengthBar.textContent = strengthText;
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