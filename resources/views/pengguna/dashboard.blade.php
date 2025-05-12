@extends('layouts.pengguna')

@section('content')

<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Roboto', Arial, sans-serif;
    }


    .card {
        margin-bottom: 10px;
        border: 1px solid black;
        background-color: white;
        text-align: justify;
        overflow: hidden;
        border: 1px solid black;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* padding: 10px; */
        border: 1px solid black;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        text-align: center;
        background-color: white;
    }

    .card-qr {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px;
        text-align: center;
        background-color: white;
        padding: 5px;
    }


    .icon {
        font-size: 2rem;
        color: black;
        margin-bottom: 10px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: black;
        margin-bottom: 5px;
    }

    .card-text {
        font-size: 1rem;
        color: black;
    }

    .highlight {
        background-color: #FFDC40;
        padding: 10px;
        border: 1px solid black;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    /* User Info styling */
    .user-card {
        max-width: 100%;
        margin: auto;
    }

    .user-info img {
        width: 100%;
        height: auto;
        max-width: 200px;
    }

    .details table {
        width: 100%;
    }

    .details th,
    .details td {
        padding: 6px;
        word-break: break-word;
    }

    /* Responsive layout for desktop */
    @media (min-width: 992px) {

        .user-info,
        .details {
            display: inline-block;
            vertical-align: top;
        }

        .user-info {
            width: 40%;
            padding-right: 20px;

        }

        .details {
            width: 65%;
            padding-left: 10px;
            text-align: left;
        }
    }

    /* QR Code and Foto Responsiveness */
    .user-info img,
    .qr-code {
        width: 50%;
        height: auto;
    }

    /* Desktop adjustments */
    .qr-code {
        max-width: 25%;
    }

    .user-info img {
        max-width: 50%;
    }

    /* Adjusting the download button */
    .btn-download {
        margin-top: 5px;
        margin-bottom: 10px;
        padding: 5px 20px;
        font-size: 1rem;
        color: black;
        background-color: #FFDC40;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;

    }

    h6.text-center {
        color: black;
        border-bottom: 1px solid black;
        padding-bottom: 10px;
        display: inline-block;
        width: 100%;
        text-align: center;
    }

    .greeting-message {
        color: #FFDC40;
        /* Text color */
        background-color: black;
        /* Background color */
        padding: 8px;
        /* Add some padding */
        border-radius: 5px;
        /* Rounded corners */
        display: flex;
        /* Aligns icon and text */
        align-items: center;
        /* Vertically center icon with text */
    }

    .greeting-message i {
        margin-left: 5px;
        /* Space between text and icon */
    }



    /* hp */
    .date-display {
        margin-left: auto;
        /* Pushes the date to the far right */
        text-align: right;
        /* Ensures the text is aligned to the right */
    }



    /* Mobile adjustments */
    @media (max-width: 768px) {

        .qr-code,
        .user-info img {
            max-width: 40%;
        }
    }

    .card-title {
        font-size: 1rem;
    }

    .card-text {
        font-size: 0.9rem;
    }

    .details th,
    .details td {
        font-size: 0.8rem;
    }

    .qr-code {
        max-width: 23%;
    }

    .user-info img {
        max-width: 50%;
    }

    /* Media query for mobile screens */
    .dashboard-title {
        font-size: 1.5rem;
        /* Default font size for larger screens */
        font-weight: bold;
        /* Makes the title bold */
        margin: 0;
        /* Remove default margin */
    }

    .date-display {
        font-size: 1rem;
        /* Default font size for date */
        margin-left: 15px;
        /* Space between title and date */
        color: #6c757d;
        /* Lighter color for the date text */
    }

    .greeting-message {
        font-size: 1.2rem;
        /* Greeting font size */
        margin-top: 15px;
        /* Space above the greeting */
    }

    /* Media query for mobile screens */
    @media (max-width: 576px) {

        /* Bootstrap's small screen breakpoint */
        .dashboard-title {
            font-size: 1.25rem;
            /* Adjust font size for mobile */
        }

        .date-display {
            font-size: 0.9rem;
            /* Smaller font size for the date on mobile */
        }

        .greeting-message {
            font-size: 1rem;
            /* Smaller font size for greeting on mobile */
        }

        .d-flex {
            flex-direction: row;
            /* Ensure items are in a row */
            align-items: center;
            /* Align items vertically centered */
        }

        .qr-code {
            max-width: 20%;
        }

    }
</style>


<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 class="dashboard-title black">Dashboard</h4>
        <p class="date-display mb-0">{{ $date }}</p>
    </div>
    <p class="greeting-message">
        Hallo, {{ Auth::user()->nama }} <i class="fas fa-smile"></i>
    </p>

</div>

<div class="row mx-0">
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card-body">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h6 class="card-title">{{ $jumlahPengguna }}</h6>
            <p class="card-text">Jumlah Pengguna</p>
        </div>
        <div class="highlight"></div>
    </div>

    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card-body">
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
            <h6 class="card-title">{{ $jumlahParkirMasuk }}</h6>
            <p class="card-text">Jumlah Parkir Masuk</p>
        </div>
        <div class="highlight"></div>
    </div>

    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card-body">
            <div class="icon">
                <i class="fas fa-car-side"></i>
            </div>
            <h6 class="card-title">{{ $jumlahParkirKeluar }}</h6>
            <p class="card-text">Jumlah Parkir Keluar</p>
        </div>
        <div class="highlight"></div>
    </div>
</div>

<div class="row mx-0">
    <!-- User Info Card -->
    <div class="col-lg-8 col-md-12 mb-3">
        <div class="card user-card">
            <h6 class="text-center mt-3">Data Diri Pengguna</h6>
            <div class="d-flex flex-column flex-lg-row align-items-center">
                <div class="user-info text-center mb-3">
                   <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Pengguna" class="rounded img-fluid">

                </div>
                <div class="details">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>NID</th>
                                <td>:</td>
                                <td>{{ $penggunaDetail->id_pengguna }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>:</td>
                                <td>{{ $penggunaDetail->nama }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>:</td>
                                <td>{{ $penggunaDetail->kategori }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>:</td>
                                <td>{{ $penggunaDetail->email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Card -->
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card user-card text-center">
            <h6 class="text-center mt-3">QR Code</h6>
            <div class="card-qr">
                @if ($qrCodePath)
                <img alt="QR Code" src="{{ asset($qrCodePath) }}" class="img-fluid qr-code mb-3" />
                <a href="{{ asset($qrCodePath) }}" download="QR_Code_{{ Auth::user()->nama }}" class="btn-download">
                    Unduh <i class="fas fa-download"></i>
                </a>
                @else
                <span>Tidak ada QR Code tersedia.</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Success Message -->
@if (session('status') === 'success' && session('message'))
<div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Center the modal -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #FFDC40;">
                <h5 class="modal-title" id="successModalLabel">Berhasil Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div>
                    <i class="fas fa-check-circle" style="color: green; font-size: 3em; animation: bounce 1s infinite; padding-bottom:10px;"></i>
                </div>
                <p>Selamat datang, Anda berhasil masuk!<br>"{{ Auth::user()->nama }}"</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn" style="background-color: #FFDC40; color: black; width: 100px;" data-dismiss="modal">Oke</button>

            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        console.log('Page loaded');
        $('#successModal').modal('show'); // Temporarily show the modal to see if it triggers without conditions
    });
</script>
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
@endsection