@extends('layouts.pengelola')
@section('title', 'Kelola Pengguna Parkir')

@section('content')
<style>
    .border-black {
        border-color: black;
    }



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
    <h3>Kelola Pengguna</h3>
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-end">
                <button class="btn" style="background-color: #ffdc40; font-weight:bold;" onclick="openAddForm()">
                    <i class="fas fa-plus-circle"></i> Tambah
                </button>
            </div>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <div class="card border-black">
                <!-- Table untuk kelola pengguna parkir -->
                <div class="card-body">
                    <div class="header-container d-flex justify-content-between mb-3">
                        <div>
                            <span class="ml-2">Tampilkan</span>
                            <form method="GET" action="{{ route('pengelola.kelola_pengguna.index') }}" style="display: inline;">
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
                            <form method="GET" action="{{ route('pengelola.kelola_pengguna.search') }}" class="d-flex align-items-center">
                                <input type="text" name="query" class="form-control border-black" placeholder="Pencarian" style="width: auto;" value="{{ request()->get('query') }}">
                                <button class="btn search-btn ml-2" style="background-color: #ffdc40;" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($pengguna->isEmpty())
                    <p class="mt-3">Tidak ada data yang ditemukan untuk "{{ request()->get('query') }}"</p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #ffdc40;">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pengguna</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-putih">
                                @foreach ($pengguna as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->id_pengguna }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <!-- Button Lihat -->
                                        <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#imageModal-{{ $user->id_pengguna }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    <td class="text-center">
                                        <!-- Button Edit -->
                                        <button
                                            type="button"
                                            class="btn btn-info btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalEdit{{ $user->id_pengguna }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <!-- Button Hapus -->
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelete-{{ $user->id_pengguna }}">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal Untuk Hapus Pengguna -->
                                <div class="modal fade" id="modalDelete-{{ $user->id_pengguna }}" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="modalDeleteLabel">Hapus Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center d-flex flex-column align-items-center">
                                                <div class="d-flex justify-content-center align-items-center" style="padding-bottom:10px;">
                                                    <i class="fas fa-question-circle" style="color: red; font-size: 3em; animation: bounce 1s infinite;"></i>
                                                </div>
                                                <p>Anda yakin ingin menghapus data pengguna <br> "{{ $user->nama }}" ?</p>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <form method="POST" action="{{ route('pengelola.kelola_pengguna.destroy', $user->id_pengguna) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn" style="background-color: #FFFFFF; color: black; border: 1px solid black; width: 100px;" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn" style="background-color: #FFDC40; color: black; width: 100px;">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal untuk lihat Pengguna -->
                                <div class="modal fade" id="imageModal-{{ $user->id_pengguna }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $user->id_pengguna }}" aria-hidden="true">
                                    <div class="modal-dialog" style=" display: flex; justify-content: center; align-items: center; text-align: center;" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="imageModalLabel-{{ $user->id_pengguna }}">Foto Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($user->foto) }}" alt="User Image" class="img-fluid" style="height: 50%; width: 50%;">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal untuk Tambah pengguna -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="modalAddLabel">Tambah Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Begin Form -->
                                                <form method="POST" action="{{ route('pengelola.kelola_pengguna.store') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <!-- Left Column: Upload Foto Pengguna -->
                                                        <div class="col-md-6 text-center">
                                                            <div class="upload-area" onclick="document.getElementById('uploadPhotoUser').click()" style="position: relative;">
                                                                <img id="previewUser" src="https://tse3.mm.bing.net/th?id=OIP.20nJoIhmY9GkmvS8SolP4wHaHa&pid=Api&P=0&h=180" alt="Preview Foto Pengguna" class="img-fluid mb-3" style="display:block; max-width:100%; border-radius: 5px;" />
                                                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background-color: #FFDC40; color: black; padding: 10px; border-top: 1px solid black; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; cursor: pointer;">
                                                                    <i class="fas fa-plus-circle fa-2x"></i>
                                                                </div>
                                                                <input type="file" id="uploadPhotoUser" name="foto" style="display: none;" accept="image/*" onchange="previewImage(event, 'previewUser')" />
                                                            </div>
                                                            @error('foto')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Right Column: Input Fields -->
                                                        <div class="col-md-6">
                                                            <!-- Kategori Akun -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                                    <select id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                                                        <option value="">Pilih Kategori</option>
                                                                        @foreach($kategoriArray as $value)
                                                                        <option value="{{ $value }}" {{ old('kategori') == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('kategori')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- ID Pengguna -->
                                                            <div class="form-group" id="id_pengguna_group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                    <input type="text" id="id_pengguna" name="id_pengguna" class="form-control @error('id_pengguna') is-invalid @enderror" placeholder="Masukkan ID Pengguna" value="{{ old('id_pengguna') }}">
                                                                </div>
                                                                @error('id_pengguna')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Nama -->
                                                            <div class="form-group mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required placeholder="Masukkan Nama" value="{{ old('nama') }}">
                                                                </div>
                                                                @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Masukkan Email" value="{{ old('email') }}">
                                                                </div>
                                                                @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Password -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                                    <input type="password" id="addPasswordInput" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Masukkan Kata Sandi" value="{{ old('password') }}">
                                                                    <div class="input-group-append" style="border: 1px solid black; box-sizing: border-box; border-radius: 0 3px 3px 0;">
                                                                        <span class="input-group-text border border-black cursor-pointer" id="addToggleIcon" onclick="toggleAddPasswordVisibility()">
                                                                            <i id="addTogglePasswordIcon" class="fas fa-eye"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer: Batal and Simpan Buttons -->
                                                    <div class="modal-footer" style="border-top: 1px solid black;">
                                                        <button type="button" class="btn" style="background-color: #fff; color: #000; margin-right: 8px; border: 1px solid #000; width: 100px;" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn" style="background-color: #FFDC40; color: black; width: 100px">Simpan</button>
                                                    </div>
                                                </form>
                                                <!-- End Form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal untuk Edit Pengguna -->
                                <div class="modal fade" id="modalEdit{{ $user->id_pengguna }}" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel{{ $user->id_pengguna }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFDC40;">
                                                <h5 class="modal-title" id="modalEditLabel{{ $user->id_pengguna }}">Edit Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Begin Form -->
                                                <form method="POST" action="{{ route('pengelola.kelola_pengguna.update', ['id_pengguna' => $user->id_pengguna]) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                      <!-- Left Column: Upload Foto Pengguna -->
                                                        <div class="col-md-6 text-center">
                                                            <div class="upload-area" onclick="document.getElementById('uploadPhotoUserEdit{{ $user->id_pengguna }}').click()" style="position: relative;">
                                                                <!-- Menampilkan gambar baru atau gambar lama jika tidak ada perubahan -->
                                                                <img id="previewUserEdit{{ $user->id_pengguna }}"
                                                                     src="{{ $user->foto ? asset($user->foto) : asset('images/profil/default.jpg') }}"
                                                                     alt="Preview Foto Pengguna"
                                                                     class="img-fluid mb-3"
                                                                     style="display:block; max-width:100%; border-radius: 5px;" />
                                                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background-color: #FFDC40; color: black; padding: 10px; border-top: 1px solid black; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; cursor: pointer;">
                                                                    <i class="fas fa-plus-circle fa-2x"></i>
                                                                </div>
                                                                <input type="file" id="uploadPhotoUserEdit{{ $user->id_pengguna }}" name="foto" style="display: none;" accept="image/*" onchange="previewImage(event, 'previewUserEdit{{ $user->id_pengguna }}')" />
                                                            </div>
                                                            @error('foto')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>


                                                        <!-- Right Column: Input Fields -->
                                                        <div class="col-md-6">
                                                            <!-- Kategori Akun -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                                    <select id="kategori{{ $user->id_pengguna }}" name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                                                        <option value="">Pilih Kategori</option>
                                                                        @foreach($kategoriArray as $value)
                                                                        <option value="{{ $value }}" {{ old('kategori', $user->kategori) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('kategori')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- ID Pengguna -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                    <input type="text" id="id_pengguna{{ $user->id_pengguna }}" name="id_pengguna" class="form-control @error('id_pengguna') is-invalid @enderror" placeholder="Masukkan ID Pengguna" required value="{{ old('id_pengguna', $user->id_pengguna) }}" disabled>
                                                                </div>
                                                                @error('id_pengguna')
                                                                @enderror
                                                            </div>

                                                            <!-- Nama -->
                                                            <div class="form-group mb-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required placeholder="Masukkan Nama" value="{{ old('nama', $user->nama) }}">
                                                                </div>
                                                                @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Masukkan Email" value="{{ old('email', $user->email) }}">
                                                                </div>
                                                                @error('email')
                                                                @enderror
                                                            </div>

                                                            <!-- Password -->
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                                    <input type="password" id="passwordInput{{ $user->id_pengguna }}" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Kata Sandi">
                                                                    <div class="input-group-append" style="border: 1px solid black; box-sizing: border-box; border-radius: 0 3px 3px 0;">
                                                                        <span class="input-group-text border border-black cursor-pointer" id="toggleIcon{{ $user->id_pengguna }}" onclick="togglePasswordVisibility('{{ $user->id_pengguna }}')">
                                                                            <i id="icon{{ $user->id_pengguna }}" class="fas fa-eye"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer: Batal and Simpan Buttons -->
                                                    <div class="modal-footer" style="border-top: 1px solid black;">
                                                        <button type="button" class="btn" style="background-color: #fff; color: #000; margin-right: 8px; border: 1px solid #000; width: 100px;" data-dismiss="modal">Batal</button>
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
                            <li class="page-item {{ $pengguna->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pengguna->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($pengguna->getUrlRange(1,$pengguna->lastPage()) as $page => $url)
                            <li class="page-item {{ $pengguna->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{$pengguna->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $pengguna->nextPageUrl() }}" aria-label="Next">
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
    document.addEventListener("DOMContentLoaded", function() {
        const kategoriSelect = document.getElementById("kategori");
        const idPenggunaGroup = document.getElementById("id_pengguna_group");

        // Fungsi untuk mengatur visibilitas ID Pengguna
        function toggleIdPengguna() {
            if (kategoriSelect.value === "Tamu") {
                idPenggunaGroup.style.display = "none";
            } else {
                idPenggunaGroup.style.display = "block";
            }
        }

        // Jalankan fungsi saat halaman dimuat pertama kali
        toggleIdPengguna();

        // Tambahkan event listener untuk perubahan pada select kategori
        kategoriSelect.addEventListener("change", toggleIdPengguna);
    });

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

    // Fungsi untuk mengganti visibilitas password pada edit 
    function togglePasswordVisibility(userId) {
        var passwordInput = document.getElementById('passwordInput' + userId);
        var toggleIcon = document.getElementById('icon' + userId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
    // Fungsi untuk mengganti visibilitas password pada Add Password
    function toggleAddPasswordVisibility() {
        var passwordInput = document.getElementById('addPasswordInput');
        var toggleIcon = document.getElementById('addTogglePasswordIcon');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }


    // Membuka Modal Tambah Pengguna
    function openAddForm() {
        // Pastikan modal Add selalu terbuka
        $('#modalAdd').modal('show');
    }



    $(document).ready(function() {
        console.log('Page loaded');
        $('#successModal').modal('show'); // Temporarily show the modal to see if it triggers without conditions
    });
</script>

<!-- jQuery dan Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection