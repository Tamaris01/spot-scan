<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        @guest
        @if (Route::has('login'))
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars" style="color: black;"></i> <!-- Ikon menu tiga garis hitam -->
            </a>
        </li>
        @endif
        @else
        <li class="nav-item">
            <a data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars" style="color: black;"></i> <!-- Ikon menu tiga garis hitam -->
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
             <img src="{{ asset(Auth::user()->foto) }}" alt="" class="rounded-circle" height="30" width="30">

                {{ Auth::user()->nama }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <!-- Pengecekan tipe pengguna dan menyesuaikan rute profil -->
                @if (Auth::guard('pengelola')->check())
                <a class="dropdown-item" href="{{ route('pengelola.profile.show') }}">
                    <i class="fas fa-user"></i> {{ __('Profil') }}
                </a>
                @elseif (Auth::guard('pengguna')->check())
                <a class="dropdown-item" href="{{ route('pengguna.profile.show') }}">
                    <i class="fas fa-user"></i> {{ __('Profil') }}
                </a>
                @endif
                <a class="dropdown-item lo" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        @endguest
    </ul>
</nav>
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Center the modal -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #FFDC40;">
                <h5 class="modal-title text-dark" id="logoutModalLabel">Konfirmasi Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black; font-size: 1.75em; font-weight: bold;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Tanda tanya di atas tulisan -->
                <div>
                    <i class="fas fa-question-circle" style="color: red; font-size: 3em; animation: bounce 1s infinite; padding-bottom:10px;"></i>
                </div>
                <p>Anda yakin ingin keluar dari sistem ini?<br>"{{ Auth::user()->nama }}"</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-light border-dark" style="margin-right: 10px; width: 100px;" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn" style="background-color: #FFDC40; color: black; margin-right: 10px; width: 100px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Ya</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS untuk navbar */
    .nav-link {
        color: black !important;
        /* Warna teks navbar hitam */
    }

    .nav-link:hover {
        color: black !important;
        /* Tetap hitam saat hover */
    }

    .dropdown-item {
        color: black !important;
        /* Warna teks dropdown hitam */
    }

    .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.1);
        /* Efek hover untuk dropdown */
        color: black !important;
        /* Tetap hitam saat hover di dropdown */
    }

    /* Responsif: menyembunyikan sidebar dan menampilkan tombol */
    @media (max-width: 768px) {
        .main-header .navbar-nav .nav-item .nav-link {
            color: black !important;
            /* Warna hitam untuk ikon saat kecil */
        }
    }
</style>
<!-- Pastikan jQuery sudah di-load sebelum Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>