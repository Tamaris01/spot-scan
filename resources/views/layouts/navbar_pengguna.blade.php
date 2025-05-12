<!-- resources/views/layouts/navbar_pengguna.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: white; z-index: 1030; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
    <div class="d-flex justify-content-between align-items-center w-100" style="padding: 0 15px;">
        <!-- Logo SPOT -->
        <a href="{{ route('pengguna.dashboard') }}" class="navbar-brand d-flex align-items-center" style="padding: 0;">
            <img src="{{ asset('images/spot-logo.png') }}" alt="SPOT Logo" style="width: 100px; height: 40px; object-fit: contain;">
        </a>

        <!-- Logout Icon -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-dark" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt" style="font-size: 1.5em;"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Form Logout (disembunyikan) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

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

<!-- Script untuk Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-4V5ABiI/ZKPITJ4BNfl9jxGoF5G+1eP7PY0hVoFSQwl9uWEd5tb8yH4uj3S1bp7R" crossorigin="anonymous"></script>

<style>
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