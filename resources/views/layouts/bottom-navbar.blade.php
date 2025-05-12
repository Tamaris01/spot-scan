<nav class="navbar fixed-bottom navbar-light border-top">
    <div class="d-flex justify-content-around w-100">
        <!-- Menu untuk Pengguna Parkir -->
        <a href="{{ route('pengguna.dashboard') }}" class="nav-link text-center @if (Route::currentRouteName() == 'pengguna.dashboard') active @endif">
            <i class="fas fa-home nav-icon"></i><br><small>Dashboard</small>
        </a>
        <a href="{{ route('pengguna.kendaraan') }}" class="nav-link text-center @if (Route::currentRouteName() == 'pengguna.kendaraan') active @endif">
            <i class="fas fa-car nav-icon"></i><br><small>Kendaraan</small>
        </a>
        <a href="{{ route('pengguna.riwayat_parkir') }}" class="nav-link text-center @if (Route::currentRouteName() == 'pengguna.riwayat_parkir') active @endif">
            <i class="fas fa-history nav-icon"></i><br><small>Riwayat</small>
        </a>
        <a href="{{ route('pengguna.profile.show') }}" class="nav-link text-center @if (Route::currentRouteName() == 'pengguna.profile.show') active @endif">
            <i class="fas fa-user nav-icon"></i><br><small>Profile</small>
        </a>
    </div>
</nav>


<style>
    /* Basic navbar styling */
    .navbar.fixed-bottom {
        background-color: #FFDC40;
        border-top: 2px solid black;
        width: 100%;
        z-index: 1030;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
        /* Add shadow for depth */
    }

    /* Icon and text styling */
    .nav-icon {
        font-size: 1.5rem;
        color: black;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .nav-link small {
        font-size: 0.75rem;
        color: black;
        display: block;
        transition: color 0.2s ease;
    }

    /* Hover and active effects */
    .nav-link:hover .nav-icon,
    .nav-link.active .nav-icon {
        color: whitesmoke;
        transform: translateY(-6px);
    }

    .nav-link:hover small,
    .nav-link.active small {
        color: whitesmoke;
    }

    /* Animation for the active link underline */
    .nav-link {
        position: relative;

    }

    .nav-link::after {
        content: "";
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: -4px;
        width: 0;
        height: 4px;
        background-color: whitesmoke;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .nav-link.active::after,
    .nav-link:hover::after {
        width: 30%;
    }

    /* Disable zoom and hide horizontal overflow */
    body {
        touch-action: manipulation;
        overflow-x: hidden;
    }

    /* Adjust padding to avoid overlap with fixed navbar */
    .content-wrapper {
        padding-bottom: 70px;
        height: 100vh;
        overflow-y: auto;
    }
</style>