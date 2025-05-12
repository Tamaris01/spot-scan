<aside class="main-sidebar sidebar-yellow sidebar-white elevation-4">
    <a href="#" class="brand-link" style="display: flex; justify-content: center; align-items: center; padding: 12px; background-color: white;">
        <img src="{{ asset('images/spot-logo.png') }}" alt="SPOT Logo" class="brand-image"
            style="width: 100%; max-width: 150px; height: auto; object-fit: contain" />
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @auth('pengelola')
                <!-- Menu untuk Pengelola Parkir -->
                <li class="nav-item">
                    <a href="{{ route('pengelola.dashboard') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.dashboard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengelola.konfirmasi_pendaftaran') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.konfirmasi_pendaftaran') active @endif">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>Konfirmasi Pendaftaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengelola.kelola_pengguna.index') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.kelola_pengguna.index') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Kelola Pengguna</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengelola.kelola_kendaraan.index') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.kelola_kendaraan.index') active @endif">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Kelola Kendaraan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengelola.monitoring.index') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.monitoring.index') active @endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Monitoring</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengelola.laporan_parkir.index') }}" class="nav-link @if (Route::currentRouteName() == 'pengelola.laporan_parkir.index') active @endif">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Parkir</p>
                    </a>
                </li>
                @endauth
            </ul>
        </nav>
    </div>
</aside>

<style>
    /* CSS untuk mengubah tampilan sidebar */
    .nav-link.active {
        background-color: white !important;
        /* Kotak aktif putih */
        color: black !important;
        /* Teks aktif hitam */
    }

    .nav-link {
        background-color: while;
        color: while;
        /* transform: scale(1); */
        /* Teks link hitam */
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        /* Kotak aktif putih */
        color: white !important;
        transform: scale(1.05);
    }


    .nav-icon {
        color: black;
        /* Ikon hitam */
    }

    .nav-link.active .nav-icon {
        color: black;
        /* Ikon aktif tetap hitam */
    }

    .nav-link:hover .nav-icon {
        color: white;
        /* Ikon tetap hitam saat hover */
    }
</style>