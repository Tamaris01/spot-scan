<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPOT Login</title>
    <!-- Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- Tambahkan CSS Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: "Roboto", sans-serif;
            background-image: url('images/daftar.png'), url('images/mobil.png');
            background-size: 20%, 20%;
            background-repeat: no-repeat, no-repeat;
            background-position: right 20px bottom 20px, left 20px bottom 20px;
            background-attachment: fixed, fixed;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            max-width: 600px;
            flex-direction: column;
            padding: 20px;
        }

        @media (max-width: 768px) {
            body {
                background-size: 30%, 30%;
                background-position: right 10px bottom 10px, left 10px bottom 10px;
            }
        }

        @media (max-width: 480px) {
            body {
                background-size: 40%, 40%;
                background-position: right 5px bottom 5px, left 5px bottom 5px;
            }
        }

        h2 {
            padding-bottom: 1px;
            font-weight: bold;
        }

        .card-spot {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.3), -0px -10px 10px rgba(0, 0, 0, 0.3);
            background: white;
            padding: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            font-size: 14px;
        }

        .button:hover {
            background-color: #e0a800;
            color: white;
            transform: scale(1.05);
        }

        .button-palang-spot {
            background-color: #ffdc40;
            border: none;
            color: black;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .button-white {
            background-color: white;
            border: 1px solid black;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .form-control {
            color: #919191;
            border: 1px solid #919191;
            transition: border-color 0.3s;
        }

        .input-group-text {
            background-color: white;
            border: 1px solid #919191;
            transition: border-color 0.3s;
            color: black;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            text-decoration: none;
            color: #ffc107;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        img {
            width: 100%;
            max-width: 200px;
            margin: 0 auto 25px;
            display: block;
        }

        .alert {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .alert .fa-check-circle {
            font-size: 1.2rem;
            margin-right: 8px;
        }

        .btn-close {
            color: white;
            opacity: 0.8;
        }

        .btn-close:hover {
            color: black;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-spot">
            <img src="{{ asset('images/spot-logo.png') }}" alt="SPOT Logo" />
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('status') == 'success')
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2" style="font-size: 1.25rem;"></i> <!-- Ukuran ikon disesuaikan -->
                    <span style="font-size: 1rem;">{{ session('message') }}</span> <!-- Ukuran teks disesuaikan -->
                </div>
                <i class="bi bi-x" data-bs-dismiss="alert" aria-label="Close" style="cursor: pointer; font-size: 1.25rem;"></i>
            </div>
            @endif

            @if (session('status') == 'error')
            <div class="alert alert-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2" style="font-size: 1.25rem;"></i> <!-- Ikon error -->
                    <span>{{ session('message') }}</span>
                </div>
            </div>
            @endif


            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3 input-group">
                    <span class="input-group-text border border-black text-black"><i class="bi bi-person-fill"></i></span>
                    <input class="form-control border border-black" type="text" name="id" placeholder="NIM/NIP/NID" required />
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text border border-black text-black"><i class="bi bi-lock-fill"></i></span>
                    <input class="form-control border border-black"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Kata sandi"
                        required />
                    <span class="input-group-text border border-black text-black" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <button type="submit" class="button button-palang-spot w-100">Masuk</button>
            </form>
            <div class="register-link">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar!</a></p>
            </div>
        </div>
    </div>
    @if (session('pendaftaran'))
    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #FFDC40;">
                    <h5 class="modal-title" id="successModalLabel">Pendaftaran Berhasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div>
                        <i class="fas fa-check-circle" style="color: green; font-size: 3em; padding-bottom:10px;"></i>
                    </div>
                    <p>Pendaftaran anda berhasil.<br>Tunggu konfirmasi dari pengelola parkir!</p>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn" style="background-color: #FFDC40; color: black; width: 100px;" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            if ("{{ session('pendaftaran') }}") {
                $('#successModal').modal('show');
            }
        });

        document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordField = document.getElementById("password");
            const icon = this.querySelector("i");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        });
    </script>

</body>

</html>